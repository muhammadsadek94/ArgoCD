<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Comment\Http\Resources\Api\V2\User\CommentResource;
use App\Domains\Comment\Jobs\Admin\CreateCommentJob;
use App\Domains\Course\Http\Requests\Api\V2\AddCommentProjectApplicationRequest;
use App\Domains\Course\Models\ProjectApplication;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AddCommentFeature extends Feature
{


    public function handle(AddCommentProjectApplicationRequest $request)
    {
        $project_application = ProjectApplication::find($request->project_application_id);
        if(!$project_application){
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'can not add comment without apply a project'
                ]
            ]);
        }
        $comment = $this->run(CreateCommentJob::class, [
            "commnet" => $request->comment,
            "owner_id" => $request->user('api')->id,
            "owner_type" => User::class,
            "entity_id" => $request->project_application_id,
            "entity_type" => ProjectApplication::class,
        ]);

        if (!$comment) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'request cannot be resolved'
                ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'comment' => new CommentResource($comment)
            ]
        ]);
    }
}
