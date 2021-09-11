<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostCommentController extends Controller
{
    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }//end __construct()

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
                'name'     => 'required|string|between:2,100',
                'email'    => 'required|email',
                'comment'     => 'required|string',
                'post_id'    => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $PostComment  = new PostComment();
        $PostComment->name = $request->name;
        $PostComment->email = $request->email;
        $PostComment->comment = $request->comment;
        $PostComment->post_id = $request->post_id;
        $PostComment->approved = 1;

        if ($PostComment->save()) {
            return response()->json(
                [
                    'status' => true,
                    'PostComment'   => $PostComment,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the comment could not be saved.',
                ]
            );
        }

    }//end store()


    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string|between:2,100',
                'email'    => 'required|email',
                'comment'     => 'required|string',
                'id'    => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }
        $PostComment = PostComment::find($request->id);
        $PostComment->name = $request->name;
        $PostComment->email = $request->email;
        $PostComment->comment = $request->comment;

        if ($PostComment->save()) {
            return response()->json(
                [
                    'status' => true,
                    'PostComment'   => $PostComment,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the comment could not be updated.',
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

        $PostComment = PostComment::find($request->id);
        if ($PostComment->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'PostComment'   => "PostComment deleted",
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the PostComment could not be deleted.',
                ]
            );
        }

    }//end destroy()

    protected function guard()
    {
        return Auth::guard();

    }//end guard()
}
