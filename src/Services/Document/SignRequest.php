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

            return $this->request('post', '/documents/', [
                'file_from_content' => base64_encode($contents),
                'file_from_content_name' => $file->getClientOriginalName(),
            ]);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
    }

    public function sign(string $id, string $sender, string $recipients, string $message = '')
    {
        // TODO: Implement sign() method.
    }

    public function show(string $id)
    {
        try {
            return $this->request('get', '/documents/' . $id . '/');
        } catch(\Exception $exception) {
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

        $response = $this->client->request($method, config('mvc-signrequest.subdomain') . $endpoint, $options);

        $data = json_decode($response->getBody(), true);

        return [
            'id' => $data['uuid'],
            'status' => $data['status']
        ];
    }
}
