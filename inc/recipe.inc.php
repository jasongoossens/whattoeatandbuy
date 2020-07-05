<?php

require_once('pdo_conn.php');

// for file uploads-create, upload, delete and getAll
class Recipe extends DBConnection {

    public function getAll() {
        $stmt = $this->connect()->prepare("SELECT id, name FROM recipe ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) { // with placeholder
        $stmt = $this->connect()->prepare("SELECT * FROM recipe JOIN recipe_ingredients ON recipe.id = recipe_ingredients.recipe_id JOIN ingredients ON recipe_ingredients.ingredients_id = ingredients.id WHERE recipe.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getIdByName($name) {
        $stmt = $this->connect()->prepare("SELECT id FROM recipe WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function deleteById($id){
        // first get recipe_ingredients id
        // then delete all linked recipe_ingredients
        // then delete all ingredient lines
        // then delete the recipe
        $getIngredientsIdStmt = $this->connect()->prepare("SELECT ingredients_id FROM recipe_ingredients WHERE recipe_id = :recipe_id");
        $getIngredientsIdStmt->execute(['recipe_id' => $id]);
        $ingredientsIds = $getIngredientsIdStmt->fetchAll(PDO::FETCH_COLUMN);

        $recipeIngredientsStmt = $this->connect()->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = :id");
        $recipeIngredientsStmt->execute(['id' => $id]);

        $ingredientsId = implode(",", $ingredientsIds);
        $sql = "DELETE FROM ingredients WHERE id IN ($ingredientsId)";

        $ingredientsStmt = $this->connect()->prepare("DELETE FROM ingredients WHERE id IN ($ingredientsId)");
        $ingredientsStmt->execute();

        $recipeStmt = $this->connect()->prepare("DELETE FROM recipe WHERE id = :id");
        $recipeStmt->execute(['id' => $id]);
    }

    public function updateById($id, $ingredientsArray, $name, $description, $instructions, $url, $image, $overwriteExistingImage, $previousImage){
        if ($overwriteExistingImage) {
            // delete current image
            var_dump(file_exists("../uploads/".$previousImage));
            if (file_exists("../uploads/".$previousImage)) {
                unlink("../uploads/".$previousImage);
            }

            // image upload
            $imageDirectory = "../uploads/";
            $imageName = time().'_'.$image['name'];

            $fileExtension = strtolower(pathinfo($imageDirectory.$imageName, PATHINFO_EXTENSION));

            move_uploaded_file($image['tmp_name'], $imageDirectory.$imageName);

            // DELETE CURRENT RECIPE LINES AND INGREDIENT_RECIPE
            // first get recipe_ingredients id
            $getIngredientsIdStmt = $this->connect()->prepare("SELECT ingredients_id FROM recipe_ingredients WHERE recipe_id = :recipe_id");
            $getIngredientsIdStmt->execute(['recipe_id' => $id]);
            $ingredientsIds = $getIngredientsIdStmt->fetchAll(PDO::FETCH_COLUMN);

            // then delete all linked recipe_ingredients for FK integrity
            $recipeIngredientsStmt = $this->connect()->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = :id");
            $recipeIngredientsStmt->execute(['id' => $id]);

            // then delete all ingredient lines
            $ingredientsId = implode(",", $ingredientsIds);
            $ingredientsStmt = $this->connect()->prepare("DELETE FROM ingredients WHERE id IN ($ingredientsId)");
            $ingredientsStmt->execute();

            // CREATE NEW RECIPE LINES AND INGREDIENT_RECIPE
            // then create new ingredient lines
            $ingredientsSql = ("INSERT INTO `ingredients`(`amount`, `unit_id`, `food_id`) VALUES ");
            foreach ($ingredientsArray as $line){
                $ingredientsSql .= ("($line[0], $line[1], $line[2]),");
            }
            $ingredientsSql = rtrim($ingredientsSql, ',');
            $ingredientsStmt = $this->connect();
            $ingredientsStmt->prepare($ingredientsSql)->execute();

            $firstInsertedId = $ingredientsStmt->lastInsertId();
            $actualLastInsertedId = $firstInsertedId + (count($ingredientsArray)-1);

            // another instance of not using prepared stmt's
            $recipeIngredientsSql = ("INSERT INTO `recipe_ingredients`(`recipe_id`, `ingredients_id`) VALUES ");
            for ($i = $firstInsertedId; $i < ($actualLastInsertedId+1); $i++) {
                $recipeIngredientsSql .= "($id, $i), ";
            }
            $recipeIngredientsSql = rtrim($recipeIngredientsSql, ', ');
            $recipeIngredientsStmt = $this->connect()->prepare($recipeIngredientsSql);
            $recipeIngredientsStmt->execute();

            $recipeStmt = $this->connect()->prepare("UPDATE `recipe` SET `name` = :name, `description` = :description, `instructions` = :instructions, `url` = :url, `image` = :image WHERE id = :id");
            $recipeStmt->execute(['id' => $id, 'name' => $name, 'description' => $description, 'instructions' => $instructions, 'url' => $url, 'image' => $imageName]);
        } else {
            // DELETE CURRENT RECIPE LINES AND INGREDIENT_RECIPE
            // first get recipe_ingredients id
            $getIngredientsIdStmt = $this->connect()->prepare("SELECT ingredients_id FROM recipe_ingredients WHERE recipe_id = :recipe_id");
            $getIngredientsIdStmt->execute(['recipe_id' => $id]);
            $ingredientsIds = $getIngredientsIdStmt->fetchAll(PDO::FETCH_COLUMN);

            // then delete all linked recipe_ingredients for FK integrity
            $recipeIngredientsStmt = $this->connect()->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = :id");
            $recipeIngredientsStmt->execute(['id' => $id]);

            // then delete all ingredient lines
            $ingredientsId = implode(",", $ingredientsIds);
            $ingredientsStmt = $this->connect()->prepare("DELETE FROM ingredients WHERE id IN ($ingredientsId)");
            $ingredientsStmt->execute();

            // CREATE NEW RECIPE LINES AND INGREDIENT_RECIPE
            // then create new ingredient lines
            $ingredientsSql = ("INSERT INTO `ingredients`(`amount`, `unit_id`, `food_id`) VALUES ");
            foreach ($ingredientsArray as $line){
                $ingredientsSql .= ("($line[0], $line[1], $line[2]),");
            }
            $ingredientsSql = rtrim($ingredientsSql, ',');
            $ingredientsStmt = $this->connect();
            $ingredientsStmt->prepare($ingredientsSql)->execute();

            $firstInsertedId = $ingredientsStmt->lastInsertId();
            $actualLastInsertedId = $firstInsertedId + (count($ingredientsArray)-1);

            // another instance of not using prepared stmt's
            $recipeIngredientsSql = ("INSERT INTO `recipe_ingredients`(`recipe_id`, `ingredients_id`) VALUES ");
            for ($i = $firstInsertedId; $i < ($actualLastInsertedId+1); $i++) {
                $recipeIngredientsSql .= "($id, $i), ";
            }
            $recipeIngredientsSql = rtrim($recipeIngredientsSql, ', ');
            $recipeIngredientsStmt = $this->connect()->prepare($recipeIngredientsSql);
            $recipeIngredientsStmt->execute();

            $recipeStmt = $this->connect()->prepare("UPDATE `recipe` SET `name` = :name, `description` = :description, `instructions` = :instructions, `url` = :url WHERE id = :id");
            $recipeStmt->execute(['id' => $id, 'name' => $name, 'description' => $description, 'instructions' => $instructions, 'url' => $url]);
        }

        header('Location: ../recepten.php');
    }

    public function create($ingredientsArray, $name, $description, $instructions, $url, $image){
        if (isset($image) && $image !== '') {
            // image upload
            $imageDirectory = "../uploads/";
            $imageName = time().'_'.$image['name'];

            $fileExtension = strtolower(pathinfo($imageDirectory.$imageName, PATHINFO_EXTENSION));

            move_uploaded_file($image['tmp_name'], $imageDirectory.$imageName);
            $imageMessage = "File uploaded";
        } else {
            $imageName = "";
        }

        // made the call to not use a prepared statement here, or else I'd need n number of db calls,
        // intstead of one
        // I can't find a way to bindParam or bindValue properly in a loop
        $ingredientsSql = ("INSERT INTO `ingredients`(`amount`, `unit_id`, `food_id`) VALUES ");
        foreach ($ingredientsArray as $line){
            $ingredientsSql .= ("($line[0], $line[1], $line[2]),");
        }
        $ingredientsSql = rtrim($ingredientsSql, ',');
        $ingredientsStmt = $this->connect();
        $ingredientsStmt->prepare($ingredientsSql)->execute();

        $firstInsertedId = $ingredientsStmt->lastInsertId();
        $actualLastInsertedId = $firstInsertedId + (count($ingredientsArray)-1);

        $recipeStmt = $this->connect();
        $recipeStmt->prepare("INSERT INTO `recipe`(`name`, `description`, `instructions`, `url`, `image`) VALUES (?,?,?,?,?)")->execute([$name, $description, $instructions, $url, $imageName]);
        $lastInsertedRecipeId = $recipeStmt->lastInsertId();

        // another instance of not using prepared stmt's
        $recipeIngredientsSql = ("INSERT INTO `recipe_ingredients`(`recipe_id`, `ingredients_id`) VALUES ");
        for ($i = $firstInsertedId; $i < ($actualLastInsertedId+1); $i++) {
            $recipeIngredientsSql .= "($lastInsertedRecipeId, $i), ";
        }
        $recipeIngredientsSql = rtrim($recipeIngredientsSql, ', ');
        $recipeIngredientsStmt = $this->connect()->prepare($recipeIngredientsSql);
        $recipeIngredientsStmt->execute();
        header('Location: ../recepten.php');
    }

    public function selectRecipeWhereNameEquals($name) {
        $name = "%$name%";
        $results = [];

        $stmt = $this->connect()->prepare("SELECT name, id FROM recipe WHERE name LIKE ?");
        $stmt->execute([$name]);
        while ($row = $stmt->fetchColumn()) {
            $results[] = $row;
        }

        echo json_encode($results);
    }

    public function deleteImageById($id) {
        $getImageName = $this->connect()->prepare("SELECT image FROM recipe WHERE id = ?");
        $getImageName->execute([$id]);
        $image = $getImageName->fetchAll(PDO::FETCH_COLUMN);
        var_dump($image);
        $stmt = $this->connect()->prepare("UPDATE `recipe` SET `image`= NULL  WHERE id = ?");
        $stmt->execute([$id]);

        unlink("../uploads/".$image[0]);
    }

    public function findIdByName($name) {
        $stmt = $this->connect()->prepare("SELECT id FROM recipe WHERE name =  ?");
        $stmt->execute([$name]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
   }

   public function getIngredientsByRecipeId($id) {
        $stmt = $this->connect()->prepare("
            SELECT amount,
                   units.name AS unit,
                   food.name AS food
            FROM   recipe_ingredients
                   JOIN ingredients
                     ON ingredients.id = recipe_ingredients.ingredients_id
                   JOIN units
                     ON ingredients.unit_id = units.id
                   JOIN food
                     ON ingredients.food_id = food.id
            WHERE  recipe_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
   }

   public function getAllIngredientsPerWeek($firstDayOfWeek) {
        $stmt = $this->connect()->prepare("
            SELECT SUM(amount) AS amount,
                   units.name AS unit,
                   food.name AS food,
                   recipe.name as recipe
            FROM   `recipe-planning`
                   JOIN recipe_ingredients
                     ON recipe_ingredients.recipe_id = `recipe-planning`.recipe_id
                   JOIN recipe
                     ON recipe.id = `recipe-planning`.recipe_id
                   JOIN ingredients
                     ON ingredients.id = recipe_ingredients.ingredients_id
                   JOIN units
                     ON ingredients.unit_id = units.id
                   JOIN food
                     ON ingredients.food_id = food.id
            WHERE ( date BETWEEN '$firstDayOfWeek' AND Date_add('$firstDayOfWeek', INTERVAL 6 day))
            GROUP BY unit, food
            ORDER BY food, unit ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
   }

   public function getMostPopularIngredients() {
        $stmt = $this->connect()->prepare("SELECT name, count(*) AS amount FROM ingredients JOIN food ON food.id = ingredients.food_id GROUP BY name ORDER BY amount DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    /**
    * Gets recipes with the most or least number of ingredients
    *
    * @param string $rank DESC for most ingredients, ASC for least
    * @return array
    */
   public function getMostOrLeastRecipeIngredients($mostOrLeast) {
        $stmt = $this->connect()->prepare("SELECT name, count(*) AS amount FROM recipe_ingredients JOIN recipe ON recipe.id = recipe_ingredients.recipe_id GROUP BY name ORDER BY amount $mostOrLeast LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}