<?php

namespace Skurty\NutritionBundle\Repository;

use Skurty\NutritionBundle\Core\RepositoryCore;

class FoodRecipeRepository extends RepositoryCore
{
    protected $table = 'food_recipes';

    public function findByIdMin($id)
    {
        $sql = 'SELECT quantity, calories, proteins, carbohydrates, lipids, recipe_id
                FROM food_recipes
                WHERE id = ?
                AND status = 1';

        return $this->db->fetchAssoc($sql, array((int)$id));
    }
}
