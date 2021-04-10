<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Garage;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use JWTAuth;


class ConmmentController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    // now i can see a garage comments not auth
    // using function:comments /in model Garage
    public function index($garage_id)
    { 
    
      return Garage::find($garage_id)->comments;
    }
   
    // get a comment by auth 
    public function show($id)
    {

        $comment = Comment::find($id);
        if ($comment->user_id !==  auth()->id()){
            abort(403);
        }
        else {
            return response()->json([
                $comment
            ], 200);
        }

    }

    public function store(Request $request)
    {
       
        $val = request()->validate([ 
          //  'id' => 'required',//id outo increament 
            'garage_id' => 'required',
            'comment' => 'required',
            
            
        ]);
        $comment = Comment::create(array_merge($val, ['user_id' => $request->user()->id]));
    
        if($comment) {
            return response()->json([
                'success' => true,
                'Comment' => $comment    
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, comment could not be added'
            ], 500);
        }
        }


        public function update(Request $request, $id )//comment id
        {
           
    
                $comment = Comment::find($id );
                
                if ($comment->user_id !==  auth()->id()){
                    abort(403);
                } else {
                    $comment -> comment = $request -> comment;
                
                if ($comment -> update()) 
                {
                    return response() -> json(['status' => 'success']);
                } else {
                    return response() -> json(['status' => 'can not be updated']);
                }
        }  

            
    }

    public function destroy( $id)
    {
     
        $comment = Comment::find($id);
    
        if ($comment->user_id !==  auth()->id()){
            abort(403);
        } else {
            if ($comment -> delete()) 
            {
                return response() -> json(['status' => 'success']);
            } else {
                return response() -> json(['status' => 'can not be updated']);
            }
        }
        
    }
    

    public function comment_of_garage($request)
    {
        return [
            'Comment' => $this->comment,
            
        ];
    }
}
