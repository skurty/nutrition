<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class StatisticRepository extends RepositoryCore
{
    public function findAllByDate($startDate, $endDate)
    {
        $res = array();

        // Calories
        $sql = 'SELECT date, calories, proteins, carbohydrates, lipids FROM calories WHERE date >= \'' . $startDate . '\' AND date <= \'' . $endDate . '\'';
        $res['calories'] = $this->db->fetchAll($sql);


        // Weights
        $sql = 'SELECT date, weight FROM weights WHERE date >= \'' . $startDate . '\' AND date <= \'' . $endDate . '\' AND status = 1 ORDER BY date ASC;';
        $res['weights'] = $this->db->fetchAll($sql);

        return $res;
    }
}
