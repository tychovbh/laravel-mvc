<?php

namespace Tychovbh\Tests\Mvc\feature\commands;

use Tychovbh\Mvc\Contract;
use Tychovbh\Tests\Mvc\TestCase;

class MvcContractUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateContracts()
    {
        $contract = factory(Contract::class)->make(['signed_at' => null, 'status' => Contract::STATUS_CONCEPT]);
        $contract->template = 'contract';
        $contract->save();

        $this->artisan('mvc-contracts:update')->expectsOutput('1 contracts updated');

        $contract = Contract::find($contract->id);
        $this->assertTrue($contract->status === 'signed' && $contract->signed_at);
    }
}
