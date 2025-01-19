<?php

namespace App\Http\Controllers;

use App\Events\PartnerEntered;
use App\Events\StartSession;
use App\Http\Requests\StudySession\StoreSessionRequest;
use App\Models\StudySession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudySessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        return response()->json($user->studySessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request): JsonResponse
    {
        StudySession::create($request->all());
        return $this->index();
    }

    public function start(Request $request, StudySession $id): JsonResponse
    {
        $id->update(['on_going' => true]);
        StartSession::dispatch(auth()->user(), $id);
        return response()->json($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudySession $studySession): JsonResponse
    {
        return response()->json($studySession);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudySession $studySession): JsonResponse
    {
        $studySession->update($request->all());
        return $this->show($studySession);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudySession $studySession): JsonResponse
    {
        $studySession->delete();
        return $this->index();
    }
}
