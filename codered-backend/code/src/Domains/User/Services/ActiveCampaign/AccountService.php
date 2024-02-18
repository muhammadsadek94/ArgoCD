<?php

namespace App\Domains\User\Services\ActiveCampaign;

use App\Domains\User\Models\ActiveCampaignAccount;
use Log;

/*
 * @see ActiveCampaignService
 */
trait AccountService
{

    /**
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string      $email
     * @param string|null $phone
     * @param string|null $source
     * @param string|null $user_tags
     * @param string|null $country_id
     * @param null        $utm_data
     * @return ActiveCampaignAccount|null
     */
    public function createContact(?string $first_name, ?string $last_name, string $email, ?string $phone = null,
        ?string $source = 'Codered', ?string $user_tags = null, string $country_id = null,
        $utm_data = null): ?ActiveCampaignAccount
    {
        /** @var ActiveCampaignAccount $get_contact */
        $get_contact = ActiveCampaignAccount::where('email', $email)->first();
        if ($get_contact) return $get_contact;
        $data = [
            "email"                 => $email,
            "first_name"            => $first_name,
            "last_name"             => $last_name,
            "phone"                 => $phone,
            'field[%COUNTRY%]'      => $country_id,
            "tags"                  => $user_tags,
            'field[%SOURCE%]'       => $source,
            'field[%CUSTOMTAGS%]'   => $user_tags,
            'field[%UTMCAMPAIGNS%]' => $utm_data?->utm_campaign,
            'field[%UTMCONTENT%]'   => $utm_data?->utm_content,
            'field[%UTMMEDIUM%]'    => $utm_data?->utm_medium,
            'field[%UTMSOURCE%]'    => $utm_data?->utm_source,
            'field[%UTMTERM%]'      => $utm_data?->utm_term,

        ];

        $response = $this->ac->api("contact/add", $data);
        try {
            Log::info("ActiveCampaignAccount created with account_id: " . json_encode($response));
            $account_id = $response->success == 1 ? $response->subscriber_id : ($response->success == 0 ? $response->{'0'}->id : 'ERROR');
            return ActiveCampaignAccount::firstOrCreate([
                'email'      => $email,
                'account_id' => $account_id
            ]);
            Log::info("ActiveCampaignAccount created with account_id: " . $account_id);

        } catch (\Exception $e) {
            Log::error("active campaign error because :" . collect($response)->toJson() . "failure error: " . $e->getMessage());
        }
        //occur incase validate invalid email from activecampaign
        return null;


    }

    /**
     * @param             $id
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string      $email
     * @param string|null $phone
     * @param string|null $user_tags
     * @param string|null $interested_categories
     * @param string|null $interested_tags
     * @param string|null $experience
     * @param string|null $user_goal
     * @param string      $source
     * @return ActiveCampaignAccount
     */
    public function editContact($id, ?string $first_name, ?string $last_name, string $email, ?string $phone = null,
        ?string $user_tags = null, ?string $interested_categories = null, ?string $interested_tags = null,
        ?string $experience = null, ?string $user_goal = null, $source = 'codered', ?string $country = null): ?ActiveCampaignAccount
    {


        /** @var ActiveCampaignAccount $account */
        $account = ActiveCampaignAccount::where('account_id', $id)->first();
        if (empty($account)) {
            $account = $this->createContact($first_name, $last_name, $email, $phone, $source);
            Log::info("ACCOUNT :" . collect($account)->toJson());
            if (empty($account)) return null;
        }

        Log::info("TAGS :" . $user_tags);

        $data = [
            "p[6]"                           => 6, //TO Select form all lists (if this removed will not working)
            'id'                             => $id,
            // inputs to be updated
            "email"                          => $email,
            "first_name"                     => $first_name,
            "last_name"                      => $last_name,
            "phone"                          => $phone,
            'field[%CUSTOMTAGS%]'            => $user_tags,
            'field[%INTERESTED_CATEGORIES%]' => $interested_categories,
            'field[%COUNTRY%]'               => $country,
            "tags"                           => $user_tags,
            'field[%INTERSTED_TAGS%]'        => $interested_tags,
            'field[%EXPERINCE%]'             => $experience,
            'field[%USER_GOALS%]'            => $user_goal,

        ];

        try {
            $response = $this->ac->api("contact/edit", $data);
            Log::info("RESPONSE :" . collect($response)->toJson());
            $account->update([
                'email' => $email,
            ]);
        } catch (\Exception $e) {
            Log::error("active campaign error because :" . collect($response)->toJson());
        }

        return $account;

    }

    /**
     * @param        $active_campaign_id
     * @param string $course_name
     * @return ActiveCampaignAccount
     */
    public function updateUserCourseName($active_campaign_id, string $course_name)
    {

        /** @var ActiveCampaignAccount $account */
        $account = ActiveCampaignAccount::where('account_id', $active_campaign_id)->first();

        if (empty($account)) return;

        $data = [
            "p[1]"                 => 1, //TO Select form all lists (if this removed will not working)
            'id'                   => $active_campaign_id,
            'field[%COURSE_NAME%]' => $course_name,
        ];

        try {
            $response = $this->ac->api("contact/edit", $data);
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $account;

    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection|void
     * @deprecated
     * @deprecated
     */
    public function deleteContact($id)
    {
        return;
        $params = "id={$id}";

        //        ActiveCampaignAccount::where('account_id', $id)->delete();

        return $this->ac->api("contact/delete?{$params}");
    }

    public function findContactOffline($email)
    {
        return ActiveCampaignAccount::where('email', $email)->first();
    }

}
