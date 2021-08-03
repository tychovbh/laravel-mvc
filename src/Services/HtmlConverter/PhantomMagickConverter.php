<?php

namespace Tychovbh\Mvc\Services\HtmlConverter;

use Illuminate\Support\Collection;
use Anam\PhantomMagick\Converter;

class PhantomMagickConverter implements HtmlConverterInterface
{
    /**
     * @var Collection
     */
    public $pages;

    /**
     * PhantomMagickConverter constructor.
     */
    public function __construct()
    {
        $this->pages = collect([]);
    }

    /**
     * Adds a page to pages
     * @param string $html
     * @return HtmlConverterInterface
     */
    public function page(string $html): HtmlConverterInterface
    {
        $this->pages = $this->pages->push($html);

        return $this;
    }

    /**
     * Converts all the pages to specified type
     * @param string $path
     * @param string $type
     * @return mixed
     */
    public function save(string $path, string $type = 'pdf'): mixed
    {
        return $this->convert('save', storage_path($path), $type);
    }

    /**
     * Downloads all the pages in browser
     * @param string $path
     * @param string $type
     * @param bool $force
     * @return mixed
     */
    public function download(string $path, string $type = 'pdf', bool $force = false): mixed
    {
        return $this->convert('download', $path, $type, $force);
    }

    /**
     * Convert HTML
     * @param string $method
     * @param string $path
     * @param string $type
     * @param bool $force
     * @return mixed
     */
    private function convert(string $method, string $path, string $type = 'pdf', bool $force = false): mixed
    {
        try {
            $converter = new Converter();
            foreach ($this->pages as $page) {
                $converter->addPage($page);
            }
            switch ($type) {
                case 'pdf':
                    $converter->toPdf();
                    break;
                case 'png':
                    $converter->toPng();
                    break;
                case 'jpg':
                    $converter->toJpg();
                    break;
            }

            if ($force) {
                $converter->{$method}($path, $force);
            } else {
                $converter->{$method}($force);
            }
            $this->pages = collect([]);
            return true;
        } catch (\Exception $exception) {
            error('PhantomMagickConverter save error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
            return false;
        }
    }

}
