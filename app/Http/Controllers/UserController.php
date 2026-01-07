<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->create($request->validated());

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'data' => $request->except('password'),
                'message' => $e->getMessage()
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
}
