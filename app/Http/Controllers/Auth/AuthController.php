<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('blue-app.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Remove mask from auth (phone)
        if ($request->has('auth')) {
            $request->merge(['auth' => preg_replace('/\D/', '', $request->auth)]);
        }

        if ($request->auth == '' || $request->auth == null || $request->password == '') {
            return redirect()->back()->with('error', 'Telefone ou senha incorretos');
        }

        $user = User::where('phone', $request->auth)->orWhere('email', $request->auth)->first();

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }


        if (!$user) {
            return redirect()->back()->with('error', 'Telefone ou senha incorretos');
        }

        //Check user ban or unban
        if ($user->ban_unban == 'ban') {
            return redirect()->back()->with('error', 'Conta bloqueada, entre em contato com o suporte.');
        }

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                return redirect()->back()->with('error', 'Telefone ou senha incorretos');
            }
        } else {
            return redirect()->back()->with('error', 'Telefone ou senha incorretos');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
