<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class BrandRepository extends RepositoryCore
{
    protected $table = 'brands';

    public function findAll()
    {
        $sql = 'SELECT id, name FROM brands WHERE status = 1';
        return $this->db->fetchAll($sql);
    }

    public function findNameById($id)
    {
        $sql = 'SELECT name FROM brands WHERE id = ?';

        return $this->db->fetchAssoc($sql, array((int)$id));
    }
}