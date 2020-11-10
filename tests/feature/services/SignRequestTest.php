<?php


namespace Tychovbh\Tests\Mvc\feature\services;


use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\DocumentSign\SignRequest;
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
        $file = UploadedFile::fake()->image('photo2.jpg');
        $document = $this->signRequest()->create($file);
        $this->assertTrue(Arr::has($document, 'id'));
        $this->assertTrue($document['status'] === 'co');

        return $document;
    }

    /**
     * @test
     * @depends itCanCreate
     * @param array $document
     */
    public function itCanShow(array $document)
    {
        $show = $this->signRequest()->show($document['id']);
        $this->assertTrue(Arr::has($show, 'id'));

        return $document;
    }

    /**
     * @test
     * @depends itCanShow
     * @param array $document
     */
    public function itCanSign(array $document)
    {
        $sign = $this->signRequest()
            ->signer('bixit94918@x1post.com')
            ->sign($document['id'], 'Rentbay', 'noreply@rentbay.nl');
        $this->assertTrue(Arr::has($sign, 'id'));

        return $sign;
    }

    /**
     * @test
     * @depends itCanSign
     * @param array $sign
     */
    public function itCanShowSign(array $sign)
    {
        $sign = $this->signRequest()->signShow($sign['id']);
        $this->assertTrue(Arr::has($sign, 'id'));
    }

    /**
     * @test
     * @depends itCanSign
     * @param array $sign
     */
    public function itCanCancelSign(array $sign)
    {
        $sign = $this->signRequest()->signCancel($sign['id']);
        $this->assertTrue($sign['cancelled'] === true);
    }

    /**
     * @test
     * @depends itCanCreate
     * @param array $document
     */
    public function itCanDestroy(array $document)
    {
        $destroyed = $this->signRequest()->destroy($document['id']);
        $this->assertTrue($destroyed);
    }
}
