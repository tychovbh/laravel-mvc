<?php

namespace Tychovbh\Tests\Mvc\feature\services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\DocumentSign\SignRequest;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverter;
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
        $path = __DIR__ . '/SignRequestTestPdf.pdf';
        $document = $this->signRequest()->create($path, 'SignRequestTestPdf.pdf');

        $this->assertTrue(Arr::has($document, 'id'));
        $this->assertTrue($document['status'] === 'co');

        return $document;
    }

    /**
     * @test
     */
    public function itCanCreateFromUpload()
    {
        Storage::fake();
        $file = UploadedFile::fake()->image('contract.jpg');
        $document = $this->signRequest()->createFromUpload($file);
        $this->assertTrue(Arr::has($document, 'id'));
        $this->assertTrue($document['status'] === 'co');

        return $document;
    }

    /**
     * @test
     * @depends itCanCreateFromUpload
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
     * @depends itCanCreate
     * @param array $document
     */
    public function itCanSign(array $document)
    {
        $sign = $this->signRequest()
            ->signer('sanad48180@ummoh.com')
            ->sign($document['id'], 'Rentbay', 'noreply@rentbay.nl');
        $this->assertTrue(Arr::has($sign, 'id'));

        $sign['document_id'] = $document['id'];
        return $sign;
    }

    /**
     * @test
     * @depends itCanSign
     * @param array $sign
     */
    public function itCanVerifyIfDocumentIsSigned(array $sign)
    {
        $this->markTestSkipped('TO MAKE THIS TEST WORK YOU WILL HAVE TO GO TO itCanSign TEST AND CHANGE THE SIGNER EMAIL TO YOUR EMAIL, AND THEN SIGN THE DOCUMENT');

        $document = $this->signRequest()->show($sign['document_id']);

        $this->assertTrue($document['status'] === 'si');
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
