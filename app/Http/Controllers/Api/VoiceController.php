<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Voice;
use App\Http\Requests\VoiceRequest;

class VoiceController extends Controller
{
    /**
     * Function Description:
     * To create/update voice. User can only use 1 voice per question.
     * Only logged user can create/update voice
     *
     * Params:
     * question_id  = required|int|exists:questions,id
     * value        = required|bool
     */
    public function voice(VoiceRequest $request){

        $question = Question::find($request->post('question_id'));

        if ($question->user_id == auth()->id())
            abort(500, 'The user is not allowed to vote to your question');

        //check if user voted
        $voice = Voice::voted($request->post('question_id'))->first();

        if(!is_null($voice)){
            switch($voice->value){
                case($request->post('value')):
                    abort(500, 'The user is not allowed to vote more than once');
                    break;

                default:
                    $voice->update([
                        'value' => $request->post('value')
                    ]);
                    return response()->json([
                        'message'   =>'update your voice'
                    ], 201);
                    break;
            }
        }

        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);

        return response()->json([
            'message'   => 'Voting completed successfully'
        ], 200);
    }
}
