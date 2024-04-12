<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\HashidsService;


class UserController extends Controller
{
    protected $hashidsService;
    public function __construct(HashidsService $hashidsService)
{
    $this->hashidsService = $hashidsService;
}
    public function ticketCounts(Request $request)
    {
        $userId = Auth::id();
        // Assuming each ticket belongs to a user and each event has many tickets
    
        // Count of all upcoming events
        $allUpcomingEventsCount = Event::where('start_time', '>', Carbon::now())->count();
    
        // Count of user's past events based on tickets
        $pastEventsCount = Event::whereHas('tickets', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('end_time', '<', Carbon::now())->count();
    
        // Count of user's pending tickets
        $pendingTicketsCount = Ticket::where('user_id', $userId)->where('is_used', false)->count();
    
        return response()->json([
            'upcoming' => $allUpcomingEventsCount, // Changed to all upcoming events
            'past' => $pastEventsCount,
            'pending' => $pendingTicketsCount,
        ]);
    }

    public function fetchEvents(Request $request)
{
    $events = Event::with(['tickets'])->get()->map(function ($event) {
        // Calculate the ticket counts
        $availableTicketsCount = $event->tickets->where('is_used', false)->count();
        $usedTicketsCount = $event->tickets->where('is_used', true)->count();
        $totalTicketsCount = $event->tickets->count();

        // Encode the event ID
        $encodedId = $this->hashidsService->encode($event->id);

        // Calculate counts per ticket type
        $ticketsPerType = $event->tickets
            ->groupBy('type')
            ->mapWithKeys(function ($items, $key) {
                return [$key => ['available' => $items->where('is_used', false)->count(), 'used' => $items->where('is_used', true)->count()]];
            });

        // Add the ticket counts and other info to the event object
        return [
            'id' => $encodedId, // Use the encoded ID
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
            'availableTickets' => $availableTicketsCount,
            'usedTickets' => $usedTicketsCount,
            'totalTickets' => $totalTicketsCount,
            'ticketsPerType' => $ticketsPerType,
            'image' => $event->image, // Assuming you have an 'image' field
        ];
    });

    return response()->json($events);
}

    
}
