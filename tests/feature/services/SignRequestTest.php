<?php


namespace Tychovbh\Tests\Mvc\feature\services;


use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\Document\SignRequest;
use Tychovbh\Tests\Mvc\TestCase;

class SignRequestTest extends TestCase
{
    private function signRequest()
    {
        $guzzle = new Client();
        return new SignRequest($guzzle);
    }

    /**
     * @test
     */
    public function itCanCreate()
    {
        Storage::fake('photos');
        $file = UploadedFile::fake()->image('photo1.jpg');
        $document = $this->signRequest()->create($file);
        $this->assertTrue(Arr::has($document, 'id'));
    }

    /**
     * @test
     */
    public function itCanSign()
    {

    }

    /**
     * @test
     */
    public function itCanShow()
    {

    }

    /**
     * @test
     */
    public function itCanShowSignedStatus()
    {

    }
}
