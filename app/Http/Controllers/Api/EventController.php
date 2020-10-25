<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    //
    public function create_events(Request $request)
    {
        $rules = [
            "name" => "required",
            "start" => "required",
            "end" => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 400);
        } else {
            $event = new Event;
            
            $event->name = $request->name;
            $event->start = $request->start;
            $event->end = $request->end;

            $event->save();
            
            $days = [];
            foreach ($request->days as $value) {
                # code...
                array_push($days, array("name" => $value));
            }

            $event->days()->createMany([$days]);

            return response()->json(["status" => true, "message" => "Event successfully saved!"], 200);
        }
    }

    public function display_events(Request $request)
    {
        $event = Event::orderyBy('created_at', 'desc')->with('days')->first();

        return response()->json($event, 200);
    }
}
