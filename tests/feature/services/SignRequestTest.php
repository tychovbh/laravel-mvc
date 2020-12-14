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

        $this->assertTrue(!empty($document->id));
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
        $this->assertTrue(!empty($document->id));
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
        $this->signRequest()->show($document->id);
        $this->assertTrue(!empty($document->id));
    }

    /**
     * @test
     * @depends itCanCreate
     * @param DocumentSign $document
     * @return DocumentSign
     */
    public function itCanSign(DocumentSign $document)
    {
        $document = $this->signRequest()
            ->signer('your@email.com')
            ->sign($document->id, 'Rentbay', 'noreply@rentbay.nl');

        $this->assertTrue(!empty($document->sign_id));

        return $document;
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $document
     */
    public function itCanVerifyIfDocumentIsSigned(DocumentSign $document)
    {
        $this->markTestSkipped('TO MAKE THIS TEST WORK YOU WILL HAVE TO GO TO itCanSign TEST AND CHANGE THE SIGNER EMAIL TO YOUR EMAIL, AND THEN SIGN THE DOCUMENT');

        $document = $this->signRequest()->show($document->id);

        $this->assertTrue($document->status === 'si');
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $document
     */
    public function itCanShowSign(DocumentSign $document)
    {
        $document = $this->signRequest()->signShow($document->sign_id);
        $this->assertTrue(!empty($document->sign_id));
    }

    /**
     * @test
     * @depends itCanSign
     * @param DocumentSign $document
     */
    public function itCanCancelSign(DocumentSign $document)
    {
        $document = $this->signRequest()->signCancel($document->sign_id);
        $this->assertTrue($document->cancelled === true);
    }

    /**
     * @test
     * @depends itCanCreate
     * @param DocumentSign $document
     */
    public function itCanDestroy(DocumentSign $document)
    {
        $destroyed = $this->signRequest()->destroy($document->id);
        $this->assertTrue($destroyed);
    }
}
