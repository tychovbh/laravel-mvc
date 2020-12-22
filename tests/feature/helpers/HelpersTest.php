<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\helpers;
use Tychovbh\Tests\Mvc\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSplitAddresses()
    {
        $this->assertEquals([
            'street' => 'straat',
            'number' => '1',
            'addition' => '',
        ], split_address('straat 1'));

        $this->assertEquals([
            'street' => 'straat naam',
            'number' => '1',
            'addition' => '',
        ], split_address('straat naam 1'));

        $this->assertEquals([
            'street' => 'straat',
            'number' => '1',
            'addition' => '1',
        ], split_address('straat 1-1'));

        $this->assertEquals([
            'street' => 'straat',
            'number' => '113',
            'addition' => 'a',
        ], split_address('straat 113a'));

        $this->assertEquals([
            'street' => 'straat naam',
            'number' => '1',
            'addition' => '1a',
        ], split_address('straat naam 1-1a'));
    }
}
