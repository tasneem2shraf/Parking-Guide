<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Floorhistory;
use Illuminate\Support\Facades\Validator;
use App\HelperMethods\JsonReturn;

class Floor_Historycontroller extends Controller
{
    use JsonReturn;

    public function index()
    {
        return $this->dataJson(Floorhistory::all());
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $validator = request()->validate( [
            'num_cars' => 'required',
            'floor_id' => 'required',
        ]);

        $histories = Floorhistory::create(array_merge($validator, ['parking_time' => date("Y-m-d H:i")]));
        return $this->dataJson($histories->toArray(), 'Floor_History created succesfully');
    }


    public function show($id)
    {
        $floor = Floor::find($id);
        if (is_null($floor)) {
            return $this->errorJson($floor, 404, 'Floor not found !');
        }
        return $this->dataJson($floor->floor_histories->toArray(), 'Floor_History show succesfully');
    }

}
