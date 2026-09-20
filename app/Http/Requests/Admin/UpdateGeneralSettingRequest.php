<?php

namespace App\Http\Requests\Admin;

use App\Enums\WebAppStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateGeneralSettingRequest extends FormRequest
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
            'web_app_status' => ['required', new Enum(WebAppStatus::class)],
            'domain_name' => ['nullable', 'string', 'max:255'],
            'copyright_by' => ['nullable', 'string', 'max:255'],
            // Free text, not a bare integer — supports a range like "2020 - 2026".
            'copyright_year' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'max:2048'],
            // Favicons are commonly .ico, which Laravel's `image` rule doesn't cover.
            'favicon' => ['nullable', 'mimes:ico,png,jpg,jpeg,svg', 'max:2048'],
        ];
    }
}
