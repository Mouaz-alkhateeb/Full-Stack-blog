<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Http\Requests\CommentRequest;
use App\Models\Post;
use App\Notifications\PostCommentNotification;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(CommentRequest $request)
    {
        $comment = PostService::addComment($request);


        ///////
        $user = Auth::user();
        $post = Post::findOrFail($comment->post_id);
        $notifier = $post->user;

        event(new AddCommentEvent($notifier, $post, $user));


        return response()->json(['data' => $comment, 'message' => 'Comment added Successfully.']);
    }

    public function destroy(Request $request)
    {
        $isDeleted = PostService::deleteComment($request->id);
        if ($isDeleted) {
            return response()->json(['message' => 'Comment deleted Successfully ']);
        } else {
            return response()->json(['message' => 'Operation failed!']);
        }
    }
}
