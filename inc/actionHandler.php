<?php
require_once('units.inc.php');
require_once('food.inc.php');
require_once('recipe.inc.php');
require_once('recipe-planning.inc.php');

if (isset($_POST['request']) && ($_POST['request'] == 'delete-unit-by-name')) {
    $name = $_POST['name'];
    $unit = new Unit();
    $unit->deleteByName($name);
} else if (isset($_POST['request']) && ($_POST['request'] == 'delete-food-by-name')) {
    $name = $_POST['name'];
    $food = new Food();
    $food->deleteByName($name);
} else if (isset($_POST['request']) && ($_POST['request'] == 'delete-recipe-by-name')) {
    $id = $_POST['id'];
    $recipe = new Recipe();
    $recipe->deleteById($id);
} else if (isset($_POST['request']) && ($_POST['request'] == 'add-new-unit')) {
    $name = $_POST['name'];
    $unit = new Unit();
    $unit->create($name);
} else if (isset($_POST['request']) && ($_POST['request'] == 'add-new-food')) {
    $name = $_POST['name'];
    $food = new Food();
    $food->create($name);
} else if (isset($_POST['request']) && ($_POST['request'] == 'autocomplete')) {
    if ($_POST['table'] == 'units') {
        $searchString = $_POST['str'];
        $unit = new Unit();
        $unit->selectUnitsWhereNameEquals($searchString);
    } else if ($_POST['table'] == 'food') {
        $searchString = $_POST['str'];
        $food = new Food();
        $food->selectFoodWhereNameEquals($searchString);
    } else if ($_POST['table'] == 'recipe') {
        $searchString = $_POST['str'];
        $recipe = new Recipe();
        $recipe->selectRecipeWhereNameEquals($searchString);
    }
} else if (isset($_POST['save-recipe']) && ($_POST['save-recipe'] == 'Bewaar recept')) {
    $name = $_POST['recipe-name'];
    $description = $_POST['recipe-description'];
    $instructions = $_POST['recipe-instructions'];
    $url = $_POST['recipe-url'];
    $image = "";
    if (isset($_FILES['recipe-image']['name']) && $_FILES['recipe-image']['name'] !== "" ) {
        $image = $_FILES['recipe-image'];
    }

    $counter = $_POST['nrOfIngredientLines'];
    $ingredientLineArray = array();

    for ($i = 1; $i < ($counter+1); $i++) {
        ${"amount".$i} = str_replace(',', '.', $_POST["ingredient-amount-".$i]);
        ${"unit".$i} = $_POST["ingredient-unit-".$i];
        ${"food".$i} = $_POST["ingredient-food-".$i];

        $ingredientLineArray['line'.$i] = [floatval(${"amount".$i}),(int)${"unit".$i},(int)${"food".$i}];
    }

    $recipe = new Recipe();
    $recipe->create($ingredientLineArray, $name, $description, $instructions, $url, $image);
} else if (isset($_POST['update-recipe']) && ($_POST['update-recipe'] == 'Bewaar recept')) {
    $overwriteExistingImage = true;
    $id = $_POST['id'];
    $name = $_POST['recipe-name'];
    $description = $_POST['recipe-description'];
    $instructions = $_POST['recipe-instructions'];
    $url = $_POST['recipe-url'];
    $previousImage = "";
    if (isset($_POST['existing-image']) && $_POST['existing-image'] !== '') {
        $previousImage = $_POST['existing-image'];
    }

    $image = "";

    if (isset($_FILES['recipe-image']['name']) && $_FILES['recipe-image']['name'] !== "" ) {
        $image = $_FILES['recipe-image'];
    } else if (isset($_POST['existing-image']) && $_POST['existing-image'] !== "") {
        $overwriteExistingImage = false;
    } else {
        $overwriteExistingImage = false;
    }

    $counter = $_POST['nrOfIngredientLines'];
    $ingredientLineArray = array();

    for ($i = 1; $i < ($counter+1); $i++) {
        ${"amount".$i} = str_replace(',', '.', $_POST["ingredient-amount-".$i]);
        ${"unit".$i} = $_POST["ingredient-unit-".$i];
        ${"food".$i} = $_POST["ingredient-food-".$i];

        $ingredientLineArray['line'.$i] = [floatval(${"amount".$i}),(int)${"unit".$i},(int)${"food".$i}];
    }

    $recipe = new Recipe();
    // instead of getting all the recipe ingredient lines, just delete them and add the new ones in place.
    $recipe->updateById($id, $ingredientLineArray, $name, $description, $instructions, $url, $image, $overwriteExistingImage, $previousImage);
} else if (isset($_POST['name']) && ($_POST['name'] == 'remove-img')) {
    $id = $_POST['id'];
    $recipe = new Recipe();
    $recipe->deleteImageById($id);
} else if (isset($_POST['request']) && ($_POST['request'] == 'find-id-by-name')) {
    $name = $_POST['str'];
    $recipe = new Recipe();
    $recipe->findIdByName($name);
} else if (isset($_POST['save-week-planning']) && ($_POST['save-week-planning'] == 'Opslaan')) {
    $planningArray = [];
    foreach ($_POST as $postVarKey => $postVarVal) {
        $planningArray[] = array(date('Y-m-d', strtotime(substr($postVarKey, 0,10))), substr($postVarKey, 11), $postVarVal);
    }
    $planning = new RecipePlanning();
    $planning->createWeekPlanning($planningArray);
} else if (isset($_GET['stat']) && ($_GET['stat'] == 'most-popular-recipe')) {
    $recipePlanning = new RecipePlanning();
    $mostChosenRecipes = $recipePlanning->getPopularRecipes("DESC");
} else {
    echo 'Er is iets foutgegaan.  Probeer het opnieuw.<br>';
    var_dump($_POST);
}