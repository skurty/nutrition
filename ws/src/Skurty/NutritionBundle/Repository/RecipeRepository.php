<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class RecipeRepository extends RepositoryCore
{
    protected $table = 'recipes';

    public function findAll()
    {
        $sql = 'SELECT id, name,
        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
        TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids
        FROM recipes WHERE status = 1 ORDER BY name;';
        return $this->db->fetchAll($sql);
    }

    public function findList()
    {
        $sql = 'SELECT id, name FROM recipes WHERE status = 1 ORDER BY name;';
        return $this->db->fetchAll($sql);
    }

    public function findById($id)
    {
        $res = array();

        $sql = 'SELECT id, name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from lipids)) AS lipids
                FROM recipes WHERE id = ? AND status = 1';
        $recipe = $this->db->fetchAssoc($sql, array((int)$id));

        $sql = 'SELECT fr.id, f.name,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from fr.quantity)) AS quantity,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from fr.calories)) AS calories,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from fr.proteins)) AS proteins,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from fr.carbohydrates)) AS carbohydrates,
                TRIM(TRAILING \'.\' FROM TRIM(TRAILING \'0\' from fr.lipids)) AS lipids,
                b.name as brand
                FROM food_recipes fr, foods f
                LEFT JOIN brands b
                ON brand_id = b.id
                WHERE recipe_id = ?
                AND food_id = f.id
                AND fr.status = 1;';
        $recipe['foods'] = $this->db->fetchAll($sql, array((int)$recipe['id']));

        return $recipe;
    }

    public function findByIdMin($id)
    {
        $sql = 'SELECT name, calories, proteins, carbohydrates, lipids
                FROM recipes
                WHERE id = ?
                AND status = 1;';
        return $this->db->fetchAssoc($sql, array((int)$id));
    }

    public function updateFoodRecipe($foodRecipe)
    {
        $sql = 'UPDATE recipes
                SET calories = calories - ?, proteins = proteins - ?, carbohydrates = carbohydrates - ?,  lipids = lipids - ?
                WHERE id = ?;';
        return $this->db->executeQuery($sql, array($id));
    }
}
