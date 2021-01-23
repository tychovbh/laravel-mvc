<?php


namespace Tychovbh\Mvc\Services\Voucher;


use GuzzleHttp\Exception\GuzzleException;

interface VoucherInterface
{
    /**
     * Check if voucher is valid
     * @param string $voucher
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function validate(string $voucher, array $data): array;

    /**
     * Uses the voucher
     * @param string $voucher
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function use(string $voucher, array $data = []): array;
}
