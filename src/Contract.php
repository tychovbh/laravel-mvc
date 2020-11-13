<?php

namespace Tychovbh\Mvc;

class Contract extends Model
{
    const STATUS_CONCEPT = 'concept';
    const STATUS_SENT = 'sent';
    const STATUS_SIGNED = 'signed';
    const STATUS_DENIED = 'denied';

    const STATUSES = [
      self::STATUS_CONCEPT,
      self::STATUS_SENT,
      self::STATUS_SIGNED,
      self::STATUS_DENIED,
    ];

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('file', 'status', 'signed_at', 'options');
        $this->columns('file', 'status', 'signed_at', 'options');
        parent::__construct($attributes);
    }
}
