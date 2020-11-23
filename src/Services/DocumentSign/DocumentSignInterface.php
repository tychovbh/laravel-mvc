<?php

namespace Tychovbh\Mvc\Services\DocumentSign;

use Illuminate\Http\UploadedFile;

interface DocumentSignInterface
{
    /**
     * Adds a Signer to the Signers list
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @return $this
     */
    public function signer(string $email, string $firstname = '', string $lastname = ''): DocumentSignInterface;

    /**
     * Creates a Document
     * @param string $path
     * @param string $name
     * @param string|null $webhook
     * @return array
     */
    public function create(string $path, string $name, string $webhook = null): array;

    /**
     * Creates a Document
     * @param UploadedFile $file
     * @param string|null $webhook
     * @return array
     */
    public function createFromUpload(UploadedFile $file, string $webhook = null): array;

    /**
     * Creates a SignRequest
     * @param string $id
     * @param string $from_name
     * @param string $from_email
     * @param string $message
     * @return array
     */
    public function sign(string $id, string $from_name, string $from_email, string $message = ''): array;

    /**
     * Shows a Document
     * @param string $id
     * @return array
     */
    public function show(string $id): array;

    /**
     * Shows a SignRequest
     * @param string $id
     * @return array
     */
    public function signShow(string $id): array;

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return array
     */
    public function signCancel(string $id): array;

    /**
     * Deletes a Document
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool;
}
