<?php

namespace App\Http\Controllers;

use App\Filter\PostFilter;
use App\Models\Post;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StorePostRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Services\PostServices\storePostService;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        //store post logic here...
        return (new storePostService())->store($request);
    }
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'posts' => $posts
        ]);
    }
    public function getApproved()
    {
        // return "erfere";
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters((new PostFilter)->filter())
            ->where('status', 'approved')
            ->with('worker:id,name')
            ->get(['id', 'content', 'price', 'worker_id']);
        // $posts = Post::where('status', 'approved')->with('worker:id,name')->get();
        return response()->json([
            'posts' => $posts
        ]);
    }
    public function getPending()
    {
        $posts = Post::where('status', 'pending')->get();
        return response()->json([
            'posts' => $posts
        ]);
    }
    public function getPost($id)
    {
        $post = Post::find($id);
        return response()->json([
            'post' => $post
        ]);
    }
}
