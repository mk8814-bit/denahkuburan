<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    /**
     * Send OTP to the given email address.
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $name = $request->input('name');

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP + email + expiry in session (5 minutes)
        Session::put('otp_code',    $otp);
        Session::put('otp_email',   $email);
        Session::put('otp_name',    $name);
        Session::put('otp_expires', now()->addMinutes(5)->timestamp);

        // Send the OTP email
        try {
            Mail::send([], [], function ($message) use ($email, $otp) {
                $message->to($email)
                    ->subject('Kode OTP Login DenahMakam')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto;'>
                            <div style='background: #1a1a2e; padding: 24px; text-align: center; border-radius: 8px 8px 0 0;'>
                                <h2 style='color: white; margin: 0;'>🔐 Kode OTP Login</h2>
                            </div>
                            <div style='background: #f9f9f9; padding: 32px; text-align: center; border-radius: 0 0 8px 8px; border: 1px solid #e0e0e0;'>
                                <p style='color: #555; font-size: 1rem; margin-bottom: 24px;'>Gunakan kode berikut untuk login ke <strong>DenahMakam</strong>:</p>
                                <div style='background: white; border: 2px dashed #1a73e8; border-radius: 12px; padding: 20px; margin-bottom: 24px; display: inline-block;'>
                                    <span style='font-size: 2.5rem; font-weight: 800; letter-spacing: 12px; color: #1a73e8;'>{$otp}</span>
                                </div>
                                <p style='color: #888; font-size: 0.85rem;'>Kode berlaku selama <strong>5 menit</strong>.<br>Jangan bagikan kode ini kepada siapapun.</p>
                            </div>
                        </div>
                    ");
            });

            return response()->json(['success' => true, 'message' => "Kode OTP telah dikirim ke {$email}"]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify OTP and log the user in.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $sessionOtp     = Session::get('otp_code');
        $sessionEmail   = Session::get('otp_email');
        $sessionExpires = Session::get('otp_expires');

        // Validate session
        if (!$sessionOtp || !$sessionEmail || !$sessionExpires) {
            return response()->json(['success' => false, 'message' => 'Sesi OTP tidak ditemukan. Minta kode baru.'], 422);
        }

        // Check expiry
        if (now()->timestamp > $sessionExpires) {
            Session::forget(['otp_code', 'otp_email', 'otp_expires']);
            return response()->json(['success' => false, 'message' => 'Kode OTP sudah kadaluarsa. Minta kode baru.'], 422);
        }

        // Check email match
        if ($request->email !== $sessionEmail) {
            return response()->json(['success' => false, 'message' => 'Email tidak sesuai.'], 422);
        }

        // Check OTP code
        if ($request->otp !== $sessionOtp) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah!'], 422);
        }

        // OTP correct — clear session
        Session::forget(['otp_code', 'otp_email', 'otp_expires']);

        // Find or create user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $sessionName = Session::get('otp_name');
            $name = $sessionName ?: explode('@', $request->email)[0];
            $user = User::create([
                'name'     => ucfirst($name),
                'email'    => $request->email,
                'role'     => 'customer',
                'password' => null,
            ]);
        }

        Auth::login($user, true);

        return response()->json([
            'success'  => true,
            'redirect' => route('customer.dashboard'),
        ]);
    }
}
