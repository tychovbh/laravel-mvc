<?php

namespace Tychovbh\Mvc;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;
use Tychovbh\Mvc\Services\DocumentSign\SignRequest;
use Tychovbh\Mvc\Services\HtmlConverter\PhantomMagickConverter;

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
        $this->columns('file', 'status', 'signed_at', 'options', 'external_id', 'user_id');
        parent::__construct($attributes);
    }

    public function toPdf()
    {
        if (!$this->template) {
            return;
        }

        $page = view($this->template);
        $html = $page->render();
        $htmlConverter = new PhantomMagickConverter();
        $path = 'contracts/contract.pdf';
        $htmlConverter->page($html)->save($path);
        $this->file = $path;
        $this->unsetAttribute('template');
    }

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
