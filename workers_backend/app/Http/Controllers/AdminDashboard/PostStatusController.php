<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostStatusRequest;
use App\Models\Post;
use App\Notifications\PostAdmin;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function changeStatus(PostStatusRequest $request)
    {
        $post = Post::find($request['post_id']);
        $post->status = $request->status;
        $post->rejection_reasons = $request->rejection_reasons ? $request->rejection_reasons : '';
        $post->save();
        Notification::send($post->worker, new PostAdmin($post->worker, $post));
        return response()->json([
            'message' => 'post updated successfully',
            'post' => $post,
        ]);
    }
}
