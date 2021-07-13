<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camera;
use App\Models\Rectangle;
use Illuminate\Support\Facades\Validator;
use App\HelperMethods\JsonReturn;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RectanglesController extends Controller
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

        try{
            Camera::findOrFail($validator['camera_id']);
        }catch(ModelNotFoundException $e){
            return $this->errorJson('No model found camera_id');
        }

         $rect = Rectangle::where('camera_id', $validator['camera_id'])->where('position', $validator['position'])->first();
         print($validator['position']);
        //  return;

         if($rect){
            $rect->x1 = $validator['x1'];
            $rect->x2 = $validator['x2'];
            $rect->y1 = $validator['y1'];
            $rect->y2 = $validator['y2'];
            $rect->is_available = $validator['is_available'];
            $rect->save();
            return $this->dataJson($rect->toArray(), 'Rectangle created succesfully');
         }else{
         $rectangles = Rectangle::create($validator);
         return $this->dataJson($rectangles->toArray(), 'Rectangle created succesfully');
        }
     }


    public function show($id)
    {
        $camera = Camera::find($id);
        if (is_null($camera)) {
            return $this->errorJson($camera, 404, 'Camera not found !');
        }
        return $this->dataJson($camera->rectangles->toArray(), 'Rectangles show succesfully');
    }


    public function update(Request $request ,$id)
    {
              $rectangle = Rectangle::find($id);
              $input = $request->all();
              $validator = Validator::make($input, [
                 'x1' => 'required|int',
                 'y1' => 'required|int',
                 'x2' => 'required|int',
                 'y2' => 'required|int',
                 'position' => 'required|int',
                 'is_available' => 'required|boolean',
             ]);
            if ($validator->fails()) {
                return $this->errorJson($validator->errors(), 400, 'Error Validation');
            }
              $rectangle->x1 = $input['x1'];
              $rectangle->y1 = $input['y1'];
              $rectangle->x2 = $input['x2'];
              $rectangle->y2 = $input['y2'];
              $rectangle->position = $input['position'];
              $rectangle->is_available = $input['is_available'];
              $rectangle->save();

        return $this->dataJson($rectangle->toArray(), 'Rectangle updated succesfully');
    }

    public function changeRectanglesAvail(Request $request ,$id)
    {
        try{

            $rectangle = Rectangle::findOrFail($id);
            $input = $request->all();
            $validator = request()->validate([

               'is_available' => 'required|boolean',
           ]);

            $rectangle->is_available = $input['is_available'];
            $rectangle->save();

      return $this->dataJson($rectangle->toArray(), 'Rectangle updated succesfully');
        }catch (ModelNotFoundException $_){
            $this->errorJson('The rectable not founded', 404);
        }

    }

    public function destroy($id)
    {
         Rectangle::find($id)->delete();
    }

}
