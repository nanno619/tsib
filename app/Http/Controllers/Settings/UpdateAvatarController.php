<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateAvatarController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'max:2048'],
        ])->validateWithBag('avatar');

        $user = $request->user();

        // Unreachable behind the auth middleware, but user() is typed nullable.
        if (! $user instanceof User) {
            abort(403);
        }

        $user->addMediaFromRequest('avatar')
            ->toMediaCollection('avatar');

        return back()->with('status', 'avatar-updated');
    }
}
