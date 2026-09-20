<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateEmailSettingRequest;
use App\Http\Requests\Admin\UpdateGeneralSettingRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class SystemSettingController extends Controller
{
    #[Authorize('view', Setting::class)]
    public function edit(): View
    {
        return view('admin.system-setting.edit', [
            'setting' => Setting::current(),
        ]);
    }

    #[Authorize('update', Setting::class)]
    public function updateGeneral(UpdateGeneralSettingRequest $request): RedirectResponse
    {
        $setting = Setting::current();

        $setting->update($request->safe()->only([
            'web_app_status', 'domain_name', 'copyright_by', 'copyright_year',
        ]));

        if ($request->hasFile('logo')) {
            $setting->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        if ($request->hasFile('favicon')) {
            $setting->addMediaFromRequest('favicon')->toMediaCollection('favicon');
        }

        return back()->with('status', 'setting-general-updated');
    }

    #[Authorize('update', Setting::class)]
    public function updateEmail(UpdateEmailSettingRequest $request): RedirectResponse
    {
        $setting = Setting::current();

        $data = $request->safe()->only([
            'enquiry_email', 'outgoing_mail_server', 'smtp_port', 'reply_email',
        ]);

        // Blank means "keep the current password" — a password field never
        // re-populates, so an empty submission isn't the admin clearing it.
        if ($request->filled('reply_email_password')) {
            $data['reply_email_password'] = $request->string('reply_email_password')->value();
        }

        $setting->update($data);

        return back()->with('status', 'setting-email-updated');
    }
}
