<?php

namespace App\Http\Requests\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * The same password rules registration uses — an admin shouldn't be able to
     * create an account with a password the user couldn't have set themselves.
     */
    use PasswordValidationRules;

    /**
     * Authorization stays on the controller's #[Authorize] attribute rather
     * than here, so every action in this controller is guarded the same way and
     * the rules show up in `route:list`. Returning true is deliberate, not an
     * oversight — the request is only reachable once that check has passed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Loosely typed on purpose: `passwordRules()` returns Fortify's rule set,
     * whose array isn't provably a `list`, so the stricter annotation this file
     * would otherwise carry can't be satisfied.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => $this->passwordRules(),
            // Role *names*, validated against the roles table — syncRoles()
            // would otherwise create a role named after whatever was posted.
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
        ];
    }
}
