<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class CurahHujanPbiService
{
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $workspaceId;
    protected string $datasetId;

    public function __construct()
    {
        $this->tenantId     = (string) config('powerbi.tenant_id');
        $this->clientId     = (string) config('powerbi.client_id');
        $this->clientSecret = (string) config('powerbi.client_secret');
        $this->workspaceId  = (string) config('powerbi.workspace_id');
        $this->datasetId    = (string) config('powerbi.dataset_id');
    }

    /**
     * Ambil Access Token Azure AD dan simpan di Cache selama 50 menit
     */
    public function getAccessToken(): string
    {
        return Cache::remember('powerbi_service_token', 3000, function () {
            $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post($url, [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope'         => 'https://analysis.windows.net/powerbi/api/.default',
                ]);

            if ($response->failed()) {
                throw new RuntimeException('Gagal autentikasi ke Azure AD: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    /**
     * Eksekusi kueri DAX ke Power BI Dataset
     */
    public function executeDaxQuery(string $daxQuery): array
    {
        $token = $this->getAccessToken();
        $url = "https://api.powerbi.com/v1.0/myorg/groups/{$this->workspaceId}/datasets/{$this->datasetId}/executeQueries";

        $response = Http::withOptions(['verify' => false])
            ->withToken($token)
            ->timeout(30)
            ->post($url, [
                'queries' => [
                    ['query' => $daxQuery]
                ],
                'serializerSettings' => [
                    'includeNulls' => true
                ]
            ]);

        if ($response->failed()) {
            $status = $response->status();
            $bodyJson = $response->json();

            // Ambil pesan spesifik dari engine DAX Power BI
            $details = data_get($bodyJson, 'error.pbi.error.details', []);
            $daxEngineError = collect($details)
                ->map(fn($d) => data_get($d, 'detail.value'))
                ->filter()
                ->implode(' | ');

            $detailError = $daxEngineError
                ?: data_get($bodyJson, 'error.message')
                ?: data_get($bodyJson, 'error.code')
                ?: $response->body();

            throw new RuntimeException(
                "Gagal eksekusi DAX [HTTP {$status}]: {$detailError} || DAX: {$daxQuery}"
            );
        }

        return $response->json('results.0.tables.0.rows') ?? [];
    }
}
