<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function create(Request $request){
        $comment = new Comment;
        $comment->user_id = JWTAuth::user()->id;
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'success' => true,
            'user' => JWTAuth::user(),
            'comment' => $comment
        ]);
    }

    public function update(Request $request){
        $comment = Comment::find($request->id);
        if($comment->user_id != JWTAuth::user()->id){
            return response()->json([
                'success' => false,
                'comment' => 'unauthorized action'
            ]);
        }
        $comment->comment = $request->comment;
        $comment->update();
        return response()->json([
            'success' => true,
            'comment' => 'comment edited'
        ]);
    }

    public function delete(Request $request){
        $comment = Comment::find($request->id);
        if($comment->user_id != JWTAuth::user()->id){
            return response()->json([
                'success' => false,
                'comment' => 'unauthorized action'
            ]);
        }
        $comment->delete();
        return response()->json([
            'success' => true,
            'comment' => 'comment deleted'
        ]);
    }

    public function comments(Request $request){
        $comments = Comment::where('post_id', $request->post_id)->orderBy('id', 'desc')->get();
        foreach($comments as $comment){
            $comment->user;
        }
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
