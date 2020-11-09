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
        $this->assertTrue($document['status'] === 'co');
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
        $id = '5ca5e0f4-b413-49eb-aa46-bc6ddce20ad4';
        $document = $this->signRequest()->show($id);
        $this->assertTrue(Arr::has($document, 'id'));
    }

    /**
     * @test
     */
    public function itCanShowSignedStatus()
    {

    }
}
