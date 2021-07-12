<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Floor;
use App\Models\Camera;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use League\Flysystem\AwsS3v3\AwsS3Adapter;



class CameraController extends Controller
{

    use JsonReturn;

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

    public function show_all_floor_cameras($id) {
        $floors =  Camera::where('floor_id', $id)->get();
        if($floors){
            return $this->dataJson($floors);
        }
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
        // $image = file_get_contents($image);
        // // $image -> move($path, $file_name);
        // Storage::put($file_name, $image, 'public');
        // $path = Storage::disk('local')->putFile($folder, new File($image));
        $uploadedObject = app('firebase.storage')->getBucket()->upload(fopen($image, 'r'), ['name' => $file_name]);
        // print($image);
        $url = 'https://firebasestorage.googleapis.com/v0/b/mydatabase-6107e.appspot.com/o/'.urlencode($file_name).'?alt=media';


        // $expiresAt = new \DateTime('tomorrow');
        // $imageReference = app('firebase.storage')->getBucket()->object($file_name);
        // if($imageReference->exists())
        //   $image = $imageReference->signedUrl($expiresAt);

        // $image = $uploadedObject->signedUrl($expiresAt);
        // print($image)
        return $url;

    }



}
