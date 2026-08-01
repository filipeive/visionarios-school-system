<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Exibir painel de gestão de licença.
     */
    public function index()
    {
        $license = License::first();
        return view('admin.license.index', compact('license'));
    }

    /**
     * Atualizar dados da licença.
     */
    public function update(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'license_key' => 'required|string|max:255',
            'expires_at' => 'required|date',
            'plan' => 'required|string',
        ]);

        $license = License::firstOrNew();
        $license->fill([
            'client_name' => $request->client_name,
            'license_key' => $request->license_key,
            'expires_at' => $request->expires_at,
            'plan' => $request->plan,
            'activated_at' => $license->activated_at ?? now(),
        ]);
        $license->evaluateStatus();

        return redirect()->back()->with('success', 'Licença do sistema atualizada com sucesso!');
    }

    /**
     * Página exibida quando a licença está suspensa.
     */
    public function suspended()
    {
        $license = License::first();
        return view('admin.license.suspended', compact('license'));
    }
}
