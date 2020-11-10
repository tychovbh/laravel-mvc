<?php


namespace Tychovbh\Mvc\Services\DocumentSign;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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
     * SignRequest constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->signers = collect([]);
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
     * Creates a Document
     * @param UploadedFile $file
     * @param string|null $webhook
     * @return array
     */
    public function create(UploadedFile $file, string $webhook = null): array
    {
        try {
            $response = $this->request('post', '/documents', [
                'file_from_content' => base64_encode($file->get()),
                'file_from_content_name' => $file->getClientOriginalName(),
                'events_callback_url' => $webhook
            ]);

            return [
                'id' => Arr::get($response, 'uuid', ''),
                'status' => Arr::get($response, 'status', '')
            ];
        } catch (\Exception $exception) {
            error('SignRequestService create error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
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
        try {
            if(!$this->signers->count()) {
                throw new Exception('At least one signer is required (See signer method)');
            }

            $response = $this->request('post', '/signrequests', [
                'from_email' => $from_email,
                'from_email_name' => $from_name,
                'document' => config('mvc-document-sign.providers.signrequest.subdomain') . '/documents/' . $id . '/',
                'signers' => $this->signers->toArray(),
                'message' => $message
            ]);

            return [
                'id' => Arr::get($response, 'uuid', '')
            ];
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
     * @return array
     */
    public function show(string $id): array
    {
        try {
            $response = $this->request('get', '/documents/' . $id);

            return [
                'id' => Arr::get($response, 'uuid', ''),
                'status' => Arr::get($response, 'status', '')
            ];
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
     * @return array
     */
    public function signShow(string $id): array
    {
        try {
            $response = $this->request('get', '/signrequests/' . $id);

            return [
                'id' => Arr::get($response, 'uuid', $id),
            ];
        } catch (\Exception $exception) {
            error('SignRequestService signShow error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    /**
     * Cancels a SignRequest
     * @param string $id
     * @return array
     */
    public function signCancel(string $id): array
    {
        try {
            $response = $this->request('post', '/signrequests/' . $id . '/cancel_signrequest');

            return [
                'cancelled' => Arr::get($response, 'cancelled', '')
            ];
        } catch(\Exception $exception) {
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
        } catch(\Exception $exception) {
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
                'Authorization' => 'Token ' . config('mvc-document-sign.providers.signrequest.token'),
            ],
        ];

        if (!empty($params)) {
            $options['json'] = $params;
        }

        $response = $this->client->request($method, config('mvc-document-sign.providers.signrequest.subdomain') . $endpoint . '/', $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
