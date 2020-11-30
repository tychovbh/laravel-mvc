<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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
    protected $description = 'This command updates contracts information';

    /**
     * Execute the console command.
     *
     * @param DocumentSignInterface $documentSign
     * @return mixed
     */
    public function handle(DocumentSignInterface $documentSign)
    {
        $contracts = Contract::whereNotNull('external_id')->whereIn('status', [Contract::STATUS_CONCEPT, Contract::STATUS_SENT])->get();
        $updated = 0;
        foreach ($contracts as $contract) {
            try {
                $document = $documentSign->show($contract['external_id']);
                $documentSign = $documentSign->signShow($document['signrequest_id']);
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
                    $contract->status = Contract::STATUS_SENT;
                    $signers = count(array_filter($documentSign['signers'], function ($signer) {
                        return $signer['needs_to_sign'];
                    }));
                    $signed = count(array_filter($documentSign['signers'], function ($signer) {
                        return $signer['signed_at'] && $signer['needs_to_sign'];
                    }));
                    if ($signed === $signers) {
                        $contract->status = Contract::STATUS_SIGNED;
                    }
                    $contract->signers = $documentSign['signers'];
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
