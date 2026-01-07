<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminApprovalController extends Controller
{
    // Tampilkan daftar user yang PENDING
    public function index()
    {
        // Ambil user yang is_active = 0 (false)
        $pendingUsers = User::where('is_active', false)->orderBy('created_at', 'desc')->get();

        return view('admin.approvals', compact('pendingUsers'));
    }

    // Aksi Menyetujui (Approve)
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return back()->with('success', "User {$user->name} berhasil disetujui dan sekarang bisa login.");
    }

    // Aksi Menolak (Hapus User)
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', "Pendaftaran user {$user->name} ditolak dan dihapus.");
    }
}
