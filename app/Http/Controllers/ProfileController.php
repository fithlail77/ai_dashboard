<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('admin.profile', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated) {
            $payload = [
                'name' => trim((string) $validated['name']),
                'email' => strtolower(trim((string) $validated['email'])),
                'phone' => ! empty($validated['phone']) ? trim((string) $validated['phone']) : null,
            ];

            if (! empty($validated['password'])) {
                $payload['password'] = (string) $validated['password'];
            }

            $user->update($payload);
        });

        return redirect()->route('profile.edit')->with('success', 'Profil dan kata sandi berhasil diperbarui.');
    }
}
