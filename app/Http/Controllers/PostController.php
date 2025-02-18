<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query();     
        if (request()->ajax()) {
            return DataTables::of($posts)
                ->addIndexColumn() // Adds an index column (if needed)
                ->addColumn('actions', function ($row) {
                    return '
                        <a href="' . route('posts.edit', $row->id) . '" class="btn edit-btn" id="edit-' . $row->id . '">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="' . route('posts.destroy', $row->id) . '" method="POST" class="inline-block">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn delete-btn" id="delete-' . $row->id . '">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';
                })
                ->addColumn('published_at', function ($row) {
                    return '<p class="published-date" id="published-' . $row->id . '">' . Carbon::parse($row->published_at)->format('M d, Y') . '</p>';
                })
                ->addColumn('description', function ($row) {
                    $description = Str::limit($row->description, 100);
                    return '<div class="post-description" id="description-' . $row->id . '">' . nl2br(e($description)) . '..</div>';
                })
                ->addColumn('slug', function ($row) {
                    return '<p class="post-slug" id="slug-' . $row->id . '">' . $row->slug . '</p>';
                })
                ->orderColumn('published_at', function ($query, $order) {
                    $query->orderBy('published_at', $order);
                })
                ->rawColumns(['actions', 'description', 'slug', 'published_at'])
                ->make(true);
        }
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'published_at' => now(),
            'slug' =>  Str::uuid(),

        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'published_at' => 'required|date',
        ]);
        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'published_at' => $validated['published_at'],
        ]);
        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|',
        ]);
    
        if ($request->hasFile('upload')) {
            // Get file info
            $file = $request->file('upload');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
    
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            $file->move(public_path('temp'), $fileName);
    
            $url = asset('temp/' . $fileName);
    
            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url,
            ]);
        }
    
        return response()->json(['error' => 'No file uploaded or file is invalid'], 400);
    }
    
}
