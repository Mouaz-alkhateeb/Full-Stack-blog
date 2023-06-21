<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['allPosts']]);
    }

    // get all posts to display to guests
    public function allPosts()
    {
        $posts = PostService::getAllPosts();
        return response()->json(['data' => $posts, 'message' => 'Posts retreived Successfully.']);
    }

    // get posts of the current user
    public function userPosts()
    {
        $posts = PostService::getUserPosts();
        return response()->json(['data' => $posts, 'message' => 'Posts retreived Successfully.']);
    }

    public function store(PostRequest $request)
    {
        $post = PostService::addPost($request);
        return response()->json(['data' => $post, 'message' => 'Post created Successfully.']);
    }

    // post details with its comments
    public function show(Request $request)
    {
        $post = PostService::getPostDetails($request->id);
        return response()->json(['data' => $post, 'message' => 'Post details retreived Successfully']);
    }

    public function update(PostRequest $request)
    {
        $post = PostService::updatePost($request);
        return response()->json(['data' => $post, 'message' => 'Post updated Successfully ']);
    }

    public function destroy(Request $request)
    {
        $isDeleted = PostService::deletePost($request->id);
        if ($isDeleted) {
            return response()->json(['message' => 'Post deleted Successfully ']);
        } else {
            return response()->json(['message' => 'Operation failed!']);
        }
    }
}