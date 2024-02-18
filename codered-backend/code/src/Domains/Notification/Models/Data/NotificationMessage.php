<?php


namespace App\Domains\Notification\Models\Data;


class NotificationMessage
{
    private $language = 'en';

    private $message = ['en' => [self::TITLE => null, self::BODY => null],
                        'ar' => [self::TITLE => null, self::BODY => null]];

    const TITLE = 'title';
    const BODY = 'body';


    public function getMessage($language = 'en')
    {
        $this->setLanguage($language);
        return [self::TITLE => $this->getKey(self::TITLE), self::BODY => $this->getKey(self::BODY)];
    }

    public function getKey($key)
    {
        return $this->message[$this->language][$key];
    }

    public function setLanguage($language = 'en')
    {
        $this->language = $language;
    }

    public function setTitle($message, $language = 'en')
    {
        $this->message[$language][self::TITLE] = $message;
    }

    public function setBody($message, $language = 'en')
    {
        $this->message[$language][self::BODY] = $message;
    }


}
