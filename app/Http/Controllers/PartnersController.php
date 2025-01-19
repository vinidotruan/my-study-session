<?php

namespace App\Http\Controllers;

use App\Events\PartnerEntered;
use App\Events\PartnerLeaved;
use App\Models\Partners;
use App\Models\StudySession;
use App\Models\User;
use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $partner = Partners::firstOrCreate(['partner_id' => $request->get('partner_id')],$request->all());
        return $this->followerEntered($request);
    }


    public function followerLeave(Request $request)
    {
        $sessionId = $request->get('session_id');
        $userId = $request->get('partner_id');
        $user = User::find($userId);
        $session = StudySession::find($sessionId);

        $waitingRoom = WaitingRoom::where(['session_id' => $sessionId])->first();
        $waiters = $waitingRoom->waiters ?? [];

        $waitersUpdated = collect($waitingRoom->waiters)->reject(function($waiter) use($userId){
            return $waiter['id'] === $userId;
        })->all();
        $waitingRoom->waiters = $waitersUpdated;
        $waitingRoom->save();

        PartnerLeaved::dispatch($session);

        return response()->json(['data' => 'Owner notified']);
    }

    public function followerEntered(Request $request)
    {
        $sessionId = $request->get('session_id');
        $userId = $request->get('partner_id');
        $user = User::find($userId);
        $session = StudySession::find($sessionId);
        $waitingRoom = WaitingRoom::firstOrCreate(['session_id' => $sessionId]);

        if($waitingRoom) {
            $waiters = $waitingRoom->waiters ?? [];
            $isWaiting = collect($waitingRoom->waiters)->contains('id', $userId);
            if(!$isWaiting) {
                $waiters[] = $user;
                $waitingRoom->waiters = $waiters;
                $waitingRoom->save();
            }
        }
        PartnerEntered::dispatch($session);

        return response()->json(['data' => Auth::user()->refresh()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Partners $partners)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partners $partners)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partners $partner, Request $request)
    {
        $sessionId = $partner->session_id;
        $session = StudySession::find($sessionId);
        $userId = $partner->partner_id;

        $waitingRoom = WaitingRoom::where(['session_id' => $sessionId])->first();
        $waiters = $waitingRoom->waiters ?? [];

        $waitersUpdated = collect($waitingRoom->waiters)->reject(function($waiter) use($userId){
            return $waiter['id'] === $userId;
        })->all();
        $waitingRoom->waiters = $waitersUpdated;
        $waitingRoom->save();

        PartnerLeaved::dispatch($session);

        $partner->delete();
        return response()->json(["data" => Auth::user()->refresh()]);
    }
}
