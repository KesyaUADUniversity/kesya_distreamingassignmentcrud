<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MovieCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index()
    {
        $categories = MovieCategory::all();
        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil',
            'data' => $categories
        ]);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = MovieCategory::with('movies')->find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail kategori berhasil diambil',
            'data' => $category
        ]);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:movie_category,name',
            'description' => 'nullable|string',
        ]);

        $category = MovieCategory::create($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $category = MovieCategory::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:movie_category,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $category = MovieCategory::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Opsional: cek apakah ada film yang terkait
        if ($category->movies()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus kategori yang masih digunakan oleh film'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}