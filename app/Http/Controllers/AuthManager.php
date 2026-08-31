<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

class AuthManager extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cerca l'utente
        $user = Users::where('Username', $request->username)->first();

        if (!$user || $user->Keyword !== $request->password) {
            return back()->with('error', 'Credenziali non valide.');
        }

        // Autenticazione manuale (salva i dati nella sessione)
        session(['user' => $user]);

        return redirect('/dashboard'); // Redirige a una pagina protetta
    }

    public function logout()
    {
        session()->forget('user'); // Rimuove l'utente dalla sessione
        return redirect('/login');
    }
}
