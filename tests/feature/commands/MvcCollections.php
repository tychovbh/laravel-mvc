<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\Commands;
;
use Tychovbh\Tests\Mvc\TestCase;

class MvcCollections extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateMvcCollections()
    {
        $this->artisan('mvc:collections');

        foreach (config('collections') as $collection) {
            $this->assertDatabaseHasCollection($collection['table'], $collection['items']);
        }
    }
}
