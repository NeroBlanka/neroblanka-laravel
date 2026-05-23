<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class MfaController extends Controller
{
    public function showVerify(): View|RedirectResponse
    {
        if (session('admin_mfa_passed')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.mfa.verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = auth()->user();

        if (! $user->totp_enabled || ! $user->totp_secret) {
            session(['admin_mfa_passed' => true]);
            return redirect()->intended(route('admin.dashboard'));
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->totp_secret, $request->input('code'));

        if (! $valid) {
            return back()->withErrors(['code' => 'Code invalide. Vérifiez l\'heure de votre appareil.']);
        }

        session(['admin_mfa_passed' => true]);
        return redirect()->intended(route('admin.dashboard'));
    }

    public function showSetup(): View
    {
        $user = auth()->user();

        if (! $user->totp_secret) {
            $google2fa = app('pragmarx.google2fa');
            $secret = $google2fa->generateSecretKey();
            $user->update(['totp_secret' => $secret]);
        }

        $google2fa = app('pragmarx.google2fa');
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'Neroblanka'),
            $user->email,
            $user->totp_secret,
        );

        $qrCode = \BaconQrCode\Renderer\ImageRenderer::class;

        return view('admin.mfa.setup', [
            'secret' => $user->totp_secret,
            'qrUrl' => $qrCodeUrl,
        ]);
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = auth()->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->totp_secret, $request->input('code'));

        if (! $valid) {
            return back()->withErrors(['code' => 'Code invalide. Vérifiez votre app d\'authentification.']);
        }

        $user->update(['totp_enabled' => true]);
        session(['admin_mfa_passed' => true]);

        return redirect()->route('admin.dashboard')->with('success', 'MFA activé avec succès.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = auth()->user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->totp_secret, $request->input('code'));

        if (! $valid) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        $user->update(['totp_enabled' => false, 'totp_secret' => null]);
        session()->forget('admin_mfa_passed');

        return back()->with('success', 'MFA désactivé.');
    }
}
