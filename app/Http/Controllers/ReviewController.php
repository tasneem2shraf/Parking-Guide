<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Garage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use JsonReturn;
    protected $user;

    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'garage_id' => 'required',
            'review' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return $this->errorJson($validator->errors(), 400, 'Error Validation');
        }
        $user->garage_reviews()->attach($request->garage_id, ['review' => $request->review]);
        return $this->dataJson($input, 'Review created succesfully');
    }

    public function show($id)
    {
        $garage = Garage::find($id);
        if (is_null($garage)) {
            return $this->errorJson($garage, 404, 'Garage not found !');
        }
        // $garage->user_reviews;
        return $this->dataJson($garage->user_reviews->toArray(), 'Review show succesfully');
    }
}
