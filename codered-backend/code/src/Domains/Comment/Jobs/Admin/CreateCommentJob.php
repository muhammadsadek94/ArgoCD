<?php

namespace App\Domains\Comment\Jobs\Admin;

use App\Domains\Comment\Models\Comment;
use Illuminate\Foundation\Bus\Dispatchable;
use INTCore\OneARTFoundation\Job;

class CreateCommentJob extends Job
{
    use Dispatchable;

    private $commnet;
    private $owner_id;
    private $owner_type;
    private $entity_id;
    private $entity_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($commnet, $owner_id, $owner_type, $entity_id, $entity_type)
    {
        $this->commnet = $commnet;
        $this->owner_id = $owner_id;
        $this->owner_type = $owner_type;
        $this->entity_id = $entity_id;
        $this->entity_type = $entity_type;
    }

    public function handle()
    {
      return Comment::create([
            'comment' => $this->commnet,
            'owner_id' => $this->owner_id,
            'owner_type' => $this->owner_type,
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type
        ]);
    }
}
