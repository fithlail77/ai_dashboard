<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InlineUpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User $targetUser */
        $targetUser = $this->route('user');
        $userId = $targetUser instanceof User ? $targetUser->id : $targetUser;

        return [
            'field' => ['required', 'string', 'in:name,email,phone,role,is_active'],
            'value' => match ($this->input('field')) {
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
                'phone' => ['nullable', 'string', 'max:20'],
                'role' => ['required', 'string', 'in:admin,operator,viewer'],
                'is_active' => ['required', 'in:0,1,true,false'],
                default => ['required'],
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'value.required' => 'Nilai field tidak boleh kosong.',
            'value.email' => 'Format email tidak valid.',
            'value.unique' => 'Email sudah digunakan pengguna lain.',
            'value.in' => 'Pilihan data tidak valid.',
        ];
    }
}
