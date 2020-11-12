<?php

namespace Tychovbh\Tests\Mvc\feature\services;

use Illuminate\Support\Facades\Storage;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverter;
use Tychovbh\Tests\Mvc\TestCase;

class HtmlConverterTest extends TestCase
{
    /**
     * @test
     */
    public function itCanAddPage()
    {
        $htmlConverter = new HtmlConverter();
        $html = '<html lang="nl"><h1>test</h1></html>';
        $response = $htmlConverter->page($html);
        $this->assertTrue($response->pages[0] === $html);
    }

    /**
     * @test
     */
    public function itCanSave()
    {
        $htmlConverter = new HtmlConverter();
        $htmlConverter->page('<html lang="nl"><h1>pdf</h1></html>')
            ->save(storage_path('documents/file.pdf'));
        $this->assertFileExists(storage_path('documents/file.pdf'));
    }

    /**
     * @test
     */
    public function itCanSavePng()
    {
        $htmlConverter = new HtmlConverter();
        $type = 'png';
        $htmlConverter->page('<html lang="nl"><h1>' . $type . '</h1></html>')
            ->save(storage_path('documents/file.' . $type), $type);
        $this->assertFileExists(storage_path('documents/file.' . $type));
    }

    /**
     * @test
     */
    public function itCanSaveJpg()
    {
        $htmlConverter = new HtmlConverter();
        $type = 'jpg';
        $htmlConverter->page('<html lang="nl"><h1>' . $type .'</h1></html>')
            ->save(storage_path('documents/file.' . $type), $type);
        $this->assertFileExists(storage_path('documents/file.' . $type));
    }
}
