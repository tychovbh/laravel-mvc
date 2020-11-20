<?php
namespace Tychovbh\Mvc\Services\DocumentSign;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class DocuSign implements DocumentSignInterface
{
    public function signer(string $email, string $firstname = '', string $lastname = ''): DocumentSignInterface
    {
        // TODO: Implement signer() method.
    }

    public function create(string $path, string $name): array
    {
        // TODO: Implement create() method.
    }

    public function createFromUpload(UploadedFile $file, string $webhook = null): array
    {
        // TODO: Implement createFromUpload() method.
    }

    public function sign(string $id, string $from_name, string $from_email, string $message = ''): array
    {
        // TODO: Implement sign() method.
    }

    public function show(string $id): array
    {
        // TODO: Implement show() method.
    }

    public function signShow(string $id): array
    {
        // TODO: Implement signShow() method.
    }

    public function signCancel(string $id): array
    {
        // TODO: Implement signCancel() method.
    }

    public function destroy(string $id): bool
    {
        // TODO: Implement destroy() method.
    }

}
