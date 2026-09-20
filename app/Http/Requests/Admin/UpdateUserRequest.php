<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
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
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        if (! $user instanceof User) {
            abort(404);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            // Unique, but ignoring this user — otherwise saving the form without
            // touching the email would collide with the user's own row.
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            // Role *names*, validated against the roles table rather than
            // accepted as-is — syncRoles() would otherwise create a role named
            // after whatever the request contained.
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
        ];
    }
}
