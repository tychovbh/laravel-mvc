<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\Str;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverterInterface;

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
        $this->fillables('file', 'status', 'signed_at', 'options', 'template', 'external_id', 'user_id');
        $this->columns('id', 'file', 'status', 'signed_at', 'options', 'external_id', 'user_id');
        parent::__construct($attributes);
    }

    /**
     * Converts html file to pdf
     * @param HtmlConverterInterface $htmlConverter
     */
    public function toPdf(HtmlConverterInterface $htmlConverter)
    {
        if (!$this->template) {
            return;
        }

        $page = view($this->template);
        $html = $page->render();
        $path = 'contracts/contract.pdf';
        $htmlConverter->page($html)->save($path);
        $this->file = $path;
        $this->unsetAttribute('template');
    }

    /**
     * Generates file and sends a SignRequest
     * @param DocumentSignInterface $documentSign
     */
    public function sign(DocumentSignInterface $documentSign)
    {
        if (!$this->file) {
            return;
        }

        $document = $documentSign->create(storage_path($this->file), Str::replaceFirst('contracts/', '', $this->file));
        $this->external_id = $document['id'];

        $documentSign->signer('test@live.com')->sign($document['id'], 'Rentbay', 'noreply@rentbay.nl');
        $this->save();
    }
}
