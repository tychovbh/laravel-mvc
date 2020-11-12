<?php

namespace Tychovbh\Mvc\Services\HtmlConverter;

use Illuminate\Support\Collection;
use Anam\PhantomMagick\Converter;

class HtmlConverter implements HtmlConverterInterface
{
    /**
     * @var Collection
     */
    public $pages;

    public function __construct()
    {
        $this->pages = collect([]);
    }

    /**
     * @param string $html
     * @return Collection|mixed
     */
    public function page(string $html)
    {
        $this->pages = $this->pages->push($html);

        return $this;
    }

    public function save(string $path, string $type = 'pdf')
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
            $converter->save($path);
        } catch (\Exception $exception) {
            error('HtmlConverter save error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }
    }
}
