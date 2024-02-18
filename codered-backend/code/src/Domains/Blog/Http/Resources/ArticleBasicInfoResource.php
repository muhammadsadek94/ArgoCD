<?php

namespace App\Domains\Blog\Http\Resources;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Blog\Models\ArticleLikes;
use App\Domains\Blog\Http\Resources\AdminResource;

class ArticleBasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'category_name' => new ArticleCategoryResource($this->category),
            'is_featured'   => $this->is_featured,
            'image'         => new FileResource($this->image),
            'created_at'    => $this->created_at->format('m D'),
            'count_likes'   => $this->getLikesCount(),
            'is_liked'      => $request->user('api') ? $request->user('api')->article_likes()->where('article_id', $this->id)->count() > 0 : false,
            'admin_details' => new AdminResource($this->admin),
            'tags'          => $this->tags,
        ];

    }

    private function getLikesCount(): int
    {
        return number_format(ArticleLikes::where('article_id', $this->id)->count());
    }
}
