<?php

namespace Tychovbh\Tests\Mvc\feature\services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSign;
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
        $path = __DIR__ . '/SignRequestTestPdf.pdf';
        $document = $this->signRequest()->create($path, 'SignRequestTestPdf.pdf');

        $this->assertTrue(!empty($document->doc_id));
        $this->assertTrue($document->status === 'co');

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
        $this->assertTrue(!empty($document->doc_id));
        $this->assertTrue($document->status === 'co');

        return $document;
    }

    /**
     * @test
     * @depends itCanCreateFromUpload
     * @param DocumentSign $document
     */
    public function itCanShow(DocumentSign $document)
    {
        $this->signRequest()->show($document->doc_id);
        $this->assertTrue(!empty($document->doc_id));
    }

    /**
     * @test
     * @depends itCanCreate
     * @param DocumentSign $document
     * @return DocumentSign
     */
    public function itCanSign(DocumentSign $document)
    {
        $sign = $this->signRequest()
            ->signer('thvrijn2002@gmail.com')
            ->sign($document->doc_id, 'Rentbay', 'noreply@rentbay.nl');

        $this->assertTrue(!empty($sign->sign_id));

        return $sign;
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $sign
     */
    public function itCanVerifyIfDocumentIsSigned(DocumentSign $sign)
    {
        $this->markTestSkipped('TO MAKE THIS TEST WORK YOU WILL HAVE TO GO TO itCanSign TEST AND CHANGE THE SIGNER EMAIL TO YOUR EMAIL, AND THEN SIGN THE DOCUMENT');

        $document = $this->signRequest()->show($sign->doc_id);

        $this->assertTrue($document->status === 'si');
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $sign
     */
    public function itCanShowSign(DocumentSign $sign)
    {
        $sign = $this->signRequest()->signShow($sign->sign_id);
        $this->assertTrue(!empty($sign->sign_id));
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $sign
     */
    public function itCanCancelSign(DocumentSign $sign)
    {
        $sign = $this->signRequest()->signCancel($sign->sign_id);
        $this->assertTrue($sign->cancelled === true);
    }

    /**
     * @test
     * @depends itCanCreate
     * @param DocumentSign $document
     */
    public function itCanDestroy(DocumentSign $document)
    {
        $destroyed = $this->signRequest()->destroy($document->doc_id);
        $this->assertTrue($destroyed);
    }
}
