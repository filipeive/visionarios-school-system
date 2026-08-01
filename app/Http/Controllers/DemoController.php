<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    public function access()
    {
        $demoUser = User::where('email', 'demo@visionarios.co.mz')->first()
            ?? User::where('email', 'admin@visionarios.co.mz')->first()
            ?? User::first();

        if (!$demoUser) {
            return redirect()->route('login')->with('error', 'Nenhum usuário cadastrado no sistema.');
        }

        Auth::login($demoUser);

        return redirect()->route('dashboard')->with('success', 'Bem-vindo à demonstração do Visionários School System!');
    }
}
