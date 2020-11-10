<?php


namespace Tychovbh\Mvc\Services\DocumentSign;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class SignRequest implements DocumentSignInterface
{

    /**
     * @var Client
     */
    private $client;

    /**
     * SignRequest constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
     * @param array $sender
     * @param array $signers
     * @param string $message
     * @return array
     */
    public function sign(string $id, array $sender, array $signers, string $message = ''): array
    {
        try {
            $response = $this->request('post', '/signrequests', [
                'from_email' => $sender['email'],
                'from_email_name' => $sender['name'],
                'document' => config('mvc-signrequest.subdomain') . '/documents/' . $id . '/',
                'signers' => $signers,
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
                'Authorization' => 'Token ' . config('mvc-signrequest.token'),
            ],
        ];

        if (!empty($params)) {
            $options['json'] = $params;
        }

        $response = $this->client->request($method, config('mvc-signrequest.subdomain') . $endpoint . '/', $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
