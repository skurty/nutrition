<?php

namespace Skurty\NutritionBundle\Controller;

use Silex\Application;

use Skurty\NutritionBundle\Core\ControllerCore;
use Skurty\NutritionBundle\Repository;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends ControllerCore
{
    public function indexParamsAction(Application $app, Request $request)
    {
        if (!is_null($request->query->get('start_date'))) {
            $startDate = $request->query->get('start_date');
        } else {
            $startDate = '2013-08-01';
        }

        if (!is_null($request->query->get('end_date'))) {
            $endDate = $request->query->get('end_date');
        } else {
            $endDate = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d')))));
        }

        // Get calories
        $calorieRepository = new $this->repository($app['db']);

        $stats = $calorieRepository->findAllByDate($startDate, $endDate);

        $dates = $calories = $proteins = $carbohydrates = $lipids = array();
        foreach ($stats['calories'] as $c) {
            $date = date('d/m/Y', strtotime($c['date']));

            $calories[$date] = $c['calories'];

            $macronutrients[$date] = array(
                'proteins'      => $c['proteins'],
                'carbohydrates' => $c['carbohydrates'],
                'lipids'        => $c['lipids']
            );
        }

        $weights = array();

        foreach ($stats['weights'] as $w) {
            $weights[$w['date']] = $w['weight'];
        }


        $stats['calories']       = $calories;
        $stats['startDate']      = $startDate;
        $stats['endDate']        = $endDate;
        $stats['macronutrients'] = $macronutrients;
        $stats['weights']        = $weights;

        return $app->json($stats);
    }
}