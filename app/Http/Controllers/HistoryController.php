<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Garage;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    use JsonReturn;

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'car_number' => 'required',
            'parking_time' => 'required',
            'garage_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorJson($validator->errors(), 400, 'Error Validation');
        }
        $histories = History::create($input);
        return $this->dataJson($histories->toArray(), 'History created succesfully');
    }

    public function show($id)
    {
        $garage = Garage::find($id);
        if (is_null($garage)) {
            return $this->errorJson($garage, 404, 'Garage not found !');
        }
        //$garage->histories;
        return $this->dataJson($garage->histories->toArray(), 'History show succesfully');
    }
}
