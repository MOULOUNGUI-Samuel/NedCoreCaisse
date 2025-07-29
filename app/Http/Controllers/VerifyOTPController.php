<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Services\SmsService; // Ensure this class exists in the App\Services namespace

class VerifyOTPController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    private function clearSession()
    {
        Session::forget(['verification_code', 'verification_code_expires_at']);
    }
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verifiNumero(Request $request)
    {
        // Générer un code aléatoire
        $code = random_int(100000, 999999);
        $phone = $request->input('contact');
        $message = "Une demande de soins a été initiée avec votre identifiant. Veuillez confirmer en partageant ce code : " . $code . ". Contactez-nous si ce n'est pas vous.";

        // Vérifier le nombre de caractères
        $nombreCaracteres = strlen($message);

        // dd($data_demande);

        // try {
        //     $response = $this->smsService->sendSms($phone, $message);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Message envoyé avec succès.',
        //         'response' => $response,
        //     ]);


        $expiresAt = now()->addMinutes(3);
        // Stocker les infos en session pour la page suivante
        // Session::put('verification_code', $code);
        Session::put('verification_contact', $phone);
        Session::put('verification_code_expires_at', $expiresAt);
        // On envoie l'e-mail à l'utilisateur trouvé.
        // On passe UNIQUEMENT le code au Mailable.
        // Mail::to('yodingenierieia@gmail.com')->send(new EnvoiCode($code));

        // Rediriger vers la page de saisie du code (OTP)
        return redirect()->route('verifi-otp')->with([
            'success' => 'Un code a été envoyé.',
            'expires_at' => $expiresAt
        ]);
    }
    public function resendCode(Request $request)
    {
        if (!Session::has('verification_contact')) {
            return response()->json(['success' => false, 'message' => 'Session invalide. Veuillez recommencer.']);
        }
        // Générer un code aléatoire
        $code = Session::get('verification_contact');
        $phone = $request->input('contact');
        $message = "Une demande de soins a été initiée avec votre identifiant. Veuillez confirmer en partageant ce code : " . $code . ". Contactez-nous si ce n'est pas vous.";

        // Vérifier le nombre de caractères
        $nombreCaracteres = strlen($message);

        // dd($data_demande);

        // try {
        //     $response = $this->smsService->sendSms($phone, $message);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Message envoyé avec succès.',
        //         'response' => $response,
        //     ]);


        $expiresAt = now()->addMinutes(3);
        // Stocker les infos en session pour la page suivante
        // Session::put('verification_code', $code);
        Session::put('verification_contact', $phone);
        Session::put('verification_code_expires_at', $expiresAt);

        // On envoie l'e-mail à l'utilisateur trouvé.
        // On passe UNIQUEMENT le code au Mailable.
        // Mail::to('yodingenierieia@gmail.com')->send(new EnvoiCode($code));

        // Rediriger vers la page de saisie du code (OTP)
        return response()->json([
            'success' => true,
            'message' => 'Nouveau code envoyé avec succès.',
            'expires_at' => $expiresAt->toIso8601String()
        ]);
    }

    public function verifiOTP()
    {
         // S'assure que l'utilisateur est bien passé par l'étape précédente
         if (!Session::has('verification_contact')) {
            return redirect()->route('register')->withErrors('Votre session à expiré, merci de réesayer !');
        }
        return view('auth.verifi-otp');
    }

    public function verifyCode(Request $request)
    {
        if (!Session::has('verification_contact')) {
            return redirect()->route('register')->withErrors('Votre session à expiré, merci de réesayer !');
        }
        try {
            // ✅ 3. Gestion du succès
           
                // ---- SUCCÈS ----
                // Le code est correct et n'a pas expiré.
                // On nettoie la session pour ne pas pouvoir réutiliser le code.
                $this->clearSession();

                // Connectez l'utilisateur ou redirigez-le vers la page de réinitialisation du mot de passe
                Session::put('user_contact', Session::get('verification_contact')); // Marquer l'utilisateur comme vérifié
                Session::forget('verification_contact');

                return redirect()->route('mot-de-passe')->with('success', 'Vérification réussie ! Créer votre mot de passe.');
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la requête OTP.'], 500);
        }
    }

    public function motpasse(Request $request)
    {
        
        if (!Session::get('user_contact')) {
            return redirect()->route('register')->withErrors('Votre session à expiré, merci de réesayer !');
        }
        try {

                return view('auth.reset-password');
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la requête OTP.'], 500);
        }
    }

}
