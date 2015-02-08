<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class FoodRepository extends RepositoryCore
{
    protected $table = 'foods';

    public function findAll()
    {
        $sql = 'SELECT f.id, f.name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from quantity)) AS quantity,
                unit,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids,
                b.id brand_id, b.name brand_name
                FROM foods f
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE f.status = 1
                ORDER BY f.name ASC, b.name ASC;';
        return $this->db->fetchAll($sql);
    }

    public function findAllMin()
    {
        $sql = 'SELECT f.id, f.name, b.name brand_name
                FROM foods f
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE f.status = 1
                ORDER BY count DESC;';
        return $this->db->fetchAll($sql);
    }

    public function findById($id)
    {
        $sql = 'SELECT f.id, f.name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from quantity)) AS quantity,
                unit,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids,
                count, brand_id, b.name brand_name
                FROM foods f
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE f.id = ?
                AND f.status = 1;';
        return $this->db->fetchAssoc($sql, array((int)$id));
    }

    public function findByIdMin($id)
    {
        $sql = 'SELECT name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from quantity)) AS quantity,
                unit,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids,
                count, brand_id
                FROM foods
                WHERE id = ?
                AND status = 1;';
        return $this->db->fetchAssoc($sql, array((int)$id));
    }
}