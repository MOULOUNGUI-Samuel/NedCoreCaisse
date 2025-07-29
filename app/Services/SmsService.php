<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiUrl;
    protected $accountUser;
    protected $password;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url');
        $this->accountUser = config('services.sms.account_user');
        $this->password = config('services.sms.password');
    }

    public function sendSms($phone, $message)
    {
        $response = Http::post($this->apiUrl, [
            'phone' => $phone,
            'text' => $message,
            'accountuser' => $this->accountUser,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }
}
