<?php

namespace App\Domains\Challenge\Http\Controllers\Admin;

use App\Domains\Challenge\Models\Challenge;
use App\Domains\Challenge\Rules\ChallengePermission;
use App\Foundation\Traits\HasAuthorization;;

use App\Foundation\Http\Controllers\Admin\CoreController;
use ColumnTypes;
use Illuminate\Support\Collection;
use Mpdf\Tag\Dd;

class ChallengeController extends CoreController
{
    use HasAuthorization;

    public $domain = "challenge";

    public function __construct(Challenge $model)
    {
        $this->model = $model;

        $this->isShowable = true;

        $this->select_columns = [

            [
                'name' => 'Name',
                'key' => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Activation"),
                'key' => 'activation',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text' => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text' => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ]
                ]
            ],

        ];

        $this->permitted_actions = [
            'index' => ChallengePermission::CHALLENGE_INDEX,
            'show' => null,
            'edit'  => ChallengePermission::CHALLENGE_EDIT,
            'create' => ChallengePermission::CHALLENGE_CREATE,
            'delete' => ChallengePermission::CHALLENGE_DELETE,
        ];

        $this->searchColumn = ["name"];


        parent::__construct();
    }

    public function onIndex()
    {
        $this->breadcrumb[0]->title = "Competitions";
    }

    public function onCreate()
    {
        $this->breadcrumb[0]->title = "Competitions";
    }

    public function onEdit()
    {
        $this->breadcrumb[0]->title = "Competitions";
    }

    public function onStore()
    {

        $this->request->validate([
            'name'                  => 'required|max:100',
            'slug'                  => 'required|unique:challenges',
            'competition_id'        => 'required|unique:challenges',
            'duration'              => 'required|numeric|min:1',
            'end_date'              => 'required|date|after:today',
            'description'           => 'required|max:4000',
            'competition_scenario'  => 'required|max:4000',
            'tags'                  => 'required|array',
            'flags'                 => 'required|array',
            'tags.*'                => 'required',
            'flags.*'               => 'required',
        ]);

        $flags = $this->formatFlagsField($this->request->flags['title'], $this->request->flags['description']);

        $this->request->merge([
            'flags' => $flags->toArray()
        ]);
    }

    public function onUpdate()
    {
        $this->request->validate([
            'name'                  => 'required|max:100',
            'slug'                  => 'required|unique:challenges,slug,' . $this->request->challenge,
            'competition_id'        => 'required|unique:challenges,competition_id,' . $this->request->challenge,
            'duration'              => 'required|numeric|min:1',
            'end_date'              => 'required|date|after:today',
            'description'           => 'required|max:4000',
            'competition_scenario'  => 'required|max:4000',
            'tags'                  => 'required|array',
            'flags'                 => 'required|array',
            'tags.*'                => 'required',
            'flags.*'               => 'required',
        ]);

        $flags = $this->formatFlagsField($this->request->flags['title'], $this->request->flags['description']);

        $this->request->merge([
            'flags' => $flags->toArray()
        ]);
    }

    public function formatFlagsField($titles, $descriptions): Collection
    {

        foreach ($titles as $key => $title) {
            $titles[$key] = [
                'title' => $title,
                'description' => $descriptions[$key]
            ];
        }

        return collect($titles)->values();
    }
}
