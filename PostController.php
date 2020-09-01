<?php

namespace App\Http\Controllers\Api;


use App\Post;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function create(Request $request){
        $post = new post;
        $post->user_id = JWTAuth::user()->id;
        $post->desc = $request->desc;

        if($request->photo != ''){
            $photo = time().'.jpg';
            file_put_contents('storage/posts/'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }
        $post->save();
        $post->user;
        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
    }

    public function update(Request $request){
        $post = Post::find($request->id);
        if(JWTAuth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'unauthorized action'
            ]);
        }
        $post->desc = $request->desc;
        $post->update();
        return response()->json([
            'success' => true,
            'message' => 'post edited'
        ]);
    }

    public function delete(Request $request){
        $post = Post::find($request->id);
        if(JWTAuth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'unauthorized action'
            ]);
        }
        if($post->photo != ''){
            Storage::delete('public/posts/'.$post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }

    public function posts(Request $request){
        $search = addslashes($request->search);
        if($request->userOnly != ''){
            $posts = Post::where("user_id", JWTAuth::user()->id)->orderBy("id", "desc")->get();
        } else if($search != ''){
            $posts = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')->where('desc', 'like', "%$search%")
            ->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")
            ->select('posts.id', 'posts.user_id', 'posts.desc','posts.photo', 'posts.created_at', )
            ->orderBy('posts.id', 'desc')->get();
        } else {
            $posts = Post::orderBy('id', 'desc')->get();
        }
       
        foreach ($posts as $post) {
            $user = $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;
            foreach($post->likes as $like){
                if($like->user_id == JWTAuth::user()->id ){
                    $post['selfLike'] = true;
                break;
                }
            }
        }
        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function myPosts(){
        $user = JWTAuth::user();
        $posts = Post::where("user_id", $user->id)->orderBy("id", "desc")->get();
        return response()->json([
            'success' => true,
            'user' => $user,
            'posts' => $posts
        ]);
    }

}
