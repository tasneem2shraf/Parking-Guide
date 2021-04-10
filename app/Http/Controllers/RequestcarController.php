<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Garage;
use App\Models\Requestcar;
use App\Traits\GeneralTrait;
use JWTAuth;

class RequestcarController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    // now i can see a garage requests not auth 
    //return reqests of a garage using function:requestcars /in model Garage
    public function index($garage_id)
    {   
    return Garage::find($garage_id)->requestcars;  
    }

    // get a request by auth 

    public function show($id)
    {
        
        $request = Requestcar::find($id);
        if ($request->user_id !==  auth()->id()){
            abort(403);
        }
        else {
            return response()->json([
                $request
            ], 200);
        }
       
    }


       public function store(Request $request)
       {
           $validated = $request->validate([ 
              'garage_id' => 'required',
            ]);
              
          // user start request inside a garage, so set the status =10
          $requestcar = Requestcar::create([
              'user_id' => auth()->id(),
              'garage_id' => $validated['garage_id'],
              'time_start' => Carbon::now(),
              'status' => 10,
          ]);
      
  
  
          if($requestcar) {
              return response()->json([
                  'success' => true,
                  'Requestcar' => $requestcar    
              ]);
          } else {
              return response()->json([
                  'success' => false,
                  'message' => 'Sorry, Car could not be added'
              ], 500);
          }
    }


    public function update(Request $request, $id )//comment id
    {
            $requestcar = Requestcar::find($id);
            
            if ($requestcar->user_id !== auth()->id()){
                abort(403);
            } else {
                if ($requestcar -> update()) 
                {
                    $requestcar->status = 20;
                    $requestcar->time_end = Carbon::now();
                    $requestcar->save();
                    return response() -> json(['status' => 'success']);
                } else {
                    return response() -> json(['status' => 'can not be updated']);
                }
            } 
       
    }

    // canceled request : put statues = 30
    public function canceled (Request $request, $id )
    {

        $requestcar = Requestcar::find($id);
            
        if ($requestcar->user_id !== auth()->id()){
            abort(403);
        } else {
            if ($requestcar -> update()) 
            {
                $requestcar->status = 30;
                $requestcar->time_end = Carbon::now();
                $requestcar->save();

                return response() -> json(['status' => 'success']);
            } else {
                return response() -> json(['status' => 'can not be updated']);
            }
        } 


    }


    public function destroy( $id)
    {
     
        $requestcar = Requestcar::find($id);
    
        if ($requestcar->owner_id  !==  auth()->id()){
            abort(403);
        } else {
            if ($requestcar -> delete()) 
            {
                return response() -> json(['status' => 'success']);
            } else {
                return response() -> json(['status' => 'can not be updated']);
            }
        }
    
    
        
    }
}
