<?php

namespace Tychovbh\Mvc\Services\HtmlConverter;

interface HtmlConverterInterface
{
    /**
     * Adds a page to pages
     * @param string $html
     * @return HtmlConverterInterface
     */
    public function page(string $html): HtmlConverterInterface;

    /**
     * Converts all the pages to specified type
     * @param string $path
     * @param string $type
     * @return mixed
     */
    public function save(string $path, string $type = 'pdf'): mixed;

    /**
     * Downloads all the pages in browser
     * @param string $path
     * @param string $type
     * @param bool $force
     * @return mixed
     */
    public function download(string $path, string $type = 'pdf', bool $force = false): mixed;
}
