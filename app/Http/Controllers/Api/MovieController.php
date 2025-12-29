<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MovieCategory;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // GET /api/movies
    public function index(Request $request)
    {
        $query = Movie::with('category');

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $allowedSorts = ['id', 'title', 'rating', 'release_year'];
        if (in_array($sortBy, $allowedSorts)) {
            $order = in_array($request->get('order'), ['asc', 'desc']) ? $request->get('order') : 'asc';
            $query->orderBy($sortBy, $order);
        }

        $movies = $query->get()->map(function ($movie) {
            $movie->rating_class = $movie->rating_class;
            return $movie;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data film berhasil diambil',
            'data' => $movies
        ]);
    }

    // GET /api/movies/{id}
    public function show($id)
    {
        $movie = Movie::with('category')->find($id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $movie->rating_class = $movie->rating_class;

        return response()->json([
            'success' => true,
            'message' => 'Detail film berhasil diambil',
            'data' => $movie
        ]);
    }

    // POST /api/movies
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|numeric|min:0|max:10',
            'release_year' => 'required|integer|min:1900|max:2030',
            'category_id' => 'required|exists:movie_category,id',
        ]);

        $movie = Movie::create($request->only([
            'title', 'description', 'rating', 'release_year', 'category_id', 'thumbnail'
        ]));

        $movie->rating_class = $movie->rating_class;

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil ditambahkan',
            'data' => $movie
        ], 201);
    }

    // PUT /api/movies/{id}
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|numeric|min:0|max:10',
            'release_year' => 'required|integer|min:1900|max:2030',
            'category_id' => 'required|exists:movie_category,id',
        ]);

        $movie->update($request->only([
            'title', 'description', 'rating', 'release_year', 'category_id', 'thumbnail'
        ]));

        $movie->rating_class = $movie->rating_class;

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil diupdate',
            'data' => $movie
        ]);
    }

    // DELETE /api/movies/{id}
    public function destroy($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $movie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil dihapus'
        ]);
    }
}