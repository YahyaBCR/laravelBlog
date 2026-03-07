<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;


class CommentController extends Controller
{
    public function index(Post $post){
        $comments = $post->comments()->with('user')->latest()->get();
        return response()->json($comments, 200);
    }

    public function store(StoreCommentRequest $request, Post $post){
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);
        return response()->json([
            'message' => 'comment ajouté',
            'comment' => $comment->load('user'),
        ],200);
    }
    public function destroy(Comment $comment){
        if($comment->user_id !== Auth::id()){
            return response()->json([
                'message' => 'non autorisé',
            ], 403);
        }
        $comment->delete();
        return response()->json([
            'message'=>'comment supprimé',
        ],200);
    }
}
