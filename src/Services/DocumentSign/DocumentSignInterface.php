<?php

namespace Tychovbh\Mvc\Services\DocumentSign;

use Illuminate\Http\UploadedFile;

interface DocumentSignInterface
{
    /**
     * Creates a Document
     * @param UploadedFile $file
     * @param string $webhook
     * @return mixed
     */
    public function create(UploadedFile $file, string $webhook = null);

    /**
     * Creates a SignRequest
     * @param string $id
     * @param array $sender
     * @param array $recipients
     * @param string $message
     * @return mixed
     */
    public function sign(string $id, array $sender, array $recipients, string $message = '');

    /**
     * Shows a Document
     * @param string $id
     * @return mixed
     */
    public function show(string $id);

    /**
     * Shows a SignRequest
     * @param string $id
     * @return mixed
     */
    public function signShow(string $id);

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return mixed
     */
    public function signCancel(string $id);

    /**
     * Deletes a Document
     * @param string $id
     * @return mixed
     */
    public function destroy(string $id);
}
