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
     * @return DocumentSign
     */
    public function create(string $path, string $name, string $webhook = null): DocumentSign;

    /**
     * Creates a Document
     * @param UploadedFile $file
     * @param string|null $webhook
     * @return DocumentSign
     */
    public function createFromUpload(UploadedFile $file, string $webhook = null): DocumentSign;

    /**
     * Creates a SignRequest
     * @param string $id
     * @param string $from_name
     * @param string $from_email
     * @param string $message
     * @param string $redirect_url
     * @return DocumentSign
     */
    public function sign(string $id, string $from_name, string $from_email, string $message = '', string $redirect_url = ''): DocumentSign;

    /**
     * Shows a Document
     * @param string $id
     * @return DocumentSign
     */
    public function show(string $id): DocumentSign;

    /**
     * Shows a SignRequest
     * @param string $id
     * @return DocumentSign
     */
    public function signShow(string $id): DocumentSign;

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return DocumentSign
     */
    public function signCancel(string $id): DocumentSign;

    /**
     * Deletes a Document
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool;
}
