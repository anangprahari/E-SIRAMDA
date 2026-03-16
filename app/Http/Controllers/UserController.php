<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private UserService $userService
    ) {}

    public function index(Request $request): View
    {
        $users = $this->userRepository->paginateWithSearch(
            $request->input('search')
        );

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        abort_unless($authUser->isSuperUser(), 403);

        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        abort_unless($authUser->isSuperUser(), 403);

        try {
            $this->userService->create($request->validated());

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'data'    => $request->except('password'),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna.')
                ->withInput();
        }
    }

    public function show(int $id): View
    {
        $user = $this->userRepository->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        abort_unless($authUser->isSuperUser(), 403);
        abort_if($authUser->id === $id, 403, 'Tidak dapat mengubah status akun sendiri.');

        try {
            $user = $this->userRepository->toggleStatus($id);
            $statusLabel = $user->isAktif() ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->route('users.index')
                ->with('success', "Akun pengguna berhasil {$statusLabel}.");
        } catch (\Exception $e) {
            Log::error('Error toggling user status', [
                'id'      => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status pengguna.');
        }
    }
}
