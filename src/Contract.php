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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'signers' => 'array',
    ];

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('file', 'status', 'signers', 'options', 'template', 'external_id', 'user_id');
        $this->columns('id', 'file', 'status', 'signers', 'options', 'external_id', 'user_id');
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
     * @param array $data
     * @throws \Throwable
     */
    public function toPdf(HtmlConverterInterface $htmlConverter, array $data = [])
    {
        if (!$this->template || !config('mvc-contracts.pdf.enabled')) {
            return;
        }
        $page = view($this->template, array_merge([
            'contract' => $this
        ], $data));

        $html = $page->render();
        $path = 'contracts/contract.pdf';
        $htmlConverter->page($html)->save($path);
        $this->file = $path;
    }

    /**
     * Generates file and sends a SignRequest
     * @param DocumentSignInterface $documentSign
     * @return bool|void
     */
    public function sign(DocumentSignInterface $documentSign)
    {
        $config = config('mvc-contracts.document_sign');

        if (!$this->file || !Arr::get($config, 'enabled', false)) {
            return;
        }

        try {
            $document = $documentSign->create(storage_path($this->file), Str::replaceFirst('contracts/', '', $this->file));
            $this->external_id = $document['id'];

            $redirectUrl = Arr::has($config, 'return') ? str_replace('{id}', $this->id, $config['return']) : null;

            $documentSign->signer($this->user->email)
                ->sign(
                    $document['id'],
                    Arr::get($config, 'from_name'),
                    Arr::get($config, 'from_email'),
                    '',
                    $redirectUrl
                );
            return $this->save();
        } catch (\Exception $exception) {
            error('Contract sign error', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ]);
            return false;
        }

    }
}
