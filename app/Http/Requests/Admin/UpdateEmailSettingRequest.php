<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailSettingRequest extends FormRequest
{
    /**
     * Authorization is the controller's #[Authorize] attribute.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'enquiry_email' => ['nullable', 'email', 'max:255'],
            'outgoing_mail_server' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'between:1,65535'],
            'reply_email' => ['nullable', 'email', 'max:255'],
            // Blank means "keep the current password" — see the controller.
            'reply_email_password' => ['nullable', 'string', 'max:255'],
        ];
    }
}
