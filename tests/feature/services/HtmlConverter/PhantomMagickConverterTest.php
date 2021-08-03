<?php

namespace Tychovbh\Tests\Mvc\feature\services\HtmlConverter;

use Tychovbh\Mvc\Services\HtmlConverter\PhantomMagickConverter;
use Tychovbh\Tests\Mvc\TestCase;

class PhantomMagickConverterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanAddPage()
    {
        $htmlConverter = new PhantomMagickConverter();
        $html = '<html lang="en"><h1>test</h1></html>';
        $response = $htmlConverter->page($html);
        $this->assertTrue($response->pages[0] === $html);
    }

    /**
     * @test
     */
    public function itCanSave()
    {
        $htmlConverter = new PhantomMagickConverter();
        $htmlConverter->page('<html lang="nl"><h1>pdf</h1></html>')
            ->save('documents/file.pdf');
        $this->assertFileExists(storage_path('documents/file.pdf'));
    }

    /**
     * @test
     */
    public function itCanSavePng()
    {
        $htmlConverter = new PhantomMagickConverter();
        $type = 'png';
        $htmlConverter->page('<html lang="nl"><h1>' . $type . '</h1></html>')
            ->save('documents/file.' . $type, $type);
        $this->assertFileExists(storage_path('documents/file.' . $type));
    }

    /**
     * @test
     */
    public function itCanSaveJpg()
    {
        $htmlConverter = new PhantomMagickConverter();
        $type = 'jpg';
        $htmlConverter->page('<html lang="nl"><h1>' . $type .'</h1></html>')
            ->save('documents/file.' . $type, $type);
        $this->assertFileExists(storage_path('documents/file.' . $type));
    }
}
