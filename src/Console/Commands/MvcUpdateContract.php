<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;

class MvcUpdateContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:contracts-update';

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
        try {
            $contracts = Contract::all()->whereNotNull('external_id')->whereNull('signed_at');

            foreach ($contracts as $contract) {
                $document = $documentSign->show($contract['external_id']);

                switch ($document['status']) {
                    case 'co':
                        $contract['status'] = Contract::STATUS_CONCEPT;
                        break;
                    case 'se':
                        $contract['status'] = Contract::STATUS_SENT;
                        break;
                    case 'de':
                        $contract['status'] = Contract::STATUS_DENIED;
                        break;
                    case 'si':
                        $date = new \DateTime();
                        $contract['signed_at'] = $date->format('Y-m-d H:i:s');
                        $contract['status'] = Contract::STATUS_SIGNED;
                        break;
                }
            }
            return $this->line('All contracts are updated');
        } catch (\Exception $exception) {
            return $this->warn('Something went wrong');
        }
    }
}
