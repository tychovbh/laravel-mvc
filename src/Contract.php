<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
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
     * @var array
     */
    private $config;

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('file', 'status', 'signed_at', 'options', 'template', 'external_id', 'user_id');
        $this->columns('id', 'file', 'status', 'signed_at', 'options', 'external_id', 'user_id');
        $this->config = config('mvc-contracts');
        parent::__construct($attributes);
    }

    /**
     * The Users
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Converts html file to pdf
     * @param HtmlConverterInterface $htmlConverter
     */
    public function toPdf(HtmlConverterInterface $htmlConverter)
    {
        if (!$this->template || !config('mvc-contracts.pdf.enabled')) {
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
        if (!$this->file || !config('mvc-contracts.document_sign.enabled')) {
            return;
        }

        try {
            $document = $documentSign->create(storage_path($this->file), Str::replaceFirst('contracts/', '', $this->file));
            $this->external_id = $document['id'];
            $documentSign->signer($this->user->email)->sign($document['id'], 'Rentbay', 'noreply@rentbay.nl');
            $this->save();
        } catch (\Exception $exception) {
            error('Contract sign error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
        }

    }
}
