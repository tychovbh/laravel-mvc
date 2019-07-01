<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Field;

/**
 * @property InputRepository inputs
 */
class FieldRepository extends AbstractRepository implements Repository
{
    /**
     * InputRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Field();
        parent::__construct();
        $this->inputs = new InputRepository();
    }

    /**
     * Overwrite save to store input
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        return parent::save($this->addInput($data));
    }

    public function update(array $data, int $id)
    {
        return parent::update($this->addInput($data), $id);
    }

    /**
     * Add input_id to data
     * @param array $data
     * @return array|void
     */
    private function addInput(array $data)
    {
        if (Arr::has($data, 'input')) {
            $data['input_id'] = $this->inputs->findBy('name', $data['input'])->id;
        }
        return $data;
    }
}
