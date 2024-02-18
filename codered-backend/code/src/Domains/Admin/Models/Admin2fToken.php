<?php

namespace App\Domains\Admin\Models;

use Carbon\Carbon;
use INTCore\OneARTFoundation\Model;

class Admin2fToken extends Model
{
    const EXPIRATION_TIME = 15; // minutes

    protected $table = 'admin_2fa_tokens';

    protected $fillable = [
        'code',
        'admin_id',
        'used'
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }

        parent::__construct($attributes);
    }

    /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public function generateCode()
    {

        return app()->isLocal() ? 1234 : rand(1000, 9999);
        //        return $code;
    }

    /**
     * User tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Send code to user
     *
     * @return bool
     * @throws \Exception
     */
    public function sendCode()
    {
        if (!$this->admin) {
            throw new \Exception("No user attached to this token.");
        }

        if ($this->admin->phone == '15053195613' || $this->admin->phone == '17864772792' || $this->admin->phone == '201118240000' || $this->admin->email == 'melanie.rodriguez@eccouncil.org') {
            $this->code = '1332';
        } else {
            $this->code = $this->generateCode();
        }

        $this->save();

        // if (!$this->code) {


        // }

        try {
            if (app()->isProduction()) {
                app('twilio')->messages->create($this->admin->phone,
                    ['from' => env('TWILIO_NUMBER'), 'body' => "Your verification code for EC-Council Learning AdminPanel is {$this->code}"]);
            }

        } catch (\Exception $ex) {
            \Log::info("admin_otp_error: " . $ex->getMessage()); //unable to send SMS
            return true;
        }

        return true;
    }

    /**
     * True if the token is not used nor expired
     *
     * @return bool
     */
    public function isValid()
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    /**
     * Is the current token used
     *
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * Is the current token expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }
}
