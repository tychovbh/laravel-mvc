<?php

namespace Tychovbh\Mvc\Services\DocumentSign;

use Illuminate\Http\UploadedFile;

interface DocumentSignInterface
{
    /**
     * @param UploadedFile $file
     * @param string $webhook
     * @return mixed
     */
    public function create(UploadedFile $file, string $webhook = null);

    /**
     * @param string $id
     * @param array $sender
     * @param array $recipients
     * @param string $message
     * @return mixed
     */
    public function sign(string $id, array $sender, array $recipients, string $message = '');

    /**
     * @param string $id
     * @return mixed
     */
    public function show(string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function signShow(string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function signCancel(string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function destroy(string $id);
}
