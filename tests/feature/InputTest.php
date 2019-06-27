<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Http\Resources\InputResource;
use Tychovbh\Mvc\Input;
use Tychovbh\Tests\Mvc\TestCase;

class InputTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $forms = Input::all();
        $this->index('inputs.index', (InputResource::collection($forms)));
    }
}
