<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InlineUpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $role = trim((string) $request->input('role', ''));
        $status = $request->input('status');

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== '' && in_array($role, ['admin', 'operator', 'viewer'], true)) {
            $query->where('role', $role);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Inline update for double click on table cell.
     */
    public function inlineUpdate(InlineUpdateUserRequest $request, User $user): JsonResponse
    {
        $field = (string) $request->input('field');
        $rawVal = $request->input('value');

        $value = match ($field) {
            'email' => strtolower(trim((string) $rawVal)),
            'name' => trim((string) $rawVal),
            'phone' => ! empty($rawVal) ? trim((string) $rawVal) : null,
            'is_active' => filter_var($rawVal, FILTER_VALIDATE_BOOLEAN),
            default => $rawVal,
        };

        $updated = $this->userService->updateField($user, $field, $value);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => [
                'id' => $updated->id,
                'field' => $field,
                'value' => $updated->{$field},
                'name' => $updated->name,
                'initials' => strtoupper(substr($updated->name, 0, 2)),
                'role' => $updated->role,
                'is_active' => (bool) $updated->is_active,
            ],
        ]);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        try {
            $this->userService->delete($user, $request->user());

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\DomainException $e) {
            return redirect()->route('admin.users.index')->with('error', $e->getMessage());
        }
    }
}
