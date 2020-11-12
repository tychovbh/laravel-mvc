<?php

namespace Tychovbh\Mvc\Services\HtmlConverter;

interface HtmlConverterInterface
{
    /**
     * @param string $html
     * @return mixed
     */
    public function page(string $html);

    /**
     * @param string $path
     * @param string $type
     * @return mixed
     */
    public function save(string $path, string $type = 'pdf');
}
