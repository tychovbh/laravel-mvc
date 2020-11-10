<?php


namespace Tychovbh\Mvc\Services\Document;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class SignRequest implements DocumentInterface
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
            ]);

            return [
                'id' => $response['uuid'],
                'status' => $response['status']
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
                'id' => $response['uuid'],
                'status' => $response['status']
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
                'id' => $response['uuid'],
                'status' => $response['status']
            ];
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
    }

    public function signedStatus(string $id)
    {
        // TODO: Implement signedStatus() method.
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

        return json_decode($response->getBody(), true);
    }
}
