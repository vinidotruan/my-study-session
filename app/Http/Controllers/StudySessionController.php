<?php

namespace App\Http\Controllers;

use App\Events\StartSession;
use App\Http\Requests\StudySession\StoreSessionRequest;
use App\Models\StudySession;
use Illuminate\Http\Request;

class StudySessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        return response()->json($user->studySessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request)
    {
        StudySession::create($request->all());
        return $this->index();
    }

    public function start(Request $request, StudySession $id)
    {
        $id->update(['on_going' => true]);
        StartSession::dispatch(auth()->user(), $id);
        return response()->json($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudySession $studySession)
    {
        return response()->json($studySession);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudySession $studySession)
    {
        $studySession->update($request->all());
        return $this->show($studySession);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudySession $studySession)
    {
        $studySession->delete();
        return $this->index();
    }
}
