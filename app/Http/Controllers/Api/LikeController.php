<?php

namespace App\Http\Controllers\Api;

use App\Like;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function like(Request $request){
        $like = Like::where('user_id', JWTAuth::user()->id)->where('post_id', $request->post_id)->get();
        if(count($like)>0){
            $like[0]->delete();
            return response()->json([
                'success' => true,
                'message' => 'unliked'
            ]);
        }
        $like = new Like;
        $like->user_id = JWTAuth::user()->id;
        $like->post_id = $request->post_id;
        $like->save();
        return response()->json([
            'success' => true,
            'message' => 'liked'
        ]);
    }
}
