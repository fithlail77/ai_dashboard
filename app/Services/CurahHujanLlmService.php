<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class CurahHujanLlmService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $model;
    protected int $timeout;

    /**
     * Sesuaikan daftar Tabel, Kolom, dan Measure sesuai Semantic Model Power BI Anda
     */
    protected string $datasetSchema = <<<'SCHEMA'
Daftar Tabel, Kolom, beserta TIPE DATA pada Power BI Dataset (Curah Hujan & Air Sungai):

1. 'public ch_gab' (Tabel Fakta Utama Curah Hujan Semua PT: GUM, PAM, TBSM)
   - 'public ch_gab'[pt] (Tipe: Text -> berisi "GUM", "PAM", "TBSM")
   - 'public ch_gab'[estate] (Tipe: Text -> berisi nama kebun seperti "GMO", "Melamor", "Mulau", "Ngaring", "Sedadung", "Tugang", "GMOP", "KPS", "KPT1", "KPT2", "KPU1", "KPU2", "SGUE", "SKDE")
   - 'public ch_gab'[divisi] (Tipe: Text)
   - 'public ch_gab'[ch] (Tipe: Number/Decimal -> nilai curah hujan dalam mm)
   - 'public ch_gab'[date1] (Tipe: Date/Tanggal -> GUNAKAN KOLOM INI untuk filter tanggal/bulan/tahun!)
   - 'public ch_gab'[tanggal] (Tipe: Integer -> angka tanggal 1-31)
   - 'public ch_gab'[tahun] (Tipe: Integer -> angka tahun seperti 2025, 2026)
   - 'public ch_gab'[bulan] (Tipe: Text -> JANGAN dibandingkan dengan angka! Gunakan MONTH('public ch_gab'[date1]) jika ingin filter angka bulan 1-12)
   - 'public ch_gab'[label_hujan] (Tipe: Text)

2. 'public air_sungais' (Tabel Fakta Tinggi Air Sungai dalam cm)
   - 'public air_sungais'[tanggal1] (Tipe: Date/Tanggal -> GUNAKAN KOLOM INI untuk filter tanggal/bulan/tahun!)
   - 'public air_sungais'[pagi_cm] (Tipe: Number -> tinggi air pagi dalam cm)
   - 'public air_sungais'[sore_cm] (Tipe: Number -> tinggi air sore dalam cm)
   - 'public air_sungais'[rataan] (Tipe: Number -> rata-rata tinggi air dalam cm)
   - 'public air_sungais'[batas_atas] (Tipe: Number)
   - 'public air_sungais'[Batas_bawah] (Tipe: Number)

3. 'CalendarTable' (Tabel Dimensi Kalender)
   - 'CalendarTable'[Date] (Tipe: Date/Tanggal)
   - 'CalendarTable'[bulan.order] (Tipe: Integer -> angka urutan bulan 1 sampai 12)
   - 'CalendarTable'[bulan] (Tipe: Text)
   - 'CalendarTable'[NamaBulan] (Tipe: Text)
   - 'CalendarTable'[tahun] (Tipe: Text/String -> JANGAN dibandingkan langsung dengan angka tanpa VALUE() atau gunakan YEAR('CalendarTable'[Date]))

4. Explicit Measures (bisa langsung dipanggil):
   - [NILAI CH MTD], [NILAI CH Last Date], [Rata rata Harian], [HH MTD text], [LD], [LD MTD]
SCHEMA;

    public function __construct()
    {
        $this->baseUrl = config('powerbi.llm.base_url');
        $this->apiKey  = config('powerbi.llm.api_key');
        $this->model   = config('powerbi.llm.model');
        $this->timeout = config('powerbi.llm.timeout');
    }

    /**
     * Helper method untuk memanggil endpoint OpenAI-compatible di 9Router
     */
    protected function callNineRouter(array $messages, float $temperature = 0.1): array
    {
        $endpoint = "{$this->baseUrl}/chat/completions";

        $response = Http::withToken($this->apiKey)
            ->withoutVerifying()
            ->timeout($this->timeout)
            ->acceptJson()
            ->post($endpoint, [
                'model'       => $this->model,
                'messages'    => $messages,
                'temperature' => $temperature,
            ]);

        if ($response->failed()) {
            throw new RuntimeException("9Router Error ({$response->status()}): " . $response->body());
        }

        return [
            'content' => trim($response->json('choices.0.message.content', '')),
            'model'   => $response->json('model', $this->model), // Menampilkan model aktual yang dipilih 9Router
        ];
    }

    /**
     * Step 1: Ubah pertanyaan user menjadi DAX Query valid (mendukung multi-turn context)
     */
    public function questionToDax(string $question, array $history = []): array
    {
        $systemPrompt = <<<PROMPT
Anda adalah pakar Power BI DAX kelas dunia. Tugas Anda HANYA membuat 1 kueri DAX valid yang diawali dengan kata kunci `EVALUATE`.

{$this->datasetSchema}

ATURAN WAJIB ANTI-ERROR DAX:
1. DILARANG KERAS MENULIS TEKS PENJELASAN ATAU MONOLOG:
   - JANGAN PERNAH menulis catatan internal, keraguan, monolog seperti "Hmm...", "Perbaikan:", "Versi final:", atau analisis apapun.
   - HANYA KEMBALIKAN 1 KODE DAX UTUH TANPA PENJELASAN.
2. ATURAN PENGELOMPOKAN (GROUPING) DI SUMMARIZECOLUMNS:
   - Kolom grouping HARUS berupa kolom fisik tabel (seperti `'public ch_gab'[estate]`, `'CalendarTable'[NamaBulan]`), TIDAK BOLEH berupa ekspresi kalkulasi (seperti `MONTH(...)`, `YEAR(...)`, dsb).
   - Jika pengguna meminta pengelompokan per bulan ("setiap bulan", "per bulan", "tren bulanan"):
     SELALU gunakan kolom grouping fisik: `'CalendarTable'[bulan.order]` dan `'CalendarTable'[NamaBulan]` (atau `'public ch_gab'[bulan]`).
3. ATURAN FILTER BULAN & TAHUN:
   - Kolom `'CalendarTable'[bulan]` dan `'public ch_gab'[bulan]` bertipe TEKS.
   - Untuk memfilter angka bulan tertentu (misal bulan 8), gunakan fungsi `MONTH('public ch_gab'[date1]) = 8`.
   - Untuk memfilter tahun tertentu, gunakan `YEAR('public ch_gab'[date1]) = <tahun>`.
4. ATURAN MULTI-TURN CONVERSATION MEMORY:
   - Jika riwayat percakapan sebelumnya dilampirkan, periksa apakah pertanyaan saat ini merujuk pada entitas, filter, estate, atau topik sebelumnya (misal: "lalu bagaimana dengan tahun 2025?", "bagaimana di PT lain?"). Gunakan konteks tersebut untuk melengkapi kueri DAX.
   - Namun jika pertanyaan saat ini adalah topik baru yang tidak berkaitan dengan riwayat sebelumnya, buat kueri mandiri dari awal.

5. ATURAN STANDAR PERHITUNGAN RATA-RATA CURAH HUJAN & HARI HUJAN (STANDAR OPERASIONAL PERKEBUNAN):
   - Di perkebunan kelapa sawit, data curah hujan dicatat di setiap stasiun/divisi. Oleh karena itu, curah hujan wilayah (per Estate, per PT, atau Total Seluruh Kebun) BUKAN sekadar penjumlahan mentah `SUM('public ch_gab'[ch])` karena akan menghasilkan angka ribuan mm yang tidak realistis.
   - PATOKAN RATA-RATA CURAH HUJAN:
     Total Curah Hujan HARUS DIBAGI DENGAN TOTAL DARI JUMLAH DIVISI DARI SEMUA ESTATE yang berada di bawah lingkup tersebut!
     * Rumus DAX Total Divisi yang BENAR:
       `COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi]))`
     * Rumus DAX Rata-Rata Curah Hujan (mm):
       `DIVIDE(SUM('public ch_gab'[ch]), COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])))`
     * PERINGATAN KERAS: JANGAN PERNAH MENGGUNAKAN `DISTINCTCOUNT('public ch_gab'[divisi])`!
       Nomor divisi (misal: 0, 1, 2, 3...) digunakan berulang pada berbagai estate. DISTINCTCOUNT hanya akan menghasilkan 6-7 divisi padahal total divisi dari semua estate berjumlah 12 hingga 22 divisi!
   - PATOKAN HARI HUJAN (HH):
     Hari dinyatakan Hari Hujan jika data curah hujan DI ATAS 0 mm (`ch > 0`).
     * Rumus DAX Hari Hujan (jumlah tanggal unik dengan curah hujan > 0):
       `COUNTROWS(FILTER(DISTINCT('public ch_gab'[date1]), CALCULATE(SUM('public ch_gab'[ch])) > 0))`
   - Kapanpun pengguna menanyakan "curah hujan", "rata-rata curah hujan", "total rata-rata curah hujan", "hari hujan", atau perbandingan curah hujan antar PT/Estate/Bulan:
     SELALU sertakan kalkulasi `"Total_Divisi"`, `"Rata_Rata_CH"`, dan `"Hari_Hujan"`.

6. CONTOH KUERI DAX STANDAR PERKEBUNAN:
Contoh 1: Rekap Rata-Rata CH & Hari Hujan per PT (Januari 2023):
EVALUATE
SUMMARIZECOLUMNS(
    'public ch_gab'[pt],
    FILTER(
        ALL('public ch_gab'),
        'public ch_gab'[date1] >= DATE(2023, 1, 1) &&
        'public ch_gab'[date1] <= DATE(2023, 1, 31)
    ),
    "Total_Divisi", COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])),
    "Rata_Rata_CH", DIVIDE(
        SUM('public ch_gab'[ch]),
        COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi]))
    ),
    "Hari_Hujan", COUNTROWS(
        FILTER(
            DISTINCT('public ch_gab'[date1]),
            CALCULATE(SUM('public ch_gab'[ch])) > 0
        )
    )
)
ORDER BY [Rata_Rata_CH] DESC

Contoh 2: Rekap Rata-Rata CH & Hari Hujan per Estate di PT PAM (Januari 2023):
EVALUATE
SUMMARIZECOLUMNS(
    'public ch_gab'[estate],
    FILTER(
        ALL('public ch_gab'),
        'public ch_gab'[pt] = "PAM" &&
        MONTH('public ch_gab'[date1]) = 1 &&
        YEAR('public ch_gab'[date1]) = 2023
    ),
    "Total_Divisi", COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])),
    "Rata_Rata_CH", DIVIDE(
        SUM('public ch_gab'[ch]),
        COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi]))
    ),
    "Hari_Hujan", COUNTROWS(
        FILTER(
            DISTINCT('public ch_gab'[date1]),
            CALCULATE(SUM('public ch_gab'[ch])) > 0
        )
    )
)
ORDER BY [Rata_Rata_CH] DESC

Contoh 3: Tren Rata-Rata Curah Hujan Bulanan (Tahun 2023):
EVALUATE
SUMMARIZECOLUMNS(
    'CalendarTable'[bulan.order],
    'CalendarTable'[NamaBulan],
    FILTER(
        ALL('public ch_gab'),
        YEAR('public ch_gab'[date1]) = 2023
    ),
    "Total_Divisi", COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])),
    "Rata_Rata_CH", DIVIDE(
        SUM('public ch_gab'[ch]),
        COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi]))
    ),
    "Hari_Hujan", COUNTROWS(
        FILTER(
            DISTINCT('public ch_gab'[date1]),
            CALCULATE(SUM('public ch_gab'[ch])) > 0
        )
    )
)
ORDER BY 'CalendarTable'[bulan.order]

Contoh 4: Total Rata-Rata Curah Hujan Keseluruhan (Semua PT & Semua Estate):
EVALUATE
SUMMARIZECOLUMNS(
    FILTER(
        ALL('public ch_gab'),
        MONTH('public ch_gab'[date1]) = 1 &&
        YEAR('public ch_gab'[date1]) = 2023
    ),
    "Total_Divisi", COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])),
    "Rata_Rata_CH", DIVIDE(
        SUM('public ch_gab'[ch]),
        COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi]))
    ),
    "Hari_Hujan", COUNTROWS(
        FILTER(
            DISTINCT('public ch_gab'[date1]),
            CALCULATE(SUM('public ch_gab'[ch])) > 0
        )
    )
)

7. ATURAN LEVEL DETAIL GROUPING:
   - Jika pengguna meminta data curah hujan untuk rentang tanggal (misalnya "1 - 30 Januari 2023"):
     Kecuali pengguna secara EKSPLISIT menyebut kata "divisi" atau "stasiun", JANGAN menambahkan kolom 'public ch_gab'[divisi] ke dalam grouping SUMMARIZECOLUMNS!
     Gunakan level grouping yang proporsional dan relevan:
     * Agregasi per Tanggal: ('public ch_gab'[date1]) -> Menghasilkan rata-rata harian (hanya 30 baris).
     * ATAU Agregasi per Tanggal & PT: ('public ch_gab'[date1], 'public ch_gab'[pt]) -> Menghasilkan data per PT per hari (~90 baris).
     * ATAU Agregasi per Tanggal & Estate: ('public ch_gab'[date1], 'public ch_gab'[estate]) jika meminta per estate.
PROMPT;

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Masukkan riwayat percakapan sebelumnya jika ada
        if (!empty($history)) {
            foreach ($history as $item) {
                if ($item['role'] === 'user') {
                    $messages[] = ['role' => 'user', 'content' => $item['content']];
                } elseif ($item['role'] === 'assistant') {
                    $prevDax = !empty($item['dax']) ? "Kueri DAX sebelumnya:\n" . $item['dax'] : $item['content'];
                    $messages[] = ['role' => 'assistant', 'content' => $prevDax];
                }
            }
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        $result = $this->callNineRouter($messages, 0.1);

        $cleanDax = $this->extractCleanDax($result['content']);

        if (!str_starts_with(strtoupper($cleanDax), 'EVALUATE')) {
            throw new RuntimeException("LLM tidak menghasilkan kueri EVALUATE yang valid. Output: {$cleanDax}");
        }

        return [
            'dax'   => $cleanDax,
            'model' => $result['model'],
        ];
    }

    /**
     * Auto-Retry Self-Healing DAX:
     * Memperbaiki kueri DAX yang gagal dieksekusi berdasarkan pesan error Power BI engine
     */
    public function fixDaxQuery(string $question, string $failedDax, string $errorMessage): array
    {
        $systemPrompt = <<<PROMPT
Anda adalah pakar Power BI DAX kelas dunia. Kueri DAX sebelumnya mengalami kegagalan eksekusi pada Power BI REST API.
Tugas Anda adalah MEMPERBAIKI kueri tersebut berdasarkan pesan error yang dikembalikan oleh engine Power BI.

{$this->datasetSchema}

ATURAN PERBAIKAN:
1. HANYA kembalikan 1 kode DAX utuh yang sudah diperbaiki, diawali dengan EVALUATE.
2. DILARANG KERAS menuliskan teks penjelasan, monolog, atau perdebatan seperti 'Hmm...', 'Perbaikan:', 'Versi final:'.
3. Analisis pesan error teknis dari Power BI dengan teliti dan perbaiki sintaks, nama kolom, fungsi filter, atau pengelompokan yang bermasalah.
4. Pastikan kolom grouping pada SUMMARIZECOLUMNS adalah kolom fisik (seperti 'public ch_gab'[estate], 'CalendarTable'[NamaBulan], dsb), bukan formula kalkulasi.
5. ATURAN STANDAR PERKEBUNAN UNTUK CURAH HUJAN & HARI HUJAN:
   - Rata-rata curah hujan wilayah dihitung dari total curah hujan dibagi total seluruh divisi dari semua estate:
     `DIVIDE(SUM('public ch_gab'[ch]), COUNTROWS(SUMMARIZE('public ch_gab', 'public ch_gab'[estate], 'public ch_gab'[divisi])))`
   - JANGAN gunakan DISTINCTCOUNT('public ch_gab'[divisi]) karena nomor divisi berulang antar estate.
   - Hari Hujan (HH) dihitung dari tanggal unik dengan curah hujan > 0 mm:
     `COUNTROWS(FILTER(DISTINCT('public ch_gab'[date1]), CALCULATE(SUM('public ch_gab'[ch])) > 0))`
PROMPT;

        $userPrompt = <<<MSG
Pertanyaan Pengguna:
{$question}

Kueri DAX yang GAGAL:
{$failedDax}

Pesan Error Teknis Power BI:
{$errorMessage}

Tolong berikan kueri DAX pengganti yang valid dan dapat dieksekusi Power BI.
MSG;

        $result = $this->callNineRouter([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ], 0.1);

        $cleanDax = $this->extractCleanDax($result['content']);

        if (!str_starts_with(strtoupper($cleanDax), 'EVALUATE')) {
            throw new RuntimeException("Self-Healing DAX gagal: output tidak berawalan EVALUATE. Output: {$cleanDax}");
        }

        return [
            'dax'   => $cleanDax,
            'model' => $result['model'],
        ];
    }

    /**
     * Ekstrak kueri DAX murni dan buang monolog/catatan AI jika ada
     */
    protected function extractCleanDax(string $rawContent): string
    {
        // 1. Buang tag <think>...</think> jika ada
        $text = preg_replace('/<think>.*?<\/think>/s', '', $rawContent);

        // 2. Jika ada blok ```dax ... ```, ambil blok kueri yang mengandung EVALUATE
        if (preg_match_all('/```(?:dax|DAX)?\s*([\s\S]*?)```/', $text, $codeBlocks)) {
            for ($i = count($codeBlocks[1]) - 1; $i >= 0; $i--) {
                $block = trim($codeBlocks[1][$i]);
                if (stripos($block, 'EVALUATE') !== false) {
                    $text = $block;
                    break;
                }
            }
        }

        // 3. Jika ada beberapa EVALUATE di teks karena AI mencoba beberapa kali, ambil EVALUATE terakhir
        $lastEvalPos = strripos($text, 'EVALUATE');
        if ($lastEvalPos !== false) {
            $text = substr($text, $lastEvalPos);
        }

        // 4. Bersihkan sisa-sisa markdown backticks
        $text = str_replace(['```dax', '```DAX', '```'], '', $text);
        $text = trim($text);

        // 5. Potong jika di bagian akhir ada teks penutup komentar
        if (preg_match('/^(.*?)(?:\n\s*(?:Catatan|Penjelasan|Note|Semoga)\s*:[\s\S]*)$/is', $text, $matchCut)) {
            $text = trim($matchCut[1]);
        }

        return $text;
    }

    /**
     * Step 2: Terjemahkan hasil JSON dari Power BI menjadi penjelasan bisnis & grafik interaktif
     */
    public function summarizeResult(string $question, array $rows, array $history = []): array
    {
        $totalRowsCount = count($rows);
        $compressed = $this->cleanAndCompressRows($rows, 1000);
        $sampleData = json_encode($compressed, JSON_UNESCAPED_SLASHES);

        $systemPrompt = <<<PROMPT
Anda adalah Senior Data Analyst AI untuk Dashboard Curah Hujan & Air Sungai.
Tugas Anda adalah menjawab pertanyaan pengguna dalam Bahasa Indonesia yang profesional, jelas, dan memberikan insight bisnis yang tajam berdasarkan data hasil kueri Power BI (JSON) yang disediakan.
Jika pengguna mengajukan pertanyaan lanjutan (misal membandingkan dengan data sebelumnya), integrasikan konteks riwayat sebelumnya dengan alami.

PANDUAN INTERPRETASI DATA:
1. Data JSON yang disediakan adalah data aktual yang dieksekusi langsung dari Semantic Model Power BI (Total baris dari kueri: {$totalRowsCount} baris).
2. Data ini sudah mencakup seluruh periode/rentang tanggal yang diminta pengguna. JANGAN PERNAH berasumsi bahwa data terputus di tengah periode (misal: JANGAN merekomendasikan query lanjutan tanggal 8-30 Januari karena rentang data sudah lengkap dari tanggal 1 sampai 30).
3. Berikan tabel ringkasan data yang rapi, mudah dibaca, dan padat (misal agregasi harian, per estate, atau per PT) serta visualisasikan dalam grafik Chart.js yang informatif.
4. STANDAR PERHITUNGAN CURAH HUJAN & HARI HUJAN PERKEBUNAN:
   - "Rata_Rata_CH" adalah rata-rata curah hujan wilayah dalam satuan milimeter (mm), yang diperoleh dari total akumulasi curah hujan dibagi total seluruh divisi dari semua estate terkait (bukan penjumlahan mentah ribuan mm).
   - "Total_Divisi" adalah jumlah seluruh divisi/stasiun pantau yang aktif berkontribusi dalam perhitungan.
   - "Hari_Hujan" (HH) adalah jumlah hari yang tercatat memiliki curah hujan di atas 0 mm (> 0 mm) pada periode tersebut.
   - Sajikan data curah hujan rata-rata dan hari hujan tersebut secara jelas dan mudah dimengerti.


ATURAN PENTING VISUALISASI GRAFIK / CHART:
1. JANGAN PERNAH MENGHASILKAN KODE PYTHON (seperti `import matplotlib`, `import seaborn`, `plt.bar()`, `plt.plot()`, `plt.show()`, atau kode pandas). Aplikasi ini BUKAN console/Jupyter Notebook, melainkan Web Application yang merender grafik langsung pada elemen HTML5 <canvas> menggunakan Chart.js.
2. Jika pengguna meminta "grafik", "chart", "visual", "diagram batang/garis", "tren", "perbandingan visual", ATAU jika pertanyaan membandingkan beberapa kategori/estate/periode:
   WAJIB sertakan blok visualisasi Chart.js menggunakan format tag:
```chartjs
{
  "type": "bar",
  "data": {
    "labels": ["Label1", "Label2"],
    "datasets": [
      {
        "label": "2024",
        "data": [100.5, 200.3],
        "backgroundColor": "rgba(59, 130, 246, 0.85)",
        "borderColor": "#2563eb",
        "borderWidth": 1
      },
      {
        "label": "2025",
        "data": [120.1, 190.4],
        "backgroundColor": "rgba(16, 185, 129, 0.85)",
        "borderColor": "#059669",
        "borderWidth": 1
      }
    ]
  },
  "options": {
    "responsive": true,
    "maintainAspectRatio": false,
    "plugins": {
      "title": {
        "display": true,
        "text": "Judul Grafik Yang Jelas & Menarik"
      },
      "legend": {
        "position": "top"
      }
    },
    "scales": {
      "y": {
        "beginAtZero": true,
        "title": {
          "display": true,
          "text": "Curah Hujan (mm) / Tinggi Air (cm)"
        }
      }
    }
  }
}
```
3. Palet Warna Chart.js yang dianjurkan (modern & kontras):
   - Biru: bg `rgba(59, 130, 246, 0.85)`, border `#2563eb`
   - Hijau Emerald: bg `rgba(16, 185, 129, 0.85)`, border `#059669`
   - Amber / Oranye: bg `rgba(245, 158, 11, 0.85)`, border `#d97706`
   - Ungu: bg `rgba(139, 92, 246, 0.85)`, border `#7c3aed`
   - Rose / Merah: bg `rgba(244, 63, 94, 0.85)`, border `#e11d48`
4. Selalu sertakan narasi ringkas dalam Bahasa Indonesia (poin insight penting: nilai tertinggi, terendah, tren peningkatan/penurunan).
PROMPT;

        $userPrompt = "Pertanyaan Pengguna: {$question}\n\nData Hasil Query Power BI (JSON):\n{$sampleData}";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Lampirkan riwayat percakapan sebelumnya untuk konteks narasi berkelanjutan
        if (!empty($history)) {
            foreach ($history as $item) {
                if ($item['role'] === 'user') {
                    $messages[] = ['role' => 'user', 'content' => $item['content']];
                } elseif ($item['role'] === 'assistant') {
                    $brief = mb_substr(strip_tags($item['content']), 0, 300);
                    $messages[] = ['role' => 'assistant', 'content' => $brief];
                }
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userPrompt];

        $result = $this->callNineRouter($messages, 0.3);

        // Bersihkan tag <think> jika 9Router melakukan fallback ke model reasoning (seperti DeepSeek-R1 / Qwen)
        $cleanAnswer = trim(preg_replace('/<think>.*?<\/think>/s', '', $result['content']));

        // Fallback filter: jika model tetap memproduksi kode matplotlib python, otomatis ubah menjadi blok chartjs
        $cleanAnswer = $this->convertPythonMatplotlibToChartJs($cleanAnswer);

        return [
            'answer' => $cleanAnswer,
            'model'  => $result['model'],
        ];
    }

    /**
     * Fallback converter jika LLM masih menghasilkan kode Matplotlib Python
     */
    protected function convertPythonMatplotlibToChartJs(string $text): string
    {
        if (!str_contains($text, 'matplotlib') && !str_contains($text, 'plt.')) {
            return $text;
        }

        return preg_replace_callback('/```(?:python|py)?\s*([\s\S]*?)```/i', function ($matches) {
            $code = $matches[1];
            if (!str_contains($code, 'matplotlib') && !str_contains($code, 'plt.')) {
                return $matches[0];
            }

            preg_match_all('/([a-zA-Z0-9_]+)\s*=\s*\[(.*?)\]/s', $code, $listMatches, PREG_SET_ORDER);
            if (empty($listMatches)) {
                return $matches[0];
            }

            $stringLists = [];
            $numberLists = [];

            foreach ($listMatches as $lm) {
                $varName = trim($lm[1]);
                $rawItems = trim($lm[2]);

                if (preg_match('/["\']/', $rawItems)) {
                    preg_match_all('/["\']([^"\']+)["\']/', $rawItems, $items);
                    $stringLists[$varName] = $items[1];
                } else {
                    $nums = array_filter(array_map('trim', explode(',', $rawItems)), fn($v) => is_numeric($v));
                    if (!empty($nums)) {
                        $numberLists[$varName] = array_map(fn($v) => (float)$v, array_values($nums));
                    }
                }
            }

            if (empty($stringLists) || empty($numberLists)) {
                return $matches[0];
            }

            $labels = reset($stringLists);

            $title = 'Grafik Analisis Curah Hujan';
            if (preg_match('/(?:set_title|title)\s*\(\s*[\'"]([^\'"]+)[\'"]/i', $code, $titleMatch)) {
                $title = str_replace(['\n', "\n"], ' - ', $titleMatch[1]);
            }

            $palette = [
                ['bg' => 'rgba(59, 130, 246, 0.85)', 'border' => '#2563eb'],
                ['bg' => 'rgba(16, 185, 129, 0.85)', 'border' => '#059669'],
                ['bg' => 'rgba(245, 158, 11, 0.85)', 'border' => '#d97706'],
                ['bg' => 'rgba(139, 92, 246, 0.85)', 'border' => '#7c3aed'],
                ['bg' => 'rgba(244, 63, 94, 0.85)', 'border' => '#e11d48'],
            ];

            $datasets = [];
            $colorIdx = 0;
            foreach ($numberLists as $name => $values) {
                $cleanLabel = trim(preg_replace('/_+/', ' ', preg_replace('/^ch[_\s]*/i', 'CH ', $name)));
                $c = $palette[$colorIdx % count($palette)];
                $colorIdx++;

                $datasets[] = [
                    'label' => $cleanLabel,
                    'data' => $values,
                    'backgroundColor' => $c['bg'],
                    'borderColor' => $c['border'],
                    'borderWidth' => 1,
                ];
            }

            $chartConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $labels,
                    'datasets' => $datasets,
                ],
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => $title,
                        ],
                        'legend' => [
                            'position' => 'top',
                        ],
                    ],
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                            'title' => [
                                'display' => true,
                                'text' => 'Curah Hujan (mm)',
                            ],
                        ],
                    ],
                ],
            ];

            return "```chartjs\n" . json_encode($chartConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n```";
        }, $text);
    }

    /**
     * Bersihkan nama kolom Power BI dan kompres data agar hemat token hingga 1000 baris
     */
    protected function cleanAndCompressRows(array $rows, int $limit = 1000): array
    {
        $sliced = array_slice($rows, 0, $limit);
        $cleaned = [];

        foreach ($sliced as $row) {
            $item = [];
            foreach ($row as $key => $val) {
                // Bersihkan nama tabel dan kurung: 'public ch_gab[date1]' -> 'date1', '[Total_CH]' -> 'Total_CH'
                $cleanKey = preg_replace('/^.*?\[(.*?)\]$/', '$1', $key);
                $cleanKey = trim($cleanKey, '[]');

                // Format tanggal ISO menjadi tanggal bersih Y-m-d
                if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}T/', $val)) {
                    $val = substr($val, 0, 10);
                }

                // Bulatkan angka desimal panjang
                if (is_float($val)) {
                    $val = round($val, 2);
                }

                $item[$cleanKey] = $val;
            }
            $cleaned[] = $item;
        }

        return $cleaned;
    }
}
