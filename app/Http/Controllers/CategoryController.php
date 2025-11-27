<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->orderBy('type')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|in:income,expense'
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type
        ]);

        return back()->with('success', 'Kategori baru berhasil dibuat!');
    }

    public function destroy($id)
    {
        $category = Category::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $category->delete();
        
        return back()->with('success', 'Kategori dihapus.');
    }

    // Fungsi Quick Add Kategori via AJAX
    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|in:income,expense'
        ]);

        $category = \App\Models\Category::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'name' => $request->name,
            'type' => $request->type
        ]);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}