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
     */
    public function save(string $path, string $type = 'pdf');

    /**
     * Downloads all the pages in browser
     * @param string $path
     * @param string $type
     * @param bool $force
     */
    public function download(string $path, string $type = 'pdf', bool $force = false);
}
