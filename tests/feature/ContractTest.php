<?php

namespace Tychovbh\Tests\Mvc\feature;

use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Http\Resources\ContractResource;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverter;
use Tychovbh\Tests\Mvc\TestCase;

class ContractTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $contracts = factory(Contract::class, 2)->create();
        $this->index('contracts.index', ContractResource::collection($contracts));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $contract = factory(Contract::class)->create();
        $this->show('contracts.show', ContractResource::make($contract));
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $contract = factory(Contract::class)->make();
        $store = $contract->toArray();
        $contract->id = 1;
        $contract->file = 'contracts/file.pdf';
        $this->store('contracts.store', ContractResource::make($contract), $store);
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $contract = factory(Contract::class)->create();
        $update = factory(Contract::class)->make();
        $update->id = $contract->id;
        $this->update('contracts.update', ContractResource::make($update), $update->toArray());
    }

    /**
     * @test
     */
    public function itCanDestroy()
    {
        $this->destroy('contracts.destroy', factory(Contract::class)->create());
    }
}
