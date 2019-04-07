<?php

namespace App\Modules\Homestay\Controllers;

use App\Location;
use App\Modules\Homestay\Models\Homestay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class HomestayController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # parent::__construct();
    }

    public function index(Request $request)
    {
        return view('Homestay::index');
    }

    public function getHomestays(Request $request)
    {
        $homestays = Homestay::query();

        $homestay_types = explode('-', $request->homestay_types);
        $price = explode('-', $request->price);
        $orderBy = $request->orderBy;

        if ($request->homestay_types) {
            $homestays = $homestays->whereIn('homestay_type', $homestay_types);
        }
        if ($request->price) {
            $homestays = $homestays->whereBetween('price', $price);
        }
        switch ($orderBy) {
            case 'highestPrice':
                $homestays = $homestays->orderBy('price', 'DESC');
                break;
            case 'lowestPrice':
                $homestays = $homestays->orderBy('price', 'ASC');
                break;
            case 'rate':
                $homestays = $homestays->orderBy('rating', 'DESC');
                break;
            case 'newest':
                $homestays = $homestays->orderBy('id', 'DESC');
                break;
            default:
                break;
        }

        $homestays = $homestays->paginate(8);
        return $homestays;
    }
    public function getSuggestLocationHomestays(Request $request) {
        $search = $request->search;
        $first = Location::whereRaw('MATCH (name) AGAINST ("' . $search . '" IN BOOLEAN MODE)')
            ->select(DB::raw("id, name, query_str, 1 as type"));
        $locations = Location::Where('name', 'like', '%' . $search . '%')
            ->union($first)
            ->select(DB::raw("id, name, query_str, 1 as type"))
            ->take(5)->get()->toArray();

        $numberOfHomestays = 8 - count($locations);

        $homestays = Homestay::query()
            ->select(DB::raw('id, name,0 as query_str, 2 as type'))
            ->where('name', 'like', '%' . $search . '%')
            ->take($numberOfHomestays)->get()->toArray();

        return array_merge($locations, $homestays);
    }

}