<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Models\Token;

class TokenRepository extends AbstractRepository implements Repository
{
    /**
     * @param array $data
     * @return Token
     */
    public function save(array $data)
    {
        return parent::save([
            'reference' => uniqid(),
            'value' => token($data),
            'type' => $data['type']
        ]);
    }
}
