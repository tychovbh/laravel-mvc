<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;

class MvcContractsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc-contracts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates the signed_at from a contract, when the status is si(signed)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param DocumentSignInterface $documentSign
     * @return mixed
     */
    public function handle(DocumentSignInterface $documentSign)
    {
        $contracts = Contract::whereNotNull('external_id')->whereNull('signed_at')->get();
        $updated = 0;
        foreach ($contracts as $contract) {
            try {
                $document = $documentSign->show($contract['external_id']);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
                continue;
            }

            $status = $contract->status;

            switch ($document['status']) {
                case 'co':
                    $contract->status = Contract::STATUS_CONCEPT;
                    break;
                case 'se':
                    $contract->status = Contract::STATUS_SENT;
                    break;
                case 'de':
                    $contract->status = Contract::STATUS_DENIED;
                    break;
                case 'si':
                    $contract->signed_at = now();
                    $contract->status = Contract::STATUS_SIGNED;
                    break;
            }

            if ($status !== $contract->status) {
                $contract->save();
                $updated++;
            }
        }

        $this->line(sprintf('%s contracts updated', $updated));
    }
}
