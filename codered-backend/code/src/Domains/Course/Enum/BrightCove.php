<?php


namespace App\Domains\Course\Enum;


use App\Foundation\BasicEnum;

class BrightCove extends BasicEnum
{
    const PLAYER_ID = "IFydT5myK";


    public static function PLAYERID()
    {
        return 'IFydT5myK';
    }

    public static function ACCOUNTID()
    {
        return config('brightcove.brightcove.account_id');
    }

    public static function PLAYER_PROFILE()
    {
        return 'multi-platform-standard-static';
    }

    public static function GETFOLDERID()
    {
        return config('brightcove.brightcove.folder_id');
//        return '';
    }

}
