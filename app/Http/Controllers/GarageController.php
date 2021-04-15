<?php


namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Models\Garage;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\ConmmentController;
use JWTAuth;



class GarageController extends Controller
{
    use JsonReturn;

    protected $user;

    public function __construct()
    {
        $this->middleware(['owner'], ['except' => ['index', 'show']]);
        // $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function index()
    {

        return $this->dataJson(Garage::all());
    }


    public function show(int $id)
    {

        return $this->dataJson(Garage::with('comments')->where('id', $id)->first());
    }


    // retutn all garages of current user using function:get_owner_garages / in user model
    public function show_owner_garages()
      {

         //enter the code her
          return $this->dataJson(User::find(auth()->id())->get_owner_garages);


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
        //add to make validation
        $this->garageValidate();

        $garage = Garage::find($id);
        // check user is the real owner of garage
        if ($garage->owner_id !==  auth()->id()) {
            abort(403);
        } else {

            $garage->name = $request->name;
            $garage->city = $request->city;
            $garage->street = $request->street;
            $garage->b_number = $request->b_number;
            $garage->capacity = $request->capacity;
            $garage->lat = $request->lat;
            $garage->long = $request->long;
            $garage->save();

            if ($garage->update()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }
    }

    public function destroy($id)
    {

        //find or fail. to make failer if the id didn't founded
        $garage = Garage::findOrFail($id);

        if ($garage->owner_id !==  auth()->id()) {
            abort(403);
        } else {
            if ($garage->delete()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'can not be updated']);
            }
        }
    }


    // find the status = 10 or check camera not done
    // find the garage{id} , it's requests : requestcars.  and filter : status=10
    public function get_garage_active_requests($id)
    {
        $garage = Garage::find($id);
        if ($garage->owner_id !==  auth()->id()) {
            abort(403);
        } else {
            return Garage::where('id', $id)->with(['requestcars' => function ($query) {
                $query->where('status', 10);
            }])->first();
        }
    }


    /**
     * @return array
     */
    public function garageValidate()
    {
        return request()->validate([
            'city' => 'required',
            'street' => 'required',
            'b_number' => 'required|int',
            'capacity' => 'required|int',
            'name' => 'required',
            "lat" => 'required',
            "long" => 'required'
        ]);
    }
}
