<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class WeightRepository extends RepositoryCore
{
    protected $table = 'weights';

    public function findAll()
    {
        $sql = 'SELECT id, date, weight FROM weights WHERE status = 1 ORDER BY date DESC';
        return $this->db->fetchAll($sql);
    }

    public function findById($id) {
        $sql = 'SELECT date, weight
                FROM weights
                WHERE id = ?
                AND status = 1;';
        return $this->db->fetchAssoc($sql, array((int)$id));
    }
}
