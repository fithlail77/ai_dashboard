<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => trim((string) $data['name']),
                'email' => strtolower(trim((string) $data['email'])),
                'password' => (string) $data['password'],
                'role' => (string) ($data['role'] ?? 'viewer'),
                'phone' => ! empty($data['phone']) ? trim((string) $data['phone']) : null,
                'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $payload = [
                'name' => trim((string) $data['name']),
                'email' => strtolower(trim((string) $data['email'])),
                'role' => (string) ($data['role'] ?? $user->role),
                'phone' => ! empty($data['phone']) ? trim((string) $data['phone']) : null,
                'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : false,
            ];

            if (! empty($data['password'])) {
                $payload['password'] = (string) $data['password'];
            }

            $user->update($payload);

            return $user->fresh();
        });
    }

    /**
     * Update single field for inline table editing.
     */
    public function updateField(User $user, string $field, mixed $value): User
    {
        return DB::transaction(function () use ($user, $field, $value) {
            $user->update([$field => $value]);

            return $user->fresh();
        });
    }

    public function delete(User $user, User $currentUser): bool
    {
        if ($user->id === $currentUser->id) {
            throw new \DomainException('Tidak dapat menghapus akun Anda sendiri.');
        }

        return DB::transaction(function () use ($user) {
            $user->tokens()->delete();

            return (bool) $user->delete();
        });
    }
}
