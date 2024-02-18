<?php

namespace App\Domains\Brightcove\API;

use App\Domains\Brightcove\API\API;
use App\Domains\Brightcove\API\Request\IngestRequest;
use App\Domains\Brightcove\API\Response\IngestResponse;

/**
 * Class DI
 *
 * @package Brightcove\API
 * @api
 */
class DI extends API {

  protected function diRequest($method, $endpoint, $result, $is_array = FALSE, $post = NULL) {
    return $this->client->request($method, '1', 'ingest', $this->account, $endpoint, $result, $is_array, $post);
  }

  /**
   * @return IngestResponse
   */
  public function createIngest($video_id, IngestRequest $request) {
    return $this->diRequest('POST', "/videos/{$video_id}/ingest-requests", IngestResponse::class, FALSE, $request);
  }
}
