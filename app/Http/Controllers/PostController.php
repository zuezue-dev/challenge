<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function list()
    {
        $posts = Post::with('tags')->withCount('likes')->get();

        return response()->json(new PostResourceCollection($posts), Response::HTTP_OK);
    }
    
    public function toggleReaction(Request $request)
    {
        $request->validate([
            'post_id' => 'required|int|exists:posts,id',
            'like'   => 'required|boolean'
        ]);
        
        $post = Post::find($request->post_id);
        if(!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'model not found'
            ]);
        }
        
        if($post->user_id == auth()->id()) {
            return response()->json([
                'status' => 500,
                'message' => 'You cannot like your post'
            ]);
        }
        
        $like = Like::where('post_id', $request->post_id)->where('user_id', auth()->id())->first();
        if($like && $like->post_id == $request->post_id && $request->like) {
            return response()->json([
                'status' => 500,
                'message' => 'You already liked this post'
            ]);
        }elseif($like && $like->post_id == $request->post_id && !$request->like) {
            $like->delete();
            
            return response()->json([
                'status' => 200,
                'message' => 'You unlike this post successfully'
            ]);
        }
        
        Like::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id()
        ]);
        
        return response()->json([
            'status' => 200,
            'message' => 'You like this post successfully'
        ]);
    }
}
