<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Services\EvolutionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
    public function __construct(private EvolutionService $evolution) {}

    public function index(): View
    {
        $settings = CompanySetting::get();
        $instanceName = tenant('id');
        $connected = false;
        $qr = null;

        if ($this->evolution->isConfigured()) {
            $connected = $this->evolution->isConnected($instanceName);

            if (! $connected) {
                $qr = $this->evolution->getQrCode($instanceName);
            }

            if ($settings->evolution_connected !== $connected) {
                $settings->update(['evolution_connected' => $connected]);
            }
        }

        return view('admin.whatsapp.index', compact('settings', 'connected', 'qr', 'instanceName'));
    }

    public function status(): JsonResponse
    {
        $instanceName = tenant('id');
        $connected = $this->evolution->isConnected($instanceName);

        $settings = CompanySetting::get();
        if ($settings->evolution_connected !== $connected) {
            $settings->update(['evolution_connected' => $connected]);
        }

        $qr = null;
        if (! $connected) {
            $qr = $this->evolution->getQrCode($instanceName);
        }

        return response()->json([
            'connected' => $connected,
            'qr' => $qr,
        ]);
    }

    public function disconnect(): RedirectResponse
    {
        $instanceName = tenant('id');
        $this->evolution->logout($instanceName);

        CompanySetting::get()->update(['evolution_connected' => false]);

        return back()->with('success', 'WhatsApp desconectado correctamente.');
    }
}
