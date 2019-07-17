<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Repositories\ElementRepository;
use Tychovbh\Mvc\Repositories\PropertyRepository;

/**
 * @property PropertyRepository properties
 * @property ElementRepository elements
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
     * @param ElementRepository $elements
     * @param PropertyRepository $properties
     * @param FormRepository $forms
     */
    public function __construct(ElementRepository $elements, PropertyRepository $properties, FormRepository $forms)
    {
        parent::__construct();
        $this->elements = $elements;
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
        foreach (config('forms.properties') as $property) {
            $this->properties->saveOrUpdate('name', $property['name'], $property);
        }

        DB::table('element_properties')->truncate();
        foreach (config('forms.elements') as $element) {
            $this->elements->saveOrUpdate('name', $element['name'], $element);
        }

        DB::table('fields')->truncate();
        foreach (config('forms.forms') as $form) {
            $this->forms->saveOrUpdate('name', $form['name'], $form);
        }
    }
}
