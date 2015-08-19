<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class CalorieRepository extends RepositoryCore
{
    protected $table = 'calories';

    public function getLastDate()
    {
        $sql = 'SELECT MAX(date) FROM calories';
        return $this->db->fetchColumn($sql);
    }

    public function update($startDate, $endDate)
    {
        $sql = 'INSERT INTO calories (date, calories, proteins, carbohydrates, lipids)
                SELECT date, SUM(calories) AS calories, SUM(proteins) AS proteins, SUM(carbohydrates) AS carbohydrates, SUM(lipids) AS lipids
                FROM diaries
                WHERE ' . (!is_null($startDate) ? 'date > ? AND ' : '') . 'date <= ?
                AND status = 1
                GROUP BY date';
        if (!is_null($startDate))  {
            $params = array($startDate, $endDate);
        } else {
            $params = array($endDate);
        }
        return $this->db->executeQuery($sql, $params);
    }
}
