<x-layouts.app title="System Setting">
    <x-slot:header>
        <x-page-header title="System Setting" :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pentadbiran'],
            ['label' => 'System Setting'],
        ]" />
    </x-slot:header>

    @if (session('status') === 'setting-general-updated')
        <x-alert type="success" class="mb-3">Tetapan am telah dikemas kini.</x-alert>
    @endif

    @if (session('status') === 'setting-email-updated')
        <x-alert type="success" class="mb-3">Tetapan e-mel telah dikemas kini.</x-alert>
    @endif

    <div class="card">
        <div class="card-header">
            <x-tabs card-header selected="general" :items="[
                ['label' => 'General', 'value' => 'general', 'target' => '#tab-general'],
                ['label' => 'Email', 'value' => 'email', 'target' => '#tab-email'],
            ]" />
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-general">
                    <form method="POST" action="{{ route('admin.system-setting.update-general') }}"
                          enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label required">Web App Status</label>
                            <div>
                                <x-radio name="web_app_status" value="online" inline
                                         :checked="old('web_app_status', $setting->web_app_status->value) === 'online'">Online</x-radio>
                                <x-radio name="web_app_status" value="under_maintenance" inline
                                         :checked="old('web_app_status', $setting->web_app_status->value) === 'under_maintenance'">Under Maintenance</x-radio>
                            </div>
                            @error('web_app_status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-input name="domain_name" label="Domain Name" :value="$setting->domain_name" placeholder="example.com" />

                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            @if ($setting->getFirstMediaUrl('logo'))
                                <div class="mb-2">
                                    <img src="{{ $setting->getFirstMediaUrl('logo') }}" alt="Logo semasa" style="max-height: 48px;" />
                                </div>
                            @endif
                            <x-input type="file" name="logo" accept="image/*" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Favicon</label>
                            @if ($setting->getFirstMediaUrl('favicon'))
                                <div class="mb-2">
                                    <img src="{{ $setting->getFirstMediaUrl('favicon') }}" alt="Favicon semasa" style="max-height: 32px;" />
                                </div>
                            @endif
                            <x-input type="file" name="favicon" accept="image/*,.ico" />
                        </div>

                        <x-input name="copyright_by" label="Copyright By" :value="$setting->copyright_by" />
                        <x-input name="copyright_year" label="Copyright Year" :value="$setting->copyright_year" placeholder="2026" class="mb-0" />

                        <div class="mt-3 d-flex justify-content-end">
                            <x-button type="submit" color="primary" icon="device-floppy">Simpan</x-button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="tab-email">
                    <form method="POST" action="{{ route('admin.system-setting.update-email') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <x-input type="email" name="enquiry_email" label="Enquiry Email" :value="$setting->enquiry_email" />
                        <x-input name="outgoing_mail_server" label="Outgoing Mail Server" :value="$setting->outgoing_mail_server" placeholder="smtp.example.com" />
                        <x-input type="number" name="smtp_port" label="SMTP Port" :value="$setting->smtp_port" placeholder="587" />
                        <x-input type="email" name="reply_email" label="Reply Email" :value="$setting->reply_email" />
                        <x-input type="password" name="reply_email_password" label="Reply Email Password"
                                 autocomplete="new-password" help="Biarkan kosong untuk kekalkan kata laluan semasa."
                                 class="mb-0" />

                        <div class="mt-3 d-flex justify-content-end">
                            <x-button type="submit" color="primary" icon="device-floppy">Simpan</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
