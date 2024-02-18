<?php

namespace App\Domains\Brightcove\API\Request;

use App\Domains\Brightcove\Item\ObjectBase;

/**
 * Class IngestRequestMaster
 *
 * @package Brightcove\API\Request
 * @api
 */
class IngestRequestMaster extends ObjectBase {
  protected $url;

  public function applyJSON(array $json) {
    parent::applyJSON($json);
    $this->applyProperty($json, 'url');
  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * @param string $url
   * @return $this
   */
  public function setUrl($url) {
    $this->url = $url;
    $this->fieldChanged('url');
    return $this;
  }
}
