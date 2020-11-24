<?php

namespace Tychovbh\Tests\Mvc\feature\commands;

use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Http\Resources\ContractResource;
use Tychovbh\Tests\Mvc\TestCase;

class MvcUpdateContractTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateContracts()
    {
        factory(Contract::class, 2)->create(['template' => 'contract', 'signed_at' => null]);
        $contract = factory(Contract::class)->make(['signed_at' => null, 'status' => Contract::STATUS_CONCEPT]);
        $contract->template = 'contract';
        $contract->save();

        $this->artisan('mvc-contract:update')->expectsOutput('All contracts are updated');

        $contract = Contract::find(3);
        $this->assertTrue($contract->status === 'signed' && $contract->signed_at);
    }
}
