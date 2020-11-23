<?php
namespace Tychovbh\Mvc\Services\DocumentSign;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class DocuSign implements DocumentSignInterface
{
    /**
     * Adds a Signer to the Signers list
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @return $this
     */
    public function signer(string $email, string $firstname = '', string $lastname = ''): DocumentSignInterface
    {
        // TODO: Implement signer() method.
    }

    /**
     * Creates a Document
     * @param string $path
     * @param string $name
     * @return array
     */
    public function create(string $path, string $name): array
    {
        // TODO: Implement create() method.
    }

    /**
     * Creates a Document
     * @param UploadedFile $file
     * @param string|null $webhook
     * @return array
     */
    public function createFromUpload(UploadedFile $file, string $webhook = null): array
    {
        // TODO: Implement createFromUpload() method.
    }

    /**
     * Creates a SignRequest
     * @param string $id
     * @param string $from_name
     * @param string $from_email
     * @param string $message
     * @return array
     */
    public function sign(string $id, string $from_name, string $from_email, string $message = ''): array
    {
        // TODO: Implement sign() method.
    }

    /**
     * Shows a Document
     * @param string $id
     * @return array
     */
    public function show(string $id): array
    {
        // TODO: Implement show() method.
    }

    /**
     * Shows a SignRequest
     * @param string $id
     * @return array
     */
    public function signShow(string $id): array
    {
        // TODO: Implement signShow() method.
    }

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return array
     */
    public function signCancel(string $id): array
    {
        // TODO: Implement signCancel() method.
    }

    /**
     * Deletes a Document
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        // TODO: Implement destroy() method.
    }

}
