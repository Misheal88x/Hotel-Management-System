<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking = Booking::where('active',false)->get();
        return response(['Booking' => $booking], 200);

    }

    public function my_booking()
    {
        $booking = Booking::where('user_id',auth()->user()->id)->get();
        return response(['Booking' => $booking], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'room_id'  => 'required|integer|exists:rooms,id',
            'start_date'     => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'end_date'      => 'required|date|date_format:Y-m-d|after_or_equal:start_date'
        ]);

        $booking = Booking::create([
            'user_id'       => auth()->user()->id,
            'room_id'       => $request->room_id,
            'status'        => true,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return response(['Message:'=>'Booking Created successfully','Code:'=>'1','booking' => $booking], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Booking::find($id);

        return response(['Message:'=>'Booking info fetched successfully','Code:'=>'1','user' => $booking], 200);

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
        $this->validate($request, [
            'room_id'        => 'integer|exists:rooms,id',
            'start_date'     => 'date|date_format:Y-m-d|after_or_equal:today',
            'end_date'       => 'date|date_format:Y-m-d|after_or_equal:start_date',
            'status'         => 'in:true,false'
        ]);

        $input = $request->all();

        $booking = Booking::find($id);
        $booking->update($input);

        return response(['Message:'=>'Booking info edited successfully','Code:'=>'1','Booking' => $booking], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Booking::find($id)->delete();
        return response(['Message:'=>'Booking deleted successfully','Code:'=>'1'], 204);
    }
}
