<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        if (!$request->filled('username') && !$request->filled('email')) {
            return back()
                ->withErrors([
                    'username' => 'Please enter at least a username or an email.',
                    'email' => 'Please enter at least a username or an email.',
                ])
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'username' => $request->filled('username') ? strtolower($request->username) : null,
            'email' => $request->filled('email') ? strtolower($request->email) : null,
            'password' => Hash::make($request->password),
            'role' => $request->role ?: 'user',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        if (!$request->filled('username') && !$request->filled('email')) {
            return back()
                ->withErrors([
                    'username' => 'Please enter at least a username or an email.',
                    'email' => 'Please enter at least a username or an email.',
                ])
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'username' => $request->filled('username') ? strtolower($request->username) : null,
            'email' => $request->filled('email') ? strtolower($request->email) : null,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}