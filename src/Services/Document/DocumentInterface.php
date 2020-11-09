<?php

namespace Tychovbh\Mvc\Services\Document;

use Illuminate\Http\UploadedFile;

interface DocumentInterface
{
    /**
     * @param UploadedFile $file
     * @param string $webhook
     * @return mixed
     */
    public function create(UploadedFile $file, string $webhook = null);

    /**
     * @param string $id
     * @param string $sender
     * @param string $recipients
     * @param string $message
     * @return mixed
     */
    public function sign(string $id, string $sender, string $recipients, string $message = '');

    /**
     * @param string $id
     * @return mixed
     */
    public function show(string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function signedStatus(string $id);
}
