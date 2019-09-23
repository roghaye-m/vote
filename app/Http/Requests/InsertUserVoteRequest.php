<?php

namespace App\Http\Requests;

use App\Rules\Active;
use App\Rules\Enable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InsertUserVoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $voteID = $request->input('vote_id');
        $request->offsetSet('user_id', $request->user()->id);
        return [
            'user_id'    => ['required','min:1' , Rule::unique('user_vote_option')->where(function ($query) use ($voteID) {
                return $query->where('vote_id', $voteID );
            })],
            'vote_id'    => ['required' , new Active],
            'option_id'  => ['required' , new Enable , Rule::exists('options','id')->where('vote_id', $voteID)],
        ];
    }
}
