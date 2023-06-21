<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostService
{

    public static function getAllPosts()
    {
        return Post::orderBy('id', 'DESC')->get();
    }

    public static function getUserPosts()
    {
        return Post::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
    }

    public static function addPost($request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $file = $files[0];
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getSize();
            $file_name = time() . "." . $file_ext;
            $rawData = file_get_contents($file);
            $file_data = base64_encode($rawData);
            $validatedData['file_size'] = $file_size;
            $validatedData['file_name'] = $file_name;
            $validatedData['file_ext'] = $file_ext;
            $validatedData['file_data'] = $file_data;
        }
        return Post::create($validatedData);
    }

    public static function getPostDetails($postId)
    {
        $post = Post::with('comments')->where(
            [
                ['id', $postId],
                ['user_id', auth()->user()->id],
            ]
        )->first();

        // if (isset($post->comments)) {
        //     foreach ($post->comments as $comment) {
        //         $comment->username = $comment->user->name;
        //     }
        //     $post->commentsCount = count($post->comments);
        // }

        return $post;
    }

    public static function updatePost($request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
        $post = Post::findOrfail($request->id);
        $file_size = $post->file_size;
        $file_name = $post->file_name;
        $file_ext = $post->file_ext;
        $file_data = $post->file_data;
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $file = $files[0];
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getSize();
            $file_name = time() . "." . $file_ext;
            $rawData = file_get_contents($file);
            $file_data = base64_encode($rawData);
        }
        DB::table('posts')
            ->where([
                ['id', $request->id],
                ['user_id', auth()->user()->id],
            ])
            ->update([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->user()->id,
                'file_size' => $file_size,
                'file_name' => $file_name,
                'file_ext' => $file_ext,
                'file_data' => $file_data,
            ]);

        $post = Post::findOrfail($request->id);
        return $post;
    }

    public static function deletePost($postId)
    {
        $post = Post::where([
            ['id', $postId],
            ['user_id', auth()->user()->id],
        ])->first();

        return ($post->delete()) ? true : false;
    }

    public static function addComment($request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
        return Comment::create($validatedData);
    }

    public static function deleteComment($commentId)
    {
        $comment = Comment::where([
            ['id', $commentId],
            ['user_id', auth()->user()->id],
        ])->first();
        return ($comment->delete()) ? true : false;
    }

    public static function updatePostComments($postId)
    {
        $post = Post::find($postId);
        $post->comments_count += 1;
        $post->save();
    }
}
