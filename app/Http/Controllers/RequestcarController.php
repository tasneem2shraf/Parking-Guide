<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Garage;
use App\Models\Requestcar;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class RequestcarController extends Controller
{
    use JsonReturn;
    protected $user;

    public function __construct()
    {
        $this->middleware('owner');

        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    // now i can see a garage requests /*❌not auth
    //✔ need authenticaiton
    //return reqests of a garage using function:requestcars /in model Garage
    public function index($garage_id)
    {
        try {

            return Garage::findOrFail($garage_id)->requestcars;
        } catch (ModelNotFoundException $_) {
            return $this->errorJson('the request not founded', 404);
        }
    }

    // get a request by auth

    public function show($id)
    {

        try {

            $request = Requestcar::findOrFail($id);
        } catch (ModelNotFoundException $_) {
            return $this->errorJson('the request not founded', 404);
        }

        if ($request->user_id !==  auth()->id()) {
            abort(403);
        } else {
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



        if ($requestcar) {
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


    public function update(Request $request, $id) //comment id
    {
        try {

            $requestcar = Requestcar::findOrFail($id);
        } catch (ModelNotFoundException $_) {
            return $this->errorJson('the request not founded', 404);
        }

        //if allready ended show to the user
        if ($requestcar->status == 20) {
            return $this->dataJson('This is already End');
        } else if ($requestcar->status == 30) { //if cancled ended show to the user
            return $this->dataJson('This cancled request');
        }
        if ($requestcar->user_id !== auth()->id()) {
            abort(403);
        } else {
            if ($requestcar->update()) {
                $requestcar->status = 20;
                $requestcar->time_end = Carbon::now();
                $requestcar->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }
    }

    // canceled request : put statues = 30
    public function canceled( $id)
    {

        try {

            $requestcar = Requestcar::findOrFail($id);
        } catch (ModelNotFoundException $_) {
            return $this->errorJson('the request not founded', 404);
        }

        if ($requestcar->user_id !== auth()->id()) {
            abort(403);
        } else {
            if ($requestcar->update()) {
                $requestcar->status = 30;
                $requestcar->time_end = Carbon::now();
                $requestcar->save();

                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }
    }


    public function destroy($id)
    {

        try {

            $requestcar = Requestcar::find($id);
        } catch (ModelNotFoundException $_) {
            return $this->errorJson('the request not founded', 404);
        }

        if ($requestcar->owner_id  !==  auth()->id()) {
            abort(403);
        } else {
            if ($requestcar->delete()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }
    }
}
