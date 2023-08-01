<?php

namespace App\Services\PostServices;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\PostAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class storePostService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Post();
    }

    public function adminPercent($price)
    {
        $priceAfterDiscount = $price - ($price * 0.05);
        return $priceAfterDiscount;
    }

    public function storePost($request)
    {
        $post = $this->model;
        $post->content = $request->content;
        $post->price = $this->adminPercent($request->price);

        $post->worker_id  = auth()->guard('workers')->id();
        $post->save();
        return $post;
    }

    public function storePostPhotos($request, $postId)
    {

        foreach ($request->file('photos') as $photo) {

            $fileName = 'Post-' . uniqid() . "." . $photo->getClientOriginalExtension();
            $photo->storeAs("Posts", $fileName, "Media");

            $postPhoto = new PostPhoto();
            $postPhoto->post_id = $postId;
            $postPhoto->photo = $fileName;
            $postPhoto->save();
        }
    }

    public function sendAdminNotification($post)
    {
        $admins = Admin::get();
        $worker = auth()->guard('workers')->user();
        Notification::send($admins, new PostAdmin($worker, $post));
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            // Store post data in database table posts and get the id of inserted row.
            $post = $this->storePost($request);

            // store post photos
            if ($request->hasFile('photos')) {
                $this->storePostPhotos($request, $post->id);
            }

            // Send notification to admin for approval
            $this->sendAdminNotification($post);


            DB::commit();
            return response()->json([
                'message' => "Post created Successfully",
                'price after discount' => "{$post->price}"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
