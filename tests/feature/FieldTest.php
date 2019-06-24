<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Form;
use Tychovbh\Tests\Mvc\TestCase;

class FieldTest extends TestCase
{
    public function itCanBelongToForm()
    {
        $form = factory(Form::class)->create();
    }
}
