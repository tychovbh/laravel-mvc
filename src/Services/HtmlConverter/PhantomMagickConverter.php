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
        if (!$this->isEnabled()) {
            return $this;
        }

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
        if (!$this->isEnabled()) {
            return false;
        }

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
            $converter->save(storage_path($path));
            return true;
        } catch (\Exception $exception) {
            error('PhantomMagickConverter save error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
            return false;
        }
    }

    /**
     * Checks if service is enabled
     * @return bool
     */
    private function isEnabled(): bool
    {
        if (config('mvc-html-converter.enabled') === true) {
            return true;
        } else {
            return false;
        }
    }
}
