<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth page.
     */
    public function redirect(\Illuminate\Http\Request $request)
    {
        session(['google_auth_type' => $request->query('type', 'login')]);
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google callback, login or create user.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $authType = session('google_auth_type', 'login');

            // Find existing user by google_id or email
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Maybe user registered with email/password first
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar'    => $googleUser->avatar,
                    ]);
                } else {
                    // Create a brand new user as 'customer'
                    $user = User::create([
                        'name'      => $googleUser->name,
                        'email'     => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar'    => $googleUser->avatar,
                        'role'      => 'customer',
                        'password'  => null,
                    ]);
                }
            }

            if ($authType === 'otp') {
                $email = $user->email;
                $name = $user->name;
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                \Illuminate\Support\Facades\Session::put('otp_code',    $otp);
                \Illuminate\Support\Facades\Session::put('otp_email',   $email);
                \Illuminate\Support\Facades\Session::put('otp_name',    $name);
                \Illuminate\Support\Facades\Session::put('otp_expires', now()->addMinutes(5)->timestamp);

                try {
                    \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $otp) {
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
                } catch (\Exception $e) {
                    return redirect('/')->with('error', 'Gagal mengirim OTP: ' . $e->getMessage());
                }

                return redirect('/')->with('show_otp_verify', $email);
            }

            // Normal Google Login
            Auth::login($user, true);

            // Google login always goes to customer dashboard
            return redirect()->route('customer.dashboard');

        } catch (\Exception $e) {
            return redirect('/')
                ->with('error', 'Login dengan Google gagal: [' . get_class($e) . '] ' . $e->getMessage());
        }
    }
}
