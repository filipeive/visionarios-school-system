<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    public function access()
    {
        $demoUser = User::where('email', 'demo@visionarios.co.mz')->first();

        if (!$demoUser) {
            return redirect()->route('login')->with('error', 'Conta demo não encontrada.');
        }

        Auth::login($demoUser);

        return redirect()->route('dashboard')->with('success', 'Bem-vindo à demonstração do Visionários School System!');
    }
}
