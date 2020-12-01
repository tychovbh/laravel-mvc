<?php


namespace Tychovbh\Mvc\Services\DocumentSign;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SignRequest implements DocumentSignInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Collection
     */
    private $signers;

    /**
     * @var array
     */
    private $config;

    /**
     * SignRequest constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->signers = collect([]);
        $this->config = config('mvc-document-sign.providers.SignRequest');
    }

    /**
     * Adds a Signer to the Signers list
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @return $this
     */
    public function signer(string $email, string $firstname = '', string $lastname = ''): DocumentSignInterface
    {
        $this->signers = $this->signers->push([
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname
        ]);

        return $this;
    }

    /**
     * @param string $file
     * @param string $name
     * @param string $webhook
     * @return DocumentSign
     */
    private function createRequest(string $file, string $name, string $webhook = null): DocumentSign
    {
        try {
            $response = $this->request('post', '/documents', [
                'file_from_content' => base64_encode($file),
                'file_from_content_name' => $name,
                'events_callback_url' => $webhook
            ]);
            return new DocumentSign([
                'doc_id' => Arr::get($response, 'uuid'),
                'sign_id' => Arr::get($response, 'signrequest.uuid'),
                'status' => Arr::get($response, 'status')
            ]);
        } catch (\Exception $exception) {
            error('SignRequestService create error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    /**
     * Creates a Document
     * @param string $path
     * @param string $name
     * @param string|null $webhook
     * @return DocumentSign
     */
    public function create(string $path, string $name, string $webhook = null): DocumentSign
    {
        return $this->createRequest(file_get_contents($path), $name);
    }

    /**
     * Creates a Document from upload
     * @param UploadedFile $file
     * @param string $webhook
     * @return DocumentSign
     */
    public function createFromUpload(UploadedFile $file, string $webhook = null): DocumentSign
    {
        return $this->createRequest($file->get(), $file->getClientOriginalName(), $webhook);
    }

    /**
     * Creates a SignRequest
     * @param string $id
     * @param string $from_name
     * @param string $from_email
     * @param string $message
     * @param string $redirect_url
     * @return DocumentSign
     */
    public function sign(string $id, string $from_name, string $from_email, string $message = '', string $redirect_url = ''): DocumentSign
    {
        try {
            if (!$this->signers->count()) {
                throw new Exception('At least one signer is required (See signer method)');
            }

            $response = $this->request('post', '/signrequests', [
                'from_email' => $from_email,
                'from_email_name' => $from_name,
                'redirect_url' => $redirect_url,
                'redirect_url_declined' => $redirect_url,
                'document' => Arr::get($this->config, 'subdomain') . '/documents/' . $id . '/',
                'signers' => $this->signers->toArray(),
                'message' => $message
            ]);

            return new DocumentSign([
                'doc_id' => $id,
                'sign_id' => Arr::get($response, 'uuid')
            ]);
        } catch (\Exception $exception) {
            error('SignRequestService sign error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    /**
     * Shows a Document
     * @param string $id
     * @return DocumentSign
     */
    public function show(string $id): DocumentSign
    {
        try {
            $response = $this->request('get', '/documents/' . $id);

            return new DocumentSign([
                'id' => Arr::get($response, 'uuid'),
                'sign_id' => Arr::get($response, 'signrequest.uuid'),
                'status' => Arr::get($response, 'status')
            ]);
        } catch (\Exception $exception) {
            error('SignRequestService show error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    /**
     * Shows a SignRequest
     * @param string $id
     * @return DocumentSign
     */
    public function signShow(string $id): DocumentSign
    {
        $documentSign = new DocumentSign(['sign_id' => $id]);
        try {
            $response = $this->request('get', '/signrequests/' . $id);

            foreach (Arr::get($response, 'signers') as $signer) {
                $timestamp = null;
                if (Arr::get($signer, 'signed_on')) {
                    $timestamp = Carbon::createFromTimestamp(
                        strtotime($signer['signed_on']),
                        'Europe/Amsterdam'
                    )
                        ->format('Y-m-d H:i:s');
                }

                $documentSign->signer(
                    Arr::get($signer, 'email'),
                    $timestamp,
                    Arr::get($signer, 'needs_to_sign')
                );
            }

            return $documentSign;
        } catch (\Exception $exception) {
            error('SignRequestService signShow error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }

        return $documentSign;
    }

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return DocumentSign
     */
    public function signCancel(string $id): DocumentSign
    {
        try {
            $response = $this->request('post', '/signrequests/' . $id . '/cancel_signrequest');

            return new DocumentSign([
                'cancelled' => Arr::get($response, 'cancelled')
            ]);
        } catch (\Exception $exception) {
            error('SignRequestService signCancel error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    /**
     * Deletes a Document
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        try {
            $this->request('delete', '/documents/' . $id);

            return true;
        } catch (\Exception $exception) {
            error('SignRequestService destroy error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);

            return false;
        }
    }

    /**
     * Does request to service
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    private function request(string $method, string $endpoint, array $params = []): array
    {
        $options = [
            'headers' => [
                'Authorization' => 'Token ' . Arr::get($this->config, 'token'),
            ],
        ];

        if (!empty($params)) {
            $options['json'] = $params;
        }

        $response = $this->client->request($method, Arr::get($this->config, 'subdomain') . $endpoint . '/', $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
