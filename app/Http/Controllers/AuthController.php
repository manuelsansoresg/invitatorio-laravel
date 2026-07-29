<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

        // Si el admin desactivó la cuenta, no dejamos entrar.
        if (! $request->user()->activo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()
                ->withErrors(['email' => 'Tu cuenta está suspendida. Contacta al administrador.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Si el form pasó un ?next= válido (URL absoluta del mismo
        // sitio), respetamos esa redirección. Lo usa el checkout para
        // que el cliente vuelva al paquete que quería comprar después
        // de loguearse. intended() gana solo si next no es válido.
        $next = $request->input('next');
        if (is_string($next) && $this->isSafeLocalUrl($next)) {
            return redirect()->to($next);
        }

        $dashboard = $request->user()->isAdmin()
            ? route('admin.dashboard')
            : route('panel.invitaciones.index');

        return redirect()->intended($dashboard);
    }

    /**
     * Valida que una URL sea del mismo sitio (mismo host que APP_URL)
     * y relativa o absoluta-pero-local. Evita open redirects tipo
     * ?next=https://malicious.com.
     */
    private function isSafeLocalUrl(string $url): bool
    {
        $url = trim($url);
        if ($url === '' || $url[0] === '#') {
            return false;
        }
        // Relativa tipo /algo o /ruta?query — siempre seguras.
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return true;
        }
        // Absoluta: comparamos host.
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $urlHost = parse_url($url, PHP_URL_HOST);
        return $appHost && $urlHost && strcasecmp($appHost, $urlHost) === 0;
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
