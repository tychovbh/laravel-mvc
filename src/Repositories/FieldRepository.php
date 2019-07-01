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
        if (Arr::has($data, 'input')) {
            $data['input_id'] = $this->inputs->findBy('name', $data['input'])->id;
        }
        return parent::save($data);
    }
}
