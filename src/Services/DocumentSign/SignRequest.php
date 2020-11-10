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

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(UploadedFile $file, string $webhook = null)
    {
        try {
            $contents = $file->get();

            $response = $this->request('post', '/documents', [
                'file_from_content' => base64_encode($contents),
                'file_from_content_name' => $file->getClientOriginalName(),
                'events_callback_url' => $webhook
            ]);

            return [
                'id' => Arr::get($response, 'uuid', ''),
                'status' => Arr::get($response, 'status', '')
            ];
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
    }

    public function sign(string $id, array $sender, array $signers, string $message = '')
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
            $message = $exception->getMessage();
        }
    }

    public function show(string $id)
    {
        try {
            $response = $this->request('get', '/documents/' . $id);

            return [
                'id' => Arr::get($response, 'uuid', ''),
                'status' => Arr::get($response, 'status', '')
            ];
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
    }

    public function signShow(string $id)
    {
        try {
            $response = $this->request('get', '/signrequests/' . $id);

            return [
                'id' => Arr::get($response, 'uuid', $id),
            ];
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
    }

    public function signCancel(string $id)
    {
        try {
            $response = $this->request('post', '/signrequests/' . $id . '/cancel_signrequest');

            return [
                'cancelled' => Arr::get($response, 'cancelled', '')
            ];
        } catch(\Exception $exception) {
            $message = $exception->getMessage();
        }
    }


    public function destroy(string $id)
    {
        try {
            $response  = $this->request('delete', '/documents/' . $id);

            return true;
        } catch(\Exception $exception) {
            return false;
        }
    }

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
