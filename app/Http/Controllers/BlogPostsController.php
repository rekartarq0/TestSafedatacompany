<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogPostStoreRequest;
use App\Models\Authors;
use App\Models\BlogPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BlogPostsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $authors = Authors::select('id', 'name')->get();

        $query = BlogPosts::with(['user:id,name', 'author:id,name'])
            ->where('title', 'LIKE', '%' . $q . '%')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();


        return Inertia::render('Posts/Index', [
            'data' => $query, // Ensure 'data' key matches exactly what is expected in Vue
            'filter' => [
                'authors' => $authors
            ]
        ]);
    }
    public function store(BlogPostStoreRequest $request)
    {
        $data = $request->validated();
        Auth::user()->blogposts()->create($data);

        return redirect()->back()->with([
            'message' => 'successfully'
        ]);
    }
    public function show($id)
    {
        $query = BlogPosts::findorfail($id);
        if (!$query) {
            return redirect()->back()->with(['message' => 'نووسەرەکە نەدۆزراییەوە'], 404);
        }
        return redirect()->back()->with([
            'data' => $query // Ensure 'data' key matches exactly what is expected in Vue
        ]);
    }
    public function update(BlogPostStoreRequest $request, $id)
    {
        $data = $request->validated();
        $Item = BlogPosts::find($id);
        $Item->update($data);
        return redirect()->back()->with([
            'message' => 'نوێکرایەوە',
        ]);
    }

    public function destroy($id)
    {
        $Item = BlogPosts::findOrFail($id);
        $Item->delete(); // Soft delete the user
        return redirect()->back()->with(['message' => 'سڕایەوە']);
    }
}
