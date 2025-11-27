<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // 1. Daftar User
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('users.create');
    }

    // 3. Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    // 4. Form Edit
    public function edit(User $user)
    {
        // PROTEKSI: Cek apakah user yang login adalah pemilik akun ini
        if ($user->id !== Auth::id()) {
            return redirect()->route('users.index')->withErrors(['msg' => 'Akses Ditolak! Anda hanya bisa mengedit profil sendiri.']);
        }

        return view('users.edit', compact('user'));
    }

    // 5. Update User
    public function update(Request $request, User $user)
    {
        // Proteksi (Hanya admin/diri sendiri yang boleh edit)
        if ($user->id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi Gambar Max 2MB
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // 1. Cek apakah ada file foto yang diupload?
        if ($request->hasFile('avatar')) {

            // Hapus foto lama jika ada (biar gak nyampah)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder 'avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // 2. Cek apakah password diganti?
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Profil berhasil diperbarui!');
    }

    // 6. Hapus User
    public function destroy(User $user)
    {
        // Biasanya User tidak boleh menghapus dirinya sendiri sembarangan,
        // Tapi jika idenya "User tidak boleh hapus ORANG LAIN", kodenya begini:

        if ($user->id !== Auth::id()) {
            return back()->withErrors(['msg' => 'Anda tidak berhak menghapus user lain!']);
        }

        // Tapi biasanya di aplikasi, user tidak bisa hapus akun sendiri lewat menu index,
        // jadi kita bisa matikan fungsi destroy atau biarkan untuk admin saja nanti.
        // Untuk sekarang, kita return error saja biar aman.
        return back()->withErrors(['msg' => 'Penghapusan akun dinonaktifkan demi keamanan.']);
    }
}
