<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Repositories\ElementRepository;
use Tychovbh\Mvc\Repositories\PropertyRepository;

/**
 * @property PropertyRepository properties
 * @property ElementRepository inputs
 * @property FormRepository forms
 */
class MvcUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update mvc package';

    /**
     * Create a new command instance.
     *
     * @param ElementRepository $inputs
     * @param PropertyRepository $properties
     */
    public function __construct(ElementRepository $inputs, PropertyRepository $properties, FormRepository $forms)
    {
        parent::__construct();
        $this->inputs = $inputs;
        $this->properties = $properties;
        $this->forms = $forms;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $properties = config('forms.properties');
        foreach ($properties as $property) {
            $this->properties->saveOrUpdate('name', $property['name'], $property);
        }

        $inputs = config('forms.inputs');
        foreach ($inputs as $input) {
            $this->inputs->saveOrUpdate('name', $input['name'], $input);
        }

        $forms = config('forms.forms');
        foreach ($forms as $form) {
            $this->forms->saveOrUpdate('name', $form['name'], $form);
        }
    }
}
