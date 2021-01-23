<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Tychovbh\Mvc\Models\Contract;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSign;
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
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
                continue;
            }

            $status = $contract->status;

            switch ($document->status) {
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
                    $this->addSigned($documentSign, $contract, $document);
                    break;
            }

            if ($status !== $contract->status) {
                $contract->save();
                $updated++;
            }
        }

        $this->line(sprintf('%s contracts updated', $updated));
    }

    /**
     * Updates contract to signed
     * @param DocumentSignInterface $documentSign
     * @param Contract $contract
     * @param DocumentSign $document
     */
    private function addSigned(DocumentSignInterface $documentSign, Contract $contract, DocumentSign $document)
    {
        $documentSign = $documentSign->signShow($document->sign_id);
        $contract->status = Contract::STATUS_SENT;
        $signers = $documentSign->signers;
        $signers_count = 0;
        $signed_count = 0;

        foreach ($signers as $signer) {
            if ($signer['needs_to_sign']) {
                $signers_count++;
            }

            if ($signer['signed_at']) {
                $signed_count++;
            }
        }

        if ($signed_count === $signers_count) {
            $contract->status = Contract::STATUS_SIGNED;
        }
        $contract->signers = $signers;
    }
}
