<?php
    require_once('inc/recipe-planning.inc.php');
    require_once('inc/recipe.inc.php');

    $recipePlanning = new RecipePlanning();
/*    $mostChosenRecipes = $recipePlanning->getPopularRecipes("DESC");
    $leastChosenRecipes = $recipePlanning->getPopularRecipes("ASC");

    $ingredients = new Recipe();
    $mostPopularIngredients = $ingredients->getMostPopularIngredients();
    $recipeWithMostIngredients = $ingredients->getMostOrLeastRecipeIngredients('DESC');
    $recipeWithLeastIngredients = $ingredients->getMostOrLeastRecipeIngredients('ASC')*/;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
    <title>EatMe - statistieken</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <div class="nav-container">
        <?php include "navbar.php"; ?>
    </div>
    <div class="page-content">
        <div class="page-title extra-theme-color">
            Statistieken
        </div>
        <div class="page-info">
            (Under construction) Hier kan je wat statistieken bekijken over de gekozen recepten, etenswaren, enz.
        </div>
        <div class="content-list">
            <div class="content-list-title">
                Meest gekozen recepten
            </div>
            <div class="canvas popular-recipes bordered-grey">
                <canvas id="most-popular-recipe" class=" bordered-grey"></canvas>
            </div>
            <div class="content-list-title">
                Minst gekozen recepten
            </div>
            <div class="canvas popular-recipes bordered-grey">
                <canvas id="least-popular-recipe" class=" bordered-grey"></canvas>
            </div>
            <div class="content-list-title">
                Meeste gebruikte ingredienten
            </div>
            <div class="canvas popular-recipes bordered-grey">
                <canvas id="most-used-ingredient" class=" bordered-grey"></canvas>
            </div>
            <div class="content-list-title">
                Recepten met meeste ingredienten
            </div>
            <div class="canvas popular-recipes bordered-grey">
                <canvas id="most-ingredients-in-recipe" class=" bordered-grey"></canvas>
            </div>
            <div class="content-list-title">
                Recepten met minst ingredienten
            </div>
            <div class="canvas popular-recipes bordered-grey">
                <canvas id="least-ingredients-in-recipe" class=" bordered-grey"></canvas>
            </div>
        </div>
    </div>

</body>
<script src="js/stats.js"></script>
</html>