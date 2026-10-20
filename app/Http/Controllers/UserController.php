<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        $user = new User();

        return view('users.index', compact('users', 'user'));
    }

    public function create()
    {
        return view('users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,kasir',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('ok', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => 'required|in:admin,kasir',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Admin tidak boleh menurunkan role dirinya sendiri
        if ($user->id === auth()->id()) {
            $data['role'] = 'admin';
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('ok', 'Data pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('ok', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($user->transactions()->exists()) {
            return back()->with('ok', 'Akun ini punya riwayat transaksi, jadi tidak bisa dihapus.');
        }

        $user->delete();
        return back()->with('ok', 'Pengguna dihapus.');
    }
}