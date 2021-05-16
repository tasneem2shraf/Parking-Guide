<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Garage;
use App\Models\History;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    use JsonReturn;

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = request()->validate( [
            'car_number' => 'required',
            'garage_id' => 'required',
        ]);

        $histories = History::create(array_merge($validator, ['parking_time' => date("Y-m-d H:i")]));
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
