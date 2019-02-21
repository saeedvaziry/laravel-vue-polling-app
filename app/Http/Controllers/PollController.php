<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Poll;
use App\Notifications\PollCreated;

class PollController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $polls = Poll::orderBy('id', 'desc')->withCount('votes')->get();

        return response()->json(compact('polls'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = [
            'question' => 'required',
            'answers'  => 'required',
            'email'    => 'required|email'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'error'   => 'validation failed',
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (!is_array($request->answers)) {
            return response()->json([
                'error'   => 'validation error',
                'message' => __('Answers format is wrong')
            ], 422);
        }

        $user = User::where('email', '=', $request->email)->first();
        try {
            DB::beginTransaction();
            if (!$user) {
                $user = User::create([
                    'email'    => $request->email,
                    'password' => 'not_set'
                ]);
            }
            $poll = $user->polls()->create([
                'question' => $request->question
            ]);
            $poll->update([
                'token' => poll_token_generator($poll, $user)
            ]);
            foreach ($request->answers as $answer) {
                if ($answer && is_string($answer)) {
                    if (!$poll->answers()->where('answer', '=', $answer)->first()) {
                        $poll->answers()->create([
                            'answer' => $answer
                        ]);
                    } else {
                        DB::rollback();

                        return response()->json([
                            'error'   => 'validation error',
                            'message' => __('Duplicate answer')
                        ], 422);
                    }
                } else {
                    DB::rollback();

                    return response()->json([
                        'error'   => 'validation error',
                        'message' => __('Answers format is wrong')
                    ], 422);
                }
            }

            DB::commit();

            $user->notify(new PollCreated($poll));

            return response()->json([
                'message' => __('Poll created'),
                'poll'    => $poll,
                'token'   => $poll->token
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return handle_front_exception($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $id)
    {
        $poll = Poll::where('id', '=', $id)->with('answers')->first();

        if ($poll) {
            $rules = [
                'hardwareConcurrency' => 'required',
                'language'            => 'required',
                'platform'            => 'required',
                'timezone'            => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'validation failed',
                    'message' => 'Validation Error'
                ], 422);
            }

            $vote = $poll->votes()->where(function ($query) use ($request) {
                $query->where('ip', '=', $request->ip())
                    ->where('country', '=', ip_to_country($request->ip()))
                    ->where('hardware_concurrency', '=', $request->hardwareConcurrency)
                    ->where('language', '=', $request->language)
                    ->where('platform', '=', $request->platform)
                    ->where('timezone', '=', $request->timezone);
            })->first();

            if ($vote) {
                $poll['vote'] = $vote;
                $poll['stats'] = $this->stats($poll);
            }

            return response()->json(compact('poll'));
        }

        return response()->json([
            'error'   => 'not found',
            'message' => __('Not found')
        ], 404);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, $id)
    {
        $poll = Poll::where('id', '=', $id)->first();

        if ($poll) {
            $rules = [
                'answer_id' => 'required|exists:poll_answers,id,poll_id,' . $poll->id
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'validation failed',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $rules = [
                'hardwareConcurrency' => 'required',
                'language'            => 'required',
                'platform'            => 'required',
                'timezone'            => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'validation failed',
                    'message' => 'Validation Error'
                ], 422);
            }

            try {
                return DB::transaction(function () use ($poll, $request) {
                    $vote = $poll->votes()->where(function ($query) use ($request) {
                        $query->where('ip', '=', $request->ip())
                            ->where('country', '=', ip_to_country($request->ip()))
                            ->where('hardware_concurrency', '=', $request->hardwareConcurrency)
                            ->where('language', '=', $request->language)
                            ->where('platform', '=', $request->platform)
                            ->where('timezone', '=', $request->timezone);
                    })->first();

                    if ($vote) {
                        return response()->json([
                            'error'   => 'you already voted',
                            'message' => __('You already voted')
                        ], 422);
                    }

                    $vote = $poll->votes()->create([
                        'answer_id'            => $request->answer_id,
                        'ip'                   => $request->ip(),
                        'country'              => ip_to_country($request->ip()),
                        'language'             => $request->language,
                        'color_depth'          => $request->colorDepth,
                        'hardware_concurrency' => $request->hardwareConcurrency,
                        'timezone_offset'      => $request->timezoneOffset,
                        'timezone'             => $request->timezone,
                        'platform'             => $request->platform,
                    ]);

                    $poll['answers'] = $poll->answers;
                    $poll['vote'] = $vote;
                    $poll['stats'] = $this->stats($poll);

                    return response()->json([
                        'message' => __('Your vote submited'),
                        'poll'    => $poll
                    ]);
                });
            } catch (\Exception $e) {
                return handle_front_exception($e);
            }
        }

        return response()->json([
            'error'   => 'not found',
            'message' => __('Not found')
        ], 404);
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function manage($token)
    {
        $poll = Poll::where('token', '=', $token)->with('answers')->first();

        if ($poll) {
            $poll['stats'] = $this->stats($poll);

            return response()->json(compact('poll'));
        }

        return response()->json([
            'error'   => 'not found',
            'message' => __('Not found')
        ], 404);
    }

    /**
     * @param $token
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function manageStatus($token, $status)
    {
        $poll = Poll::where('token', '=', $token)->with('answers')->first();

        if ($poll) {
            switch ($status) {
                case 'activate':
                    $status = 1;
                    break;
                case 'deactivate':
                    $status = 0;
                    break;
            }
            $poll->update([
                'status' => $status
            ]);

            return response()->json([
                'message' => __('Done')
            ]);
        }

        return response()->json([
            'error'   => 'not found',
            'message' => __('Not found')
        ], 404);
    }

    /**
     * @param Poll $poll
     * @return mixed
     */
    private function stats(Poll $poll)
    {
        $votes = DB::table('votes')
            ->where('poll_id', '=', $poll->id)
            ->groupBy('answer_id')
            ->select(DB::raw('answer_id, count(*) as total'))
            ->get();

        $totalVotes = $poll->votes()->count();

        foreach ($votes as $vote) {
            $vote->percent = ((float)$vote->total / $totalVotes) * 100;
        }

        return $votes;
    }
}
