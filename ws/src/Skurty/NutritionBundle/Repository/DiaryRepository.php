<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class DiaryRepository extends RepositoryCore
{
    protected $table = 'diaries';

    public function findById($id)
    {
        $sql = 'SELECT d.id, d.name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from quantity)) AS quantity,
                unit,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids,
                meal_id, m.name meal_name, brand_id, b.name brand_name
                FROM diaries d, meals m, brands b
                WHERE d.id = ?
                AND d.status = 1
                AND meal_id = m.id
                AND brand_id = b.id';

        return $this->db->fetchAssoc($sql, array((int)$id));
    }

    public function findByIdMin($id)
    {
        $sql = 'SELECT date, quantity, calories, proteins, carbohydrates, lipids, meal_id
                FROM diaries
                WHERE id = ?
                AND status = 1';

        return $this->db->fetchAssoc($sql, array((int)$id));
    }

    public function findCountDate($date, $last)
    {
        $sql = 'SELECT COUNT(*)
                FROM diaries
                WHERE date = ?
                AND updated > ?
                AND status = 1';

        return $this->db->fetchAssoc($sql, array($date, date('Y-m-d H:i:s', $last)));
    }

    public function findAllByMealDate($meal, $date)
    {
        $sql = 'SELECT d.id, d.name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from quantity)) AS quantity,
                unit,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids,
                b.name brand_name
                FROM diaries d
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE meal_id = ?
                AND date = ?
                AND d.status = 1;';

        return $this->db->fetchAll($sql, array((int)$meal, $date));
    }

    public function findAllByMealDateMin($meal, $date)
    {
        $sql = 'SELECT d.id, d.name, quantity, unit, calories, proteins, carbohydrates, lipids, meal_id, brand_id, b.name brand_name
                FROM diaries d
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE meal_id = ?
                AND date = ?
                AND d.status = 1;';

        return $this->db->fetchAll($sql, array((int)$meal, $date));
    }

    public function findAllByDate($date)
    {
        $sql = 'SELECT d.name, quantity, unit, calories, proteins, carbohydrates, lipids, meal_id, brand_id
                FROM diaries d
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE date = ?
                AND d.status = 1;';

        return $this->db->fetchAll($sql, array($date));
    }

    public function findTotalMeal($meal, $date)
    {
        $sql = 'SELECT COALESCE(ROUND(SUM(calories)), 0) AS calories, COALESCE(ROUND(SUM(proteins)), 0) AS proteins,
                       COALESCE(ROUND(SUM(carbohydrates)), 0) as carbohydrates, COALESCE(ROUND(SUM(lipids)), 0) AS lipids
                FROM diaries
                WHERE meal_id = ?
                AND date = ?
                AND status = 1;';

        return $this->db->fetchAssoc($sql, array((int)$meal, $date));
    }

    public function findTotalMeals($date)
    {
        $sql = 'SELECT COALESCE(ROUND(SUM(calories)), 0) AS calories, COALESCE(ROUND(SUM(proteins)), 0) AS proteins,
                       COALESCE(ROUND(SUM(carbohydrates)), 0) as carbohydrates, COALESCE(ROUND(SUM(lipids)), 0) AS lipids, meal_id
                FROM diaries
                WHERE date = ?
                AND status = 1
                GROUP BY meal_id
                ORDER BY meal_id;';

        return $this->db->fetchAll($sql, array($date));
    }

    public function findTotal($date)
    {
        $sql = 'SELECT COALESCE(ROUND(SUM(calories)), 0) AS calories, COALESCE(ROUND(SUM(proteins)), 0) AS proteins,
                       COALESCE(ROUND(SUM(carbohydrates)), 0) as carbohydrates, COALESCE(ROUND(SUM(lipids)), 0) AS lipids
                FROM diaries
                WHERE date = ?
                AND status = 1;';

        return $this->db->fetchAssoc($sql, array($date));
    }

    public function findLastDate($date)
    {
        $sql = 'SELECT MAX(updated) as updated
                FROM diaries
                WHERE date = ?
                AND status = 1;';

        return $this->db->fetchAssoc($sql, array($date));
    }

    public function copy($id, $meal)
    {
        $sql = 'SELECT name, quantity, unit, date, calories, proteins, carbohydrates, lipids, brand_id, food_id, recipe_id
                FROM diaries
                WHERE id = ?';

        $diary = $this->db->fetchAssoc($sql, array((int)$id));

        if (!is_null($diary['food_id'])) {
            $sql = 'UPDATE foods SET count = count + 1 WHERE id = ?';
            $this->db->executeQuery($sql, array((int)$diary['food_id']));
        }

        $sql = 'INSERT INTO diaries (name, quantity, unit, date, calories, proteins, carbohydrates, lipids, created, meal_id, brand_id, food_id, recipe_id) VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';

        $this->db->executeQuery($sql, array($diary['name'], $diary['quantity'], $diary['unit'], $diary['date'],
                $diary['calories'], $diary['proteins'], $diary['carbohydrates'], $diary['lipids'], date('Y-m-d H:i:s'), $meal, $diary['brand_id'],
                $diary['food_id'], $diary['recipe_id']));

        return 1;
    }

    public function move($id, $meal)
    {
        $sql = 'UPDATE diaries SET meal_id = ?, updated = ? WHERE id = ?';

        return $this->db->executeUpdate($sql, array((int)$meal, date('Y-m-d H:i:s'), (int)$id));
    }

    public function copyMeal($meal, $from, $to)
    {
        $sql = 'INSERT INTO diaries (name, quantity, unit, date, calories, proteins, carbohydrates, lipids, created, meal_id, brand_id, recipe_id)
                (SELECT name, quantity, unit, ?, calories, proteins, carbohydrates, lipids, ?, meal_id, brand_id, recipe_id
                FROM diaries
                WHERE date = ?
                AND meal_id = ?
                AND status = 1);';

        return $this->db->executeQuery($sql, array($to, date('Y-m-d H:i:s'), $from, (int)$meal));
    }

    public function copyDay($from, $to)
    {
        $sql = 'INSERT INTO diaries (name, quantity, unit, date, calories, proteins, carbohydrates, lipids, created, meal_id, brand_id, recipe_id)
                (SELECT name, quantity, unit, ?, calories, proteins, carbohydrates, lipids, ?, meal_id, brand_id, recipe_id
                FROM diaries
                WHERE date = ?
                AND status = 1);';

        return $this->db->executeQuery($sql, array($to, date('Y-m-d H:i:s'), $from));
    }

    public function update($id, $data)
    {
        $sql = 'UPDATE diaries SET ' .  implode('= ?, ', array_keys($data)) . ' = ?, updated = ? WHERE id = ?';

        $params = array_values($data);
        $params[] = date('Y-m-d H:i:s');
        $params[] = (int)$id;

        return $this->db->executeUpdate($sql, $params);
    }

    public function delete($id)
    {
        $sql = 'UPDATE foods f
                JOIN diaries d
                ON d.food_id = f.id
                SET count = count - 1, f.updated = ?
                WHERE d.id = ?;';

        $this->db->executeUpdate($sql, array(date('Y-m-d H:i:s'), $id));

        $params = array(
            'updated' => date('Y-m-d H:i:s'),
            'status'  => 0
        );

        return $this->db->update($this->table, $params, array('id' => $id));
    }

    public function updateFoodId()
    {
        $sql = 'SELECT d.id, f.id food_id
                FROM diaries d, foods f
                WHERE d.name = f.name
                AND d.brand_id = f.brand_id;';

        // $sql = 'SELECT d.id, f.id food_id
        //         FROM diaries d, foods f
        //         WHERE d.name = f.name
        //         AND d.brand_id IS NULL AND f.brand_id IS NULL;';

        $diaries = $this->db->fetchAll($sql);

        foreach ($diaries as $d) {
            $sql = 'UPDATE diaries SET food_id = ?, updated = ? WHERE id = ?';

            $this->db->executeUpdate($sql, array((int)$d['food_id'], date('Y-m-d H:i:s'), (int)$d['id']));
        }
    }
}
