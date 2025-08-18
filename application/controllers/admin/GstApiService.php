<?php

namespace App\Services;

use App\Models\GstAuthToken;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GstApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('gst.api_base_url');
    }

    protected function getHeaders($token)
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function getGstinDetails($gstin)
    {
        $token = $this->getValidToken($gstin, 'einv');

        return Http::withHeaders($this->getHeaders($token->einv_auth_token))
            ->get($this->baseUrl . '/Master/GetGSTINDetails', [
                'gstin' => $gstin
            ])
            ->throw()
            ->json();
    }

    public function generateEwayBill(array $data)
    {
        $token = $this->getValidToken($data['gstin'], 'eway');

        return Http::withHeaders($this->getHeaders($token->eway_auth_token))
            ->post($this->baseUrl . '/ewaybill/generate', $data)
            ->throw()
            ->json();
    }

    public function getEwayBillStatus($ebn, $gstin)
    {
        $token = $this->getValidToken($gstin, 'eway');

        return Http::withHeaders($this->getHeaders($token->eway_auth_token))
            ->get($this->baseUrl . "/ewaybill/status/{$ebn}")
            ->throw()
            ->json();
    }

    public function cancelEwayBill($ebn, $gstin, $reason)
    {
        $token = $this->getValidToken($gstin, 'eway');

        return Http::withHeaders($this->getHeaders($token->eway_auth_token))
            ->delete($this->baseUrl . "/ewaybill/{$ebn}", ['reason' => $reason])
            ->throw()
            ->json();
    }

    public function generateEinvoice(array $data)
    {
        $token = $this->getValidToken($data['gstin'], 'einv');

        return Http::withHeaders($this->getHeaders($token->einv_auth_token))
            ->post($this->baseUrl . '/einvoice/generate', [
                'gstin' => $data['gstin'],
                'invoice_details' => array_merge($data, [
                    'irn_required' => true
                ])
            ])
            ->throw()
            ->json();
    }

    public function validateEinvoice($irn, $gstin)
    {
        $token = $this->getValidToken($gstin, 'einv');

        return Http::withHeaders($this->getHeaders($token->einv_auth_token))
            ->get($this->baseUrl . "/einvoice/validate/{$irn}")
            ->throw()
            ->json();
    }

    protected function getValidToken($gstin, $type)
    {
        $token = GstAuthToken::where('gstin', $gstin)
            ->where($type . '_token_expiry', '>', now())
            ->first();

        if (!$token) {
            $token = $this->refreshToken($gstin, $type);
        }

        return $token;
    }

    protected function refreshToken($gstin, $type)
    {
        $response = Http::post($this->baseUrl . '/auth/token', [
            'gstin' => $gstin,
            'type' => $type
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return GstAuthToken::updateOrCreate(
                ['gstin' => $gstin],
                [
                    $type . '_auth_token' => $data['token'],
                    $type . '_token_expiry' => Carbon::parse($data['expiry'])
                ]
            );
        }

        throw new \Exception('Failed to refresh token');
    }
}
