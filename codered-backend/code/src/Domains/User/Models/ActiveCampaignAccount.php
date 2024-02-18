<?php

namespace App\Domains\User\Models;

use INTCore\OneARTFoundation\Model;

/**
 * @property mixed email
 * @property mixed id
 * @property mixed account_id
 */
class ActiveCampaignAccount extends Model
{
    protected $fillable = ['email', 'account_id'];
}

