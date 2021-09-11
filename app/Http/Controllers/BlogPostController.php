<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class BlogPostController extends Controller
{
    //
    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }//end __construct()

    public function list(Request $request)
    {
        $filter = array();
        if($request->input('name')){
            $filter['name']=$request->input('name');
        }
        if($request->input('email')){
            $filter['email']=$request->input('email');
        }
        if($request->input('mobile_number')){
            $filter['mobile_number']=$request->input('mobile_number');
        }
        if($request->input('created_at')){
            $filter['blog_posts.created_at']=$request->input('created_at');
        }

        $filtered = BlogPost::leftJoin('users', function($join)
        {
            $join->on('users.id', '=', 'blog_posts.user_id');
        })
        ->where($filter)
        ->get('blog_posts.*');
        $BlogPost = collect($filtered)
        ->map(function ($item){
            $item->user = User::find($item->user_id);
            $item->comments = PostComment::where('post_id', $item->id)->get();
            return $item;
        });


        return response()->json($BlogPost->toArray());

    }//end list()


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'     => 'required|string',
                'body'      => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $BlogPost            = new BlogPost();
        $BlogPost->title     = $request->title;
        $BlogPost->body      = $request->body;

        if ($this->user->blogposts()->save($BlogPost)) {
            return response()->json(
                [
                    'status' => true,
                    'BlogPost'   => $BlogPost,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the BlogPost could not be saved.',
                ]
            );
        }

    }//end store()

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'     => 'required|string',
                'body'      => 'required|string',
                'id'      => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }
        $BlogPost = BlogPost::find($request->id);
        $BlogPost->title     = $request->title;
        $BlogPost->body      = $request->body;

        if ($BlogPost->save()) {
            return response()->json(
                [
                    'status' => true,
                    'BlogPost'   => $BlogPost,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the BlogPost could not be updated.',
                ]
            );
        }

    }//end update()


      /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id'      => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $BlogPost = BlogPost::find($request->id);
        if ($BlogPost->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'BlogPost'   => "BlogPost deleted",
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the BlogPost could not be deleted.',
                ]
            );
        }

    }//end destroy()

    protected function guard()
    {
        return Auth::guard();

    }//end guard()




}
