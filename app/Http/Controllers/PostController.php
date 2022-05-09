<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionRequest;
use App\Models\Like;
use App\Models\Post;
use App\Http\Resources\PostResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    /**
     * Get Posts List.
     * 
     * @return JSON
     */
    public function list()
    {
        $posts = Post::with('tags')->withCount('likes')->get();

        return response()->json(new PostResourceCollection($posts), Response::HTTP_OK);
    }
    
    /**
     * Like or Unlike To the requested post
     * 
     * @return JSON
     */
    public function toggleReaction(ReactionRequest $request)
    {
        try {
            $post = Post::findOrFail($request->post_id);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                        'message' => 'model not found'
                    ], Response::HTTP_NOT_FOUND);
        }
        
        if($post->user_id == auth()->id()) {
            return response()->json([
                'message' => 'You cannot like your post'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $like = Like::where('post_id', $request->post_id)->where('user_id', auth()->id())->first();

        switch ([$like, $request->like]) {
            case [true, true]: 
                return response()->json([
                    'message' => 'You already liked this post'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);

            case [true, false]:
                $like->delete();
                return response()->json([
                    'message' => 'You unlike this post successfully'
                ], Response::HTTP_OK);

            case [false, false]: 
                return response()->json([
                    'message' => 'You have not liked this post yet'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);

            default:
                Like::create([
                    'post_id' => $request->post_id,
                    'user_id' => auth()->id()
                ]);
                return response()->json([
                    'message' => 'You like this post successfully'
                ], Response::HTTP_CREATED);
        }  

        
    }
}
