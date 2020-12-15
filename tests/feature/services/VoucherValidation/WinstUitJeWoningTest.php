<?php


namespace Tychovbh\Tests\Mvc\feature\services\VoucherValidation;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\VoucherValidation\WinstUitJeWoning;
use Tychovbh\Tests\Mvc\TestCase;

class WinstUitJeWoningTest extends TestCase
{
    public function winstUitJeWoning()
    {
        $guzzle = new Client();
        return new WinstUitJeWoning($guzzle);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function itCanValidate()
    {
        $voucher = $this->winstUitJeWoning()->validate('107-614-068-581', [
            'zipcodeNumber' => 1022,
            'zipcodeLetter' => 'LA',
            'addressNumber' => 17
        ]);

        $this->assertTrue($voucher['error'] === false);

        return $voucher['data'];
    }

    /**
     * @test
     * @param array $voucher
     * @throws GuzzleException
     */
    public function itCanUse()
    {
        $file = __DIR__ . '/receiptFile.pdf';
//        $zipcode = str_split($voucher['addressZipcode'], 4);
        $voucher = $this->winstUitJeWoning()->use('105-063-775-512', [
            'amount0' => 9740,
            'description0' => 'EPS plaat',
            'email' => 'wvanwanrooij@nextfactory.nl',
            'firstName' => 'Ward',
            'group0' => 6,
            'ipAddress' => '80.112.56.220',
            'lastName' => 'van Wanrooij',
            'phone' => '0652044111',
            'quantity0' => 1,
            'receiptNumber' => 2,
            'storeId' => 8,
            'storeName' => 'WoonWijzerWebshop',
            'token' => '89a36175-b23e-4f2b-b223-f3a7d5bcd26a',
            'zipcodeLetter' => 'LA',
            'zipcodeNumber' => 1022,
            'purchaseDate' => 1600419256000,
            'receiptFile' => fopen($file, 'r'),
            'routeE' => 1,
            'optin' => 1,
            'agree' => 1,
            'addressNumber' => 19
        ]);

        $this->assertFalse($voucher['error']);
    }
}
