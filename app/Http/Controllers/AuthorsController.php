<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorsRequest;
use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthorsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $search = $request->input('search'); // The searchQueryitem object

        $query = Authors::with(['user:id,name'])
            ->where('name', 'LIKE', '%' . $q . '%')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();


        return Inertia::render('Authors/Index', [
            'data' => $query // Ensure 'data' key matches exactly what is expected in Vue
        ]);
    }
    public function store(AuthorsRequest $request)
    {
        $data = $request->validated();
        Auth::user()->authors()->create($data);

        return redirect()->back()->with([
            'message' => 'successfully'
        ]);
    }
    public function show($id)
    {
        $query = Authors::findorfail($id);
        if (!$query) {
            return redirect()->back()->with(['message' => 'نووسەرەکە نەدۆزراییەوە'], 404);
        }
        return redirect()->back()->with([
            'data' => $query // Ensure 'data' key matches exactly what is expected in Vue
        ]);
    }
    public function update(AuthorsRequest $request, $id)
    {
        $data = $request->validated();
        $Item = Authors::find($id);
        $Item->update($data);
        return redirect()->back()->with([
            'message' => 'نوێکرایەوە',
        ]);
    }

    public function destroy($id)
    {
        $Item = Authors::findOrFail($id);
        $Item->delete(); // Soft delete the user
        return redirect()->back()->with(['message' => 'سڕایەوە']);
    }
}
