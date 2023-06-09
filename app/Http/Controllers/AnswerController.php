<?php

namespace App\Http\Controllers;

use DB;
use App\Answer;
use App\Hospital;
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
    public function getAllAnswersAgent($user_id, Request $request)
    {
        if (intval($user_id) == $this->user->id) { //get answers by a logged in user
            $filters = [['created_by', '=', $user_id]];
            if ($request->hospital_id) {
                array_push($filters, ['hospital_id', '=', $request->hospital_id]);
            }
            if ($request->extraction_date) {
                array_push($filters, ['extraction_date', '=', $request->extraction_date]);
            }
            $answers = $answers = Answer::where($filters)->get();
            for ($i = 0; $i < sizeof($answers); $i++) {
                $answers[$i]->json_questions =  json_decode($answers[$i]->json_questions, true);
            }
            return response()->json([
                "status" => "successful",
                "answers" => $answers
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "No answers associated with the user who is logged in",
                "answers" => []
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllAnswersAdmin(Request $request)
    {
        $filters = [];
        if ($request->hospital_id) {
            array_push($filters, ['hospital_id', '=', $request->hospital_id]);
        }
        if ($request->extraction_date) {
            array_push($filters, ['extraction_date', '=', $request->extraction_date]);
        }

        $answers = DB::table('answers')
            ->join('users', 'users.id', '=', 'answers.created_by')
            ->join('hospitals', 'hospitals.id', '=', 'answers.hospital_id')
            ->where($filters)
            ->get();
        for ($i = 0; $i < sizeof($answers); $i++) {
            $answers[$i]->json_questions =  json_decode($answers[$i]->json_questions, true);
        }
        return response()->json([
            "status" => "success",
            "answers" => $answers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $synced_data = [];
        for ($i = 0; $i < sizeof($request->data); $i++) {
            $update = Answer::where([
                ['file_number', "=", $request->data[$i]['file_number']],
                ['status', '!=', 'completed']
            ])->update([
                'json_questions' => json_encode($request->data[$i]['json_questions']),
                'status' => $request->data[$i]['status']
            ]);
            if ($update == 0) {
                $answer = new Answer();
                $answer->json_questions = json_encode($request->data[$i]['json_questions']);
                $answer->hospital_id =  $request->data[$i]['hospital_id'];
                $answer->extraction_date = $request->data[$i]['extraction_date'];
                $answer->file_number = $request->data[$i]['file_number'];
                $answer->registration_number = $request->data[$i]['registration_number'];
                $answer->mode = "online";
                $answer->status = $request->data[$i]['status'];
                $saved = $this->user->answers()->save($answer);
                array_push($synced_data, $saved->id);
            } else {
                array_push($synced_data, $update);
            }
        }
        if (sizeof($synced_data) == sizeof($request->data)) {
            return response()->json([
                'status' => "successful",
                'message' => 'all answers syncronized successfuly',
                'answer_ids' => $synced_data
            ]);
        } else if (sizeof($synced_data) != 0 && sizeof($synced_data) < sizeof($request->data)) {
            return response()->json([
                'status' => "successful",
                'message' => '[' . sizeof($synced_data) . '] answers syncronized successfuly',
                'answer_ids' => $synced_data
            ]);
        } else {
            return response()->json([
                'status' => "failed",
                'errors' => "we can't syncronize the answers currently"
            ], 400);
        }
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
            'hospital_id' => 'required',
            'extraction_date' => 'required',
            'file_number' => 'required',
            'start' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "failed",
                'errors' => $validator->errors()
            ], 400);
        }
        $hospital = Hospital::where('id', $request->hospital_id)->first();
        $answer = Answer::where('file_number', $request->file_number)->first();
        if ($hospital && !$answer) {
            $path = Storage::path('public/questions.json');
            $json = file_get_contents($path);
            $answer = new Answer();
            $answer->json_questions = $json;
            $answer->hospital_id = $request->hospital_id;
            $answer->extraction_date = $request->extraction_date;
            $answer->file_number = $request->file_number;
            $answer->registration_number = $request->registration_number;
            $answer->status = "started";

            $saved = $this->user->answers()->save($answer);
            if ($saved) {
                return response()->json([
                    'status' => "successful",
                    'message' => 'Answer created',
                    'answer_id' => $saved->id,
                    'answer' => json_decode($json, true)
                ]);
            } else {
                return response()->json([
                    'status' => "failed",
                    'message' => 'Oops, we can not save the answer'
                ]);
            }
        } else {
            return response()->json([
                'status' => "failed",
                'message' => 'We are enable to find the hospital or the file number already exist.'
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
                'status' => "successful",
                'data' => $answer
            ]);
        } else {
            return response()->json([
                'status' => "failed",
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
                'status' => "successful",
                'answer_id' => intval($id),
                'message' => 'Answer updated'
            ]);
        } else {
            return response()->json([
                'status' => "failed",
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
                'status' => "successful",
                'answer' => $answer
            ]);
        } else {
            return response()->json([
                'status' => "failed",
                'message' => 'Oops, we can not delete the answer'
            ], 400);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
