<?php
namespace Oauth2\Models\Repositories;

use GapOrmCache\Classes\Repository;

class BaseAuthClientRepository extends Repository
{
    /**
     * @param array $where
     * @return mixed
     */
    public function getByParams($where = [])
    {
        return $this->getModel()
                    ->where($where)
                    ->runOnce();
    }
}