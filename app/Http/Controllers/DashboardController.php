<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Admin Dashboard utama Tabler.
     */
    public function index(Request $request): View
    {
        $currentUser = $request->user();

        // Data statistik AI Dashboard PT GUM
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'ai_queries_today' => 1248,
            'api_tokens_active' => \Laravel\Sanctum\PersonalAccessToken::count(),
            'ai_latency_avg' => '142ms',
            'system_status' => 'Optimal (99.98%)',
        ];

        // Daftar aktivitas sistem / log AI terbaru
        $recentActivities = [
            [
                'title' => 'Permintaan Analisis Model AI #8821',
                'description' => 'Model Gemini Flash menyelesaikan inferensi klasifikasi data.',
                'time' => '2 menit yang lalu',
                'status' => 'Sukses',
                'status_class' => 'badge bg-success text-white',
            ],
            [
                'title' => 'Login Mobile Android - API Sanctum',
                'description' => 'Aplikasi Android Operator berhasil terautentikasi.',
                'time' => '15 menit yang lalu',
                'status' => 'Terkoneksi',
                'status_class' => 'badge bg-primary text-white',
            ],
            [
                'title' => 'Sinkronisasi Dataset PT GUM',
                'description' => 'Pembaruan data telemetri otomatis dari server operasional.',
                'time' => '1 jam yang lalu',
                'status' => 'Selesai',
                'status_class' => 'badge bg-info text-white',
            ],
            [
                'title' => 'Pemeriksaan Integritas Keamanan',
                'description' => 'Zero security anomaly detected. Rate-limiting aktif.',
                'time' => '3 jam yang lalu',
                'status' => 'Aman',
                'status_class' => 'badge bg-teal text-white',
            ],
        ];

        return view('admin.dashboard', compact('currentUser', 'stats', 'recentActivities'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Curah Hujan.
     */
    public function curahHujan(): View
    {
        $embedUrl = config('powerbi.embed_url')
            ?: 'https://app.powerbi.com/view?r=eyJrIjoiOGU0NGUxODQtYzQ5NC00Mzk0LWJlODctMjZlMGMyNmY3OWZjIiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';
        $reportId = config('powerbi.report_id');

        return view('admin.curah-hujan', compact('embedUrl', 'reportId'));
    }

    /**
     * Menampilkan embed Power BI Dashboard AWS (Automatic Weather Station).
     */
    public function aws(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiOTlmYzY3YWYtM2NhNC00ZmRjLWI0ZTEtMDg3ZjBjYjA0YWU5IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.aws', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Areal Statement.
     */
    public function arealStatement(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiMGRmZmEyM2MtODdiNi00NDBmLThlZWMtNjJkYjI4NWRhMjc2IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.areal-statement', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Produksi.
     */
    public function produksi(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiNDJjOTRkZDYtNzZmNC00M2RlLTg0OWMtNjhhOTNkYTM2MGUyIiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.produksi', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Analisa Produksi Kebun.
     */
    public function analisaProduksiKebun(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiNGM5YjFiNDctMzY3Ny00NDMyLTgwM2QtODVlMTkxMzEzMjM2IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.analisa-produksi-kebun', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Pemupukan & Perawatan.
     */
    public function pemupukanPerawatan(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiMDhjYjI5Y2QtYjM3OC00NzM5LTlmZTQtZGM1NzdhZmYyYWVmIiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.pemupukan-perawatan', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Fleet Cartrack.
     */
    public function fleetCartrack(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiMTFlMjFmNzktYTA5OC00OWY3LWI4ZTQtMzdlZmY3YzEzNzg3IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.fleet-cartrack', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Operasional Unit.
     */
    public function operasionalUnit(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiOTZkNDU1M2QtYmYxYi00MmU1LThjMjYtZmY4NGU2M2E3YmQ1IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.operasional-unit', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Infrastruktur Jalan.
     */
    public function infrastrukturJalan(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiMzQ3MmE0NTUtOGE4My00MTQ5LWFlMGYtYzNlYjViY2E5YzU2IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.infrastruktur-jalan', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Sampel Daun (LSU).
     */
    public function sampelDaunLsu(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiOTE0ZjA4N2EtZTJiMy00MmVkLWE1NzktZTI4Y2YwODAxMGI0IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.sampel-daun-lsu', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Assessmen Blok.
     */
    public function assessmenBlok(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiNWJiNzY3M2YtYWMwYy00YzIwLWFlZDgtZWE1ODI2Y2I2NjVlIiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.assessmen-blok', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Pembibitan.
     */
    public function pembibitan(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiN2QzM2JjMDEtYmZkZi00YmYzLTgwNTEtMTZmYzFkMzliZmE5IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.pembibitan', compact('embedUrl'));
    }

    /**
     * Menampilkan embed Power BI Dashboard Legal.
     */
    public function legal(): View
    {
        $embedUrl = 'https://app.powerbi.com/view?r=eyJrIjoiOGJlYmQ4MzktYzYyNy00M2Q4LWI2ZGUtNmEwYzJhN2QxY2M5IiwidCI6IjkzMzQ3NTJlLWIwM2EtNDUzNy04ZmY2LTU0ZDU3MGMzNWQyOCIsImMiOjEwfQ%3D%3D';

        return view('admin.legal', compact('embedUrl'));
    }
}
