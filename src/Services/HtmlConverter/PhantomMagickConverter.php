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
     * @param string $html
     * @return HtmlConverterInterface
     */
    public function page(string $html): HtmlConverterInterface
    {
        $this->pages = $this->pages->push($html);

        return $this;
    }

    /**
     * @param string $path
     * @param string $type
     * @return bool
     */
    public function save(string $path, string $type = 'pdf'): bool
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
}
