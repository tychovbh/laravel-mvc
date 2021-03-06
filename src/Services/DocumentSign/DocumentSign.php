<?php

namespace Tychovbh\Mvc\Services\DocumentSign;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tychovbh\Mvc\Services\ServiceModelInterface;

class DocumentSign implements ServiceModelInterface
{
    /**
     * @var string
     */
    public $id;

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
        $this->fill($data);
    }

    /**
     * Fills the model
     * @param array $data
     */
    public function fill(array $data = [])
    {
        $this->id = Arr::get($data, 'id', null);
        $this->sign_id = Arr::get($data, 'sign_id', null);
        $this->status = Arr::get($data, 'status', null);
        $this->cancelled = Arr::get($data, 'cancelled', null);
        $this->signers = collect([]);
    }

    /**
     * Adds a signer to the collection
     * @param string $email
     * @param $signed_at
     * @param bool $needs_to_sign
     */
    public function signer(string $email, $signed_at, bool $needs_to_sign = false)
    {
        $this->signers = $this->signers->push([
            'email' => $email,
            'signed_at' => $signed_at,
            'needs_to_sign' => $needs_to_sign
        ]);
    }
}
