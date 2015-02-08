<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class GoalRepository extends RepositoryCore
{
    protected $table = 'goals';

    public function findAll()
    {
        $sql = 'SELECT id, date,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids
                FROM goals WHERE status = 1 ORDER BY date DESC';
        return $this->db->fetchAll($sql);
    }

    public function findById($id) {
        $sql = 'SELECT date,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids
                FROM goals
                WHERE id = ?
                AND status = 1;';
        return $this->db->fetchAssoc($sql, array((int)$id));
    }

    public function findByDate($date)
    {
        $sql = 'SELECT TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids
                FROM goals
                WHERE date <= ?
                AND status = 1
                ORDER BY date DESC
                LIMIT 1;';
        return $this->db->fetchAssoc($sql, array($date));
    }

    public function insert($params)
    {
        $params['created'] = date('Y-m-d H:i:s');
        
        return $this->db->insert('goals', $params);
    }
}
