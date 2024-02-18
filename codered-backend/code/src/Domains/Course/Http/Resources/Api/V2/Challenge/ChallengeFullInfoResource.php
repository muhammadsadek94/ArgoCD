<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Challenge;

use App\Domains\Challenge\Models\UserCompetition;
use App\Domains\Challenge\Models\UserFlag;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use Carbon\Carbon;
use DateTime;
use Log;
use Mpdf\Tag\Dd;

class ChallengeFullInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user('api');

        return [
            'id'                    => $this->id,
            'slug'                  => $this->slug,
            'name'                  => $this->name,
            'challenge_id'          => $this->competition_id,
            'started'               => $user ? $user->competitions()->where('competition_id', $this->competition_id)->exists() : false,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'duration'              => round((int)$this->duration / 60, 1),
            'end_date'              => Carbon::parse($this->end_date)->format('F Y'),
            'description'           => $this->description,
            'competition_scenario'  => $this->competition_scenario,
            'tags'                  => $this->tags,
            'flags'                 => $this->flags,
            'user_metrics'          => $this->userMetrics($user),
            'leaderboard'           => $this->leaderboard(),
        ];
    }

    private function userMetrics($user)
    {
        if (!$user) return null;

        // if (!$user->competitions()->where('competition_id', $this->competition_id)->exists()) return null;

        // if (!$user->flags()->where('competition_id', $this->competition_id)->exists()) {
        //     return [
        //         'user_score'                        => 0,
        //         'median_score'                      => 0,
        //         'rank'                              => 'N/A',
        //         'time_spent_on_flags'               => 0,
        //         'average_time_spent_on_flags'       => 0,
        //         'number_of_flags_captured'          => 0,
        //         'average_number_of_flags_captured'  => 0,
        //     ];
        // }

        $users_competitions = UserCompetition::where('challenge_id', $this->id)
            ->where(function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                })->OrWhereHas('guest', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                });
            })->with('flags')->get();


        // $ranks = $users_competitions->sortByDesc('exam_score');

        // $ranks->map(function ($item) {
        //     Log::info("FROM CHALLANGE INFO " . $item->started_at);
        //     if (DateTime::createFromFormat('m-d-Y H:i:s', $item->started_at) !== false) {
        //         $started_at = Carbon::createFromFormat('m-d-Y H:i:s', $item->started_at)->format('Y-m-d H:i:s');
        //         $completed_at = Carbon::createFromFormat('m-d-Y H:i:s', $item->completed_at)->format('Y-m-d H:i:s');
        //         $item->time_taken = Carbon::parse($started_at)->diffInSeconds($completed_at);
        //     }
        //     $item->time_taken = 0;
        // })->toArray();

        // $ranks = $ranks->sortBy([
        //     ['exam_score', 'desc'],
        //     ['time_taken', 'asc'],
        // ]);

        // $ranks = $ranks->map(function ($item) {
        //     return $item->user_id ?? $item->guest_id;
        // })->toArray();

        // $ranks = array_values($ranks);

        // $ranks = array_flip($ranks);

        $ranks = $users_competitions;

        $ranks->map(function ($item) {
            $item->time_taken = $this->calculateCompetitionTime($item);
        })->toArray();

        $ranks = $ranks->sortBy([
            ['exam_score', 'desc'],
            ['time_taken', 'asc'],
        ]);

        $ranks = $ranks->map(function ($item) {
            return $item->user_id ?? $item->guest_id;
        })->toArray();

        $ranks = array_values($ranks);

        $ranks = array_flip($ranks);
        $user_challenge = $user->competitions()->where('competition_id', $this->competition_id)->first();

        $user_flags = $user->flags()->where('competition_id', $this->competition_id)->get();

        $all_flags = UserFlag::where('competition_id', $this->competition_id)->orderBy('time_taken', 'desc')
            ->get()
            ->unique('user_id');

        $all_flags->map(function ($item) {
            $carbonDuration = Carbon::createFromFormat('H:i:s', $item->time_taken);
            $seconds = $carbonDuration->hour * 3600 + $carbonDuration->minute * 60 + $carbonDuration->second;
            $item->time_taken = $seconds;
        })->toArray();

        $all_finished_competitions = UserCompetition::where('competition_id', $this->competition_id)
            ->where(function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                })->OrWhereHas('guest', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                });
            })
            ->get();

        $all_finished_competitions->map(function ($item) {
            $item->time_taken = $this->calculateCompetitionTime($item);
        })->toArray();

        $user_flags_count = $user_flags->count();

        $total_flags_time = $this->calculateCompetitionTime($user_challenge);

        $avg_flags_time = $all_finished_competitions->avg('time_taken');

        $total_hours = floor($total_flags_time / 3600);

        $total_hours = $total_hours ? "{$total_hours}hr" : null;

        $total_minutes = floor(($total_flags_time / 60) % 60);

        $total_minutes = $total_minutes ? "{$total_minutes} Min" : null;

        $total_seconds = $total_flags_time % 60;

        $total_seconds = $total_seconds ? "{$total_seconds} Sec" : null;

        $avg_hours = floor($avg_flags_time / 3600);

        $avg_hours = $avg_hours ? "{$avg_hours}hr" : null;

        $avg_mintues = floor(($avg_flags_time / 60) % 60);

        $avg_mintues = $avg_mintues ? "{$avg_mintues} Min" : null;

        $avg_seconds = $avg_flags_time % 60;

        $avg_seconds = $avg_seconds ? "{$avg_seconds} Sec" : null;

        $flags_count = UserFlag::where('competition_id', $this->competition_id)->groupBy('user_id', 'guest_id')->pluck('user_id')->count();

        $average_number_of_flags_captured = $flags_count ? UserFlag::where('competition_id', $this->competition_id)->count() / $flags_count : 0;

        $median_score = $users_competitions->sortByDesc('exam_score')->pluck('exam_score')->toArray();

        $median_score = $this->calculateMedian(array_values($median_score));

        $rank = 'N/A';
        if(isset($ranks[$user->id])) {
            $rank = $this->convertNumberToOrdinaryWord($ranks[$user->id] + 1);
        }

        $average_time_spent_on_flags = static function () use($avg_flags_time){
            $minutes = floor($avg_flags_time / 60);
            $seconds = $avg_flags_time % 60;

            // Create a human-readable format
            return "$minutes Min $seconds Sec";

        };

        return [
            'user_score'                        => !empty($user_challenge) ? round($user_challenge->exam_score) : 0,
            'median_score'                      => round($median_score),
            'rank'                              => $rank,
            'time_spent_on_flags'               => "{$total_hours} {$total_minutes} {$total_seconds}",
            'average_time_spent_on_flags'       => $average_time_spent_on_flags(),
//            'average_time_spent_on_flags'       => "{$avg_hours} {$avg_mintues} {$avg_seconds}",
            'number_of_flags_captured'          => $user_flags_count,
            'average_number_of_flags_captured'  => round($average_number_of_flags_captured)
        ];
    }

    private function leaderboard()
    {

//        $scoreWeight = 90;
//        $timeWeight = 10;
        $users_competitions = UserCompetition::where('challenge_id', $this->id)
            ->where(function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                })->OrWhereHas('guest', function ($query) {
                    $query->whereHas('flags', function ($query) {
                        $query->where('challenge_id', $this->id);
                    });
                });
            })->with('flags')->get();

        $leaderboard = [];

        foreach ($users_competitions as $user_competition) {
            $metrics = $this->getUserMetrics($user_competition, $user_competition->user_id, $user_competition->guest_id);
            $captured_percentage = $metrics['captured_percentage'];
            $time_spent_on_flags = $metrics['time_spent_on_flags'];
            $time_spent_on_flags_seconds = $metrics['time_spent_on_flags_seconds'];

            // Calculate a combined value for sorting (you can adjust the weights as needed)
//            $combinedValue = (int)((int)$captured_percentage * $scoreWeight) - (int)((int)$time_spent_on_flags_seconds * $timeWeight);
            $combinedValue = ((int)$captured_percentage * 1000000) - ((int)$time_spent_on_flags_seconds / 10);

            $leaderboard[] = [
                'name' => $user_competition->user?->display_name ?? $user_competition->guest?->display_name,
                'captured_percentage' => $captured_percentage,
                'time_spent_on_flags' => $time_spent_on_flags,
                'score' => round($user_competition->exam_score),
                'image' => new FileResource($user_competition->user->image ?? $user_competition->guest?->image),
                'combined_value' => $combinedValue, // This is the combined value for sorting
            ];
        }

        // Sort the leaderboard by the combined value in descending order
        $leaderboard = collect($leaderboard)->sortByDesc('combined_value')->take(7)->values()->toArray();

        // Add the "rank" key starting from 1
        foreach ($leaderboard as $key => $entry) {
            $entry['rank'] = $key + 1;
            $leaderboard[$key] = $entry;
        }

        return $leaderboard;
    }


    private function getUserMetrics($user_competition, $user_id = null, $guest_id = null)
    {
        if ($user_id) {
            $user_flags = $user_competition->flags->where('user_id', $user_id);
            $finished_competiton_for_user = $user_competition->where('user_id', $user_id)->first();
        }
        if ($guest_id) {
            $user_flags = $user_competition->flags->where('guest_id', $guest_id);
            $finished_competiton_for_user = $user_competition->where('guest_id', $guest_id)->first();
        }


        $challenge_flags_count = count($this->flags);

        $user_flags_count = $user_flags->count();

        $captured_percentage = $finished_competiton_for_user?->exam_score / $finished_competiton_for_user?->total_score * 100;

        if ($finished_competiton_for_user?->exam_score > $finished_competiton_for_user?->total_score) {
            $captured_percentage = 100;
        }

        $total_flags_time = $this->calculateCompetitionTime($user_competition);

        $total_hours = floor($total_flags_time / 3600);

        $total_hours = $total_hours ? "{$total_hours}hr" : null;

        $total_minutes = floor(($total_flags_time / 60) % 60);

        $total_minutes = $total_minutes ? "{$total_minutes} min" : null;

        $total_seconds = $total_flags_time % 60;

        $total_seconds = $total_seconds ? "{$total_seconds} Sec" : null;

        return [
            'captured_percentage' => round($captured_percentage) . "%",
            'time_spent_on_flags' => "{$total_hours} {$total_minutes} {$total_seconds}",
            'time_spent_on_flags_seconds' => $total_flags_time
        ];
    }


    private function calculateTotalFlagsTime($user_flags)
    {
        if (!$user_flags->sortByDesc('time_taken')->first()) return 0;
        $time_taken = $user_flags->sortByDesc('time_taken')->first()->time_taken;
        $carbonDuration = Carbon::createFromFormat('H:i:s', $time_taken);
        $seconds = $carbonDuration->hour * 3600 + $carbonDuration->minute * 60 + $carbonDuration->second;
        return $seconds;
    }

    private function calculateCompetitionTime($competition)
    {
        if (empty($competition) || empty($competition->started_at) || empty($competition->completed_at)) return 0;

        if (DateTime::createFromFormat('m-d-Y H:i:s', $competition->started_at) !== false) {
            $started_at = Carbon::createFromFormat('m-d-Y H:i:s', $competition->started_at)->format('Y-m-d H:i:s');
            $completed_at = Carbon::createFromFormat('m-d-Y H:i:s', $competition->completed_at)->format('Y-m-d H:i:s');
            $time_taken = Carbon::parse($started_at)->diffInSeconds($completed_at);
            return $time_taken;
        }

        return 0;
    }

    private function calculateMedian($scores)
    {
        if (count($scores) == 0) return 0;
        $count = sizeof($scores);
        $index = floor($count / 2);
        if ($count & 1) {
            return $scores[$index];
        } else {
            return ($scores[$index - 1] + $scores[$index]) / 2;
        }
    }

    private function convertNumberToOrdinaryWord($number)
    {
        return $number;
        $last_digit = $number % 10;
        $last_two_digits = $number % 100;
        if ($last_digit == 1 && $last_two_digits != 11) {
            return $number . 'st';
        } elseif ($last_digit == 2 && $last_two_digits != 12) {
            return $number . 'nd';
        } elseif ($last_digit == 3 && $last_two_digits != 13) {
            return $number . 'rd';
        } else {
            return $number . 'th';
        }
    }
}
