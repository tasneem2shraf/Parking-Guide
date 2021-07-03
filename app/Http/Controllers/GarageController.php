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
use App\Models\Floor;
use JWTAuth;



class GarageController extends Controller
{
    use JsonReturn;

    protected $user;

    public function __construct()
    {
        $this->middleware(['owner'], ['except' => ['index', 'show', 'search', 'gat_nearest_garage']]);
        // $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function index()
    {

        return $this->dataJson(Garage::all());
    }


    public function show(int $id)
    {

        return $this->dataJson(Garage::with('comments', 'floors')->where('id', $id)->first());
    }

    // get one garage with it's comments, floors and reviews for any user 
    public function show_one_garage($id)
    {   
        $garage = Garage::find($id);
        return $garage->load('comments', 'floors','reviews');
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
            'name' => 'required',
            "lat" => 'required',
            "long" => 'required',
            "price" => 'required',
        ]);


        //make validation to the  Floor
        $floorValidation = request()->validate([
            'floorList' => 'required|array',
            'floorList.*.number' => 'required|int',
            'floorList.*.capacity' => 'required|int',
        ]);

        $garage = Garage::create(array_merge($val, ['owner_id' => $request->user()->id]));

        //add floor for the garage
        if ($request['floorList']) {
            foreach ($request['floorList'] as $value) {
                Floor::create([
                    'number' => $value['number'],
                    'capacity' => $value['capacity'],
                    'garage_id' => $garage['id'],
                ]);
            }
        }

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

            $garage->floors()->delete();

            $garage->name = $request->name;
            $garage->city = $request->city;
            $garage->street = $request->street;
            $garage->b_number = $request->b_number;
            $garage->lat = $request->lat;
            $garage->long = $request->long;
            $garage->price = $request->price;
            $garage->save();

            //add floor for the garage
            if ($request['floorList']) {
                foreach ($request['floorList'] as $value) {
                    Floor::create([
                        'number' => $value['number'],
                        'capacity' => $value['capacity'],
                        'garage_id' => $garage['id'],
                    ]);
                }
            }

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
            
            //delete all data in relation with garage
            $garage->floors()->delete();
            $garage->requestcars()->delete();
            $garage->comments()->delete();
            $garage->histories()->delete();
            $garage->user_reviews()->delete();

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

    //USER SEARCH FOR GARAGES by name
    public function search($name)
    {
        $garages = Garage::where("name", "like", "%" . $name . "%")->get();

        if ($garages->first()) {
            return $garages;
        } else {
            return response()->json([
                'success' => 'success',
                'message' => 'Sorry,Can not find garage with this name'
            ], 200);
        }
    }



    // user get nearest garage, by lat and long
    public function get_nearest_garage(Request $request)
    {
        //$lat = 30.177901;
        // $lon = 31.216075;
        $lat = $request->input('lat');
        $long = $request->input('long');

        $locations = DB::table("garages")->select(
            "id",
            "name",
            "price",
            "lat",
            "long",
            'city',
            'street',
            'b_number',
            DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(garages.lat))
                * cos(radians(garages.long) - radians(" . $long . "))
                + sin(radians(" . $lat .  "))
                * sin(radians(garages.lat))) AS distance")
        )
            ->orderBy('distance', 'asc')->get();

        return response()->json([
            'locations' => $locations,
        ], 200);
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
            'name' => 'required',
            "lat" => 'required',
            "long" => 'required'
        ]);
    }
}
