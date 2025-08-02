<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function authenticate($id)
    {

        $response = Http::withHeaders([
            'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            'Content-Type' => 'application/json'
        ])->get('https://nedcore.net/users/', [
            'id' => $id,
        ]);

        if ($response->successful() ) {
            $data = $response->json();
dd($data);
            
        } else {
            // Gérer l'erreur de récupération des bénéficiaires
            return back()->withErrors(['erreur' => 'Échec de récupération des données.']);
        }
    }
}
