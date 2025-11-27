<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil semua kategori pengeluaran
        $categories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->get();

        // 2. Siapkan data laporan budget
        $budgetReport = $categories->map(function ($category) use ($userId) {
            // Ambil budget yang sudah diset (kalau ada)
            $budget = Budget::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->first();

            // Hitung pengeluaran bulan ini untuk kategori tersebut
            $spent = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->sum('amount');

            return (object) [
                'category' => $category,
                'limit' => $budget ? $budget->amount : 0, // Jika belum set, anggap 0
                'spent' => $spent,
                'percentage' => ($budget && $budget->amount > 0) ? ($spent / $budget->amount) * 100 : 0
            ];
        });

        return view('budgets.index', compact('budgetReport'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0'
        ]);

        // Pakai updateOrCreate: Jika sudah ada diupdate, jika belum dibuat baru
        Budget::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'category_id' => $request->category_id
            ],
            [
                'amount' => $request->amount
            ]
        );

        return back()->with('success', 'Anggaran berhasil diperbarui!');
    }
}