<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class AnswerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = $this->user->answers()->get(['json_questions', 'hospital', 'extraction_date', 'file_number', 'status', 'created_by']);
        return response()->json($answers->toArray());
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
        $validator = Validator::make($request->all(), [
            'hospital' => 'required',
            'extraction_date' => 'required',
            'file_number' => 'required',
            'start' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $path = Storage::path('public\questions.json');
        $json = file_get_contents($path);

        $answer = new Answer();
        $answer->json_questions = $json;
        $answer->hospital = $request->hospital;
        $answer->extraction_date = $request->extraction_date;
        $answer->file_number = $request->file_number;
        $answer->status = "started";

        if ($this->user->answers()->save($answer)) {
            return response()->json([
                'status' => true,
                'message' => 'Answer created',
                'answer' => json_decode($json, true)
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, we can not save the answer'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        if ($answer) {
            $answer->json_questions = json_decode($answer->json_questions, true);
            return response()->json([
                'status' => true,
                'data' => $answer
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, we can find the answer'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = Answer::where([
            ['id', "=", $id],
            ['status', '!=', 'completed']
        ])->update([
            'json_questions' => json_encode($request->json_questions),
            'status' => $request->state
        ]);

        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Answer updated'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, we can not update the answer'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        if ($answer->delete()) {
            return response()->json([
                'status' => true,
                'answer' => $answer
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Oops, we can not delete the answer'
            ], 400);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
