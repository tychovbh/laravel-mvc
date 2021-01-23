<?php


namespace Tychovbh\Mvc\Services;


interface ServiceModelInterface
{
    /**
     * Fills the model
     * @param array $data
     * @return array
     */
    public function fill(array $data = []);
}
