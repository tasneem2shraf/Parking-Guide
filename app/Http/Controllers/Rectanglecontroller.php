<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camera;
use App\Models\Rectangle;
use Illuminate\Support\Facades\Validator;
use App\HelperMethods\JsonReturn;

class RectangleController extends Controller
{
    use JsonReturn;

    public function index()
    {
        return $this->dataJson(Rectangle::all());
    }


    public function store(Request $request)
    {
         $validator = request()->validate( [
             'x1' => 'required|int',
             'y1' => 'required|int',
             'x2' => 'required|int',
             'y2' => 'required|int',
             'position' => 'required|int',
             'is_available' => 'sometimes|boolean',
             'camera_id' => 'required',
         ]);

         $rectangles = Rectangle::create($validator);
         return $this->dataJson($rectangles->toArray(), 'Rectangle created succesfully');
     }


    public function show($id)
    {
        $camera = Camera::find($id);
        if (is_null($camera)) {
            return $this->errorJson($camera, 404, 'Camera not found !');
        }
        return $this->dataJson($camera->rectangles->toArray(), 'Rectangles show succesfully');
    }


    public function destroy($id)
    {
         Rectangle::find($id)->delete();
    }
}
