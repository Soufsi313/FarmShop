<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\RentalPenalty;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RentalAdminController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage_rentals']);
    }

    /**
     * Display a listing of all rentals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Rental::with(['user', 'items.product', 'penalties']);
        
        // Filtres
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->date_from) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
        
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('rental_number', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%")
                               ->orWhere('email', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $rentals = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $rentals,
            'statuses' => Rental::getAllStatuses(),
            'statistics' => $this->getGlobalStatistics()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
