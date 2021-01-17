<?php


namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Garage;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use JWTAuth;


class GarageController extends Controller
{
    use JsonReturn;

    protected $user;

    public function __construct()
    {
        $this->middleware( ['owner'], ['except'=>['index']]);
    }


    public function index()
    {

        return $this->dataJson(Garage::all());

    }


    public function show(int $id)
    {

        return Garage::findOrFail($id);

    }

    public function store(Request $request)
    {
        $val = request()->validate([
            'city' => 'required',
            'street' => 'required',
            'b_number' => 'required|int',
            'capacity' => 'required|int',
            'name' => 'required',
            "lat" => 'required',
            "long" => 'required'
        ]);


        $garage = Garage::create(array_merge($val, ['owner_id' => $request->user()->id]));


        if ($garage) {
            return response()->json([
                'success' => true,
                'garage' => $garage
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, garage could not be added'
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {

        $garage = Garage::find($id);

        // check if the user is the real garage owner
        if ($garage->owner_id !==  auth()->id()){
            abort(403);
        } else {

            $garage->name = $request->name;
            $garage->city = $request->city;
            $garage->street = $request->street;
            $garage->b_number = $request->b_number;
            $garage->capacity = $request->capacity;

            if ($garage->update()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }

    }

    public function destroy(int $id)
    {

        $garage = Garage::findOrFail($id);

        // check if the user is the real garage owner
        if ($garage->owner_id !==  auth()->id()){
            abort(403);
        } else {
            if ($garage->delete()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }

    }

}




/// testtttttttttttttttttt
