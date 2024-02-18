<?php

namespace App\Domains\User\Services\ActiveCampaign;

class ActiveCampaignService
{

    use AccountService, EventService;

    /**
     * @var \App\Domains\User\Services\ActiveCampaign\ActiveCampaign
     */
    public $ac;

    /**
     * IntercomService constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->ac = new ActiveCampaign($config['api_url'], $config['api_key'], "", "", $config['track_email'], $config['track_actid'], $config['track_key']);
    }

    /**
     * Get Owner account details
     *
     * @return mixed
     */
    /*public function getAccount()
    {
        return $this->accountView();
    }*/

}
