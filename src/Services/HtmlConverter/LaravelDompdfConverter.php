<?php

namespace Tychovbh\Mvc\Services\HtmlConverter;

use  Barryvdh\DomPDF\Facade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class LaravelDompdfConverter implements HtmlConverterInterface
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
     * @return bool
     */
    public function save(string $path, string $type = 'pdf'): bool
    {
        return $this->convert('save', storage_path($path), $type);
    }

    /**
     * Downloads all the pages in browser
     * @param string $path
     * @param string $type
     * @param bool $force
     * @return bool
     */
    public function download(string $path, string $type = 'pdf', bool $force = false): bool
    {
        return $this->convert($force ? 'stream' : 'download', $path, $type, $force);
    }

    /**
     * Convert HTML
     * @param string $method
     * @param string $path
     * @param string $type
     * @param bool $force
     * @return bool
     */
    private function convert(string $method, string $path, string $type = 'pdf', bool $force = false): bool
    {
        try {
            $html = '';
            foreach ($this->pages as $key => $page) {
                if ($key > 0) {
                    $html .= '<div style="page-break-after: always"></div>';
                }

                $html .= $page;
            }

            $converter = App::make('dompdf.wrapper');
            $converter->loadHTML($html);
            if ($force) {
                $converter->{$method}();
            } else {
                $converter->{$method}($path);
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
