<?php

namespace Tychovbh\Tests\Mvc\feature\commands;

use Tychovbh\Mvc\Contract;
use Tychovbh\Tests\Mvc\TestCase;

class MvcUpdateContractTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateContracts()
    {
        factory(Contract::class, 2)->create(['template' => 'contract', 'signed_at' => null]);
        $contract = factory(Contract::class)->make(['signed_at' => null]);
        $contract->template = 'contract';
        $contract->save();

        $response = $this->artisan('mvc:contracts-update')->expectsOutput('All contracts are updated');
    }
}
