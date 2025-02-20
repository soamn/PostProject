<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class HistoryController extends Controller
{
    public function index(Request $request, $id)
    {
        $history = Audit::where('auditable_id', $id)->where('event', 'updated');
        if ($request->ajax()) {

            return DataTables::of($history)

                ->editColumn('old_values', function ($row) {
                    $data = $row->old_values;
                    $data = Str::limit($data['description'], 25, '...');
                    return $data;
                })
                ->editColumn("created_at", function ($row) {
                    $last_updated ='<p class="text-sm">' .  $row->created_at->format('d-M-y H:i:s') . '</p>';
                    return $last_updated;
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="/posts/'.$row->id .'/rollback" class="rollback-btn
                      px-3 py-1">
                   <i class="fas fa-undo-alt text-blue-500 hover:text-blue-700"></i> 
                            </a>';
                })
                ->rawColumns(['actions', 'old_values','created_at'])
                ->make(true);
        }

        return view('history.index', compact('id'));
    }




    public function rollback( $auditId)
    {
        $audit = Audit::findOrFail($auditId);
        $data = $audit->old_values;
        $currentPost = Post::find($audit->auditable_id);
        // dd($data);
        $post = (object) [
            "id"            => $audit->auditable_id,
            "title"         => $data['title'] ?? $currentPost->title,
            "slug"         => $currentPost->slug,
            "description"   => $data['description'] ?? $currentPost->description,
            "published_at"  => $data['published_at'] ?? $currentPost->published_at,
        ];

        return view('posts.edit', compact('post'));
    }
}
