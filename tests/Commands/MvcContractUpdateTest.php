<?php

namespace Tests\Commands;

use Tychovbh\Mvc\Models\Contract;
use Tests\TestCase;

class MvcContractUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateContracts()
    {
        $contract = factory(Contract::class)->make(['status' => Contract::STATUS_CONCEPT]);
        $contract->template = 'contract';
        $contract->save();

        $this->artisan('mvc-contracts:update')->expectsOutput('1 contracts updated');

        $contract = Contract::find($contract->id);
        $this->assertTrue($contract->status === 'signed' && $contract->signers);
    }
}
