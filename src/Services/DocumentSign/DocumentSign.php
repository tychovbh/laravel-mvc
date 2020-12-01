<?php

namespace Tychovbh\Mvc\Services\DocumentSign;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DocumentSign
{
    /**
     * @var string
     */
    public $doc_id;

    /**
     * @var string
     */
    public $sign_id;

    /**
     * @var Collection
     */
    public $signers;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    public $cancelled;

    /**
     * DocumentSign constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->doc_id = Arr::get($data, 'doc_id', null);
        $this->sign_id = Arr::get($data, 'sign_id', null);
        $this->status = Arr::get($data, 'status', null);
        $this->cancelled = Arr::get($data, 'cancelled', null);
        $this->signers = collect([]);
    }

    public function signer(string $email, $signed_at, bool $needs_to_sign = false)
    {
        $this->signers = $this->signers->push([
            'email' => $email,
            'signed_at' => $signed_at,
            'needs_to_sign' => $needs_to_sign
        ]);
    }
}
