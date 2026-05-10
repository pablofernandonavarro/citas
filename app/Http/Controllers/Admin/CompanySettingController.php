<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanySettingRequest;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function edit()
    {
        Gate::authorize('manage_company_settings');

        $settings = CompanySetting::get();

        return view('admin.company-settings.edit', compact('settings'));
    }

    public function update(UpdateCompanySettingRequest $request)
    {
        $settings = CompanySetting::get();

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image_path) {
                Storage::disk('public')->delete($settings->hero_image_path);
            }
            $data['hero_image_path'] = $request->file('hero_image')->store('hero-images', 'public');
        }

        unset($data['logo'], $data['hero_image']);

        $settings->update($data);

        return redirect()->route('admin.company-settings.edit')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
