<?php

namespace Tychovbh\Mvc\Http\Requests\Laravel;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Tychovbh\Mvc\Http\Requests\RequestSettings;

abstract class FormRequest extends BaseFormRequest
{
    use RequestSettings;
}
