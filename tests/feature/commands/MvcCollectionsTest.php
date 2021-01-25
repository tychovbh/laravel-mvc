<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\Commands;

use Tychovbh\Tests\Mvc\TestCase;

class MvcCollectionsTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateMvcCollections()
    {
        $this->artisan('mvc:collections');
        $this->assertDatabaseHasCollection('users', [
            [
                'id' => 1,
                'name' => 'Jan',
                'email' => 'jan@live.com'
            ],
            [
                'id' => 2,
                'name' => 'piet',
                'email' => 'piet@live.com'
            ],
        ]);
    }
}
