<?php

namespace Tychovbh\Mvc\Mail;

use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Support\Arr;
use Swift_Message;

class Mailable extends BaseMailable
{
    /**
     * @var array
     */
    public $mail;

    /**
     * Override view to add headers
     * @param string $view
     * @param array $data
     * @return $this
     */
    public function view($view, array $data = [])
    {
        $view = parent::view($view, $data);

        $headers = array_merge(config('mail.headers', []), Arr::get($this->mail, 'headers', []));

        if (!$headers) {
            return $this;
        }

        $view->withSwiftMessage(function (Swift_Message $message) use ($headers) {
            $message->getHeaders()->addTextHeader(
                'X-SMTPAPI', json_encode($headers)
            );
        });

        return $this;
    }
}
