<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Floor;
use App\Models\Camera;

class CameraController extends Controller
{
    
    protected $user;

    public function store(Request $request)
    {
        $input = $request->all();
        $validated = $request->validate([            
            'image' => 'required',
            'title' => 'required',
            'floor_id' => 'required'
        ]);

        //save images/camera in database 
        $file_name=  $this -> save_image( $request->image ,'images/camera');
        $Camera = Camera::create
        ([
            'image' => $file_name,
            'title' => $request->title,
            'floor_id' =>   $validated['floor_id']
            
        ]);

        if($Camera) {
            return response()->json([
                
                'success' => true,
                'Camera' => $Camera  
                
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, camera could not be added'
            ], 500);
        }


        
    } 

    public function show($id)
    {
        return Camera::find($id);
    }
    

    public function destroy( $id)
    {
        try {  $camera = Camera::find($id);
            
          $camera  -> delete();
          return response() -> json(['status' => 'success camera data deleted ']);
         }
         catch (\Throwable $e) {
             return response() -> json(['status' => ' can not be found']);
         }          
    }


   
    // save camera image in folder
    public function save_image ( $image, $folder)
    {
        $file_extension =  $image ->getClientOriginalExtension();
        $file_name=  $folder .'/'.time().'.'.$file_extension;
        $path= $folder;
        $image -> move($path, $file_name);
        return $file_name;

    }



}
