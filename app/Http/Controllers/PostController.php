<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function index(){
        $post = Post::with('user')->latest()->get();
        return response()->json($post, 200);
    }
    public function store(StorePostRequest $request){
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);
        return response()->json([
            'message' => 'post created',
            'post' => $post,
        ], 201);
    }
    public function show(Post $post){
        return response()->json($post->load('user'), 200);
    }
    public function update(UpdatePostRequest $request, Post $post){
        if($post->user_id !== Auth::id()){
            return response()->json([
                'message' => 'non autorisé',
            ], 403);
        }
        $post->update($request->only(['title', 'content']));
        return response()->json([
            'message'=>'update success',
            'post'=>$post,
        ],200);
    }
    public function destroy(Post $post){
        if($post->user_id !== Auth::id()){
            return response()->json([
                'message' => 'non autorisé&',
            ], 403);
        }
        $post->delete();
        return response()->json([
            'message' => 'post deleted',
        ], 200);
    }
}
