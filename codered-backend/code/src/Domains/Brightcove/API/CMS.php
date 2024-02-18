<?php

namespace App\Domains\Brightcove\API;

use App\Domains\Brightcove\API\Request\SubscriptionRequest;
use App\Domains\Brightcove\Item\Subscription;
use App\Domains\Brightcove\Item\Video\Video;
use App\Domains\Brightcove\Item\Video\Source;
use App\Domains\Brightcove\Item\Video\Images;
use App\Domains\Brightcove\Item\Playlist;
use App\Domains\Brightcove\Item\CustomFields;

/**
 * This class provides uncached read access to the data via request functions.
 *
 * @api
 */
class CMS extends API
{

    protected function cmsRequest($method, $endpoint, $result, $is_array = false, $post = null)
    {
        return $this->client->request($method, '1', 'cms', $this->account, $endpoint, $result, $is_array, $post);
    }

    /**
     * Lists video objects with the given restrictions.
     *
     * @return Video[]
     */
    public function listVideos($search = null, $sort = null, $limit = null, $offset = null)
    {
        $query = '';
        if($search) {
            $query .= '&q=' . urlencode($search);
        }
        if($sort) {
            $query .= "&sort={$sort}";
        }
        if($limit) {
            $query .= "&limit={$limit}";
        }
        if($offset) {
            $query .= "&offset={$offset}";
        }
        if(strlen($query) > 0) {
            $query = '?' . substr($query, 1);
        }
        return $this->cmsRequest('GET', "/videos{$query}", Video::class, true);
    }

    /**
     * Returns the amount of a searched video's result.
     *
     * @return int|null
     */
    public function countVideos($search = null)
    {
        $query = $search === null ? '' : "?q=" . urlencode($search);
        $result = $this->cmsRequest('GET', "/counts/videos{$query}", null);
        if($result && !empty($result['count'])) {
            return $result['count'];
        }
        return null;
    }

    /**
     * Gets the images for a single video.
     *
     * @return Images
     */
    public function getVideoImages($video_id)
    {
        return $this->cmsRequest('GET', "/videos/{$video_id}/images", Images::class);
    }

    /**
     * Gets the sources for a single video.
     *
     * @return Source[]
     */
    public function getVideoSources($video_id)
    {
        return $this->cmsRequest('GET', "/videos/{$video_id}/sources", Source::class, true);
    }

    public function getVideoFields()
    {
        return $this->cmsRequest('GET', "/video_fields", CustomFields::class, false);
    }

    /**
     * Gets the data for a single video by issuing a GET request.
     *
     * @return Video $video
     */
    public function getVideo($video_id)
    {
        return $this->cmsRequest('GET', "/videos/{$video_id}", Video::class);
    }

    /**
     * Creates a new video object.
     *
     * @return Video $video
     */
    public function createVideo(Video $video)
    {
        return $this->cmsRequest('POST', '/videos', Video::class, false, $video);
    }

    /**
     * add video to folder.
     *
     * @param $video_id
     * @param $folder_id
     * cmsRequest($method, $endpoint, $result, $is_array = FALSE, $post = NULL) {
     */
    public function addVideoToFolder($video_id, $folder_id)
    {
        return $this->cmsRequest('put', "/folders/{$folder_id}/videos/{$video_id}", null, false, null);
    }

    /**
     * Updates a video object with an HTTP PATCH request.
     *
     * @param Video $video
     * @return Video $video
     */
    public function updateVideo(Video $video)
    {
        $video->fieldUnchanged('account_id', 'id');
        return $this->cmsRequest('PATCH', "/videos/{$video->getId()}", Video::class, false, $video);
    }

    /**
     * Deletes a video object.
     */
    public function deleteVideo($video_id)
    {
        return $this->cmsRequest('DELETE', "/videos/{$video_id}", null);
    }

    /**
     * @return int
     */
    public function countPlaylists()
    {
        $result = $this->cmsRequest('GET', "/counts/playlists", null);
        if($result && !empty($result['count'])) {
            return $result['count'];
        }
        return null;
    }

    /**
     * @return Playlist[]
     */
    public function listPlaylists($sort = null, $limit = null, $offset = null)
    {
        $query = '';
        if($sort) {
            $query .= "&sort={$sort}";
        }
        if($limit) {
            $query .= "&limit={$limit}";
        }
        if($offset) {
            $query .= "&offset={$offset}";
        }
        if(strlen($query) > 0) {
            $query = '?' . substr($query, 1);
        }
        return $this->cmsRequest('GET', "/playlists{$query}", Playlist::class, true);
    }

    /**
     * @param Playlist $playlist
     * @return Playlist
     */
    public function createPlaylist(Playlist $playlist)
    {
        return $this->cmsRequest('POST', '/playlists', Playlist::class, false, $playlist);
    }

    /**
     * @param string $playlist_id
     * @return Playlist
     */
    public function getPlaylist($playlist_id)
    {
        return $this->cmsRequest('GET', "/playlists/{$playlist_id}", Playlist::class);
    }

    /**
     * @param Playlist $playlist
     * @return Playlist
     */
    public function updatePlaylist(Playlist $playlist)
    {
        $playlist->fieldUnchanged('id');
        return $this->cmsRequest('PATCH', "/playlists/{$playlist->getId()}", Playlist::class, false, $playlist);
    }

    /**
     * @param string $playlist_id
     */
    public function deletePlaylist($playlist_id)
    {
        $this->cmsRequest('DELETE', "/playlists/{$playlist_id}", null);
    }

    /**
     * @param string $playlist_id
     * @return int
     */
    public function getVideoCountInPlaylist($playlist_id)
    {
        $result = $this->cmsRequest('GET', "/counts/playlists/{$playlist_id}/videos", null);
        if($result && !empty($result['count'])) {
            return $result['count'];
        }
        return null;
    }

    /**
     * @param string $playlist_id
     * @return Video[]
     */
    public function getVideosInPlaylist($playlist_id)
    {
        return $this->cmsRequest('GET', "/playlists/{$playlist_id}/videos", Video::class, true);
    }

    /**
     * @return Subscription[]|null
     */
    public function getSubscriptions()
    {
        return $this->cmsRequest('GET', '/subscriptions', Subscription::class, true);
    }

    /**
     * @param string $subscription_id
     * @return Subscription
     */
    public function getSubscription($subscription_id)
    {
        return $this->cmsRequest('GET', "/subscriptions/{$subscription_id}", Subscription::class);
    }

    /**
     * @param SubscriptionRequest $request
     * @return Subscription|null
     */
    public function createSubscription(SubscriptionRequest $request)
    {
        return $this->cmsRequest('POST', '/subscriptions', Subscription::class, false, $request);
    }

    /**
     * @param string $subscription_id
     */
    public function deleteSubscription($subscription_id)
    {
        $this->cmsRequest('DELETE', "/subscriptions/{$subscription_id}", null);
    }

}
