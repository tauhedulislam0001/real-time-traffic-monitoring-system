<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TrafficUpdate;

class TrafficController extends Controller
{
    public function getTrafficData()
    {
        $trafficData = [
            'location' => '23.7465,90.3840', // Moghbazar, Dhaka, Bangladesh
            'status' => 'moderate',
            'incidents' => [
                [
                    'type' => 'Accident',
                    'location' => '23.7465,90.3840', // Latitude, Longitude
                ],
                [
                    'type' => 'Congestion',
                    'location' => '23.7500,90.3800', // Latitude, Longitude
                ],
            ],
        ];

        // Broadcast the traffic update event
        event(new TrafficUpdate($trafficData));
        return response()->json($trafficData);
    }
}
