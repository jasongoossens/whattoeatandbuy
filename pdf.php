<?php
   require_once('inc/recipe-planning.inc.php');
   require_once('inc/recipe.inc.php');

    $recipePlanning = new RecipePlanning();
    $summary = $recipePlanning->getSummaryForPdf($_GET['date']);

    $firstDayWeekOne = $_GET['date'];
    $firstDayWeekTwo = date('Y-m-d', strtotime($firstDayWeekOne. '+ 7 days'));

    $recipe = new Recipe();
    $weekOneIngredients = $recipe->getAllIngredientsPerWeek($firstDayWeekOne);
    $weekTwoIngredients = $recipe->getAllIngredientsPerWeek($firstDayWeekTwo);
?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/pdf-style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <title>Boodschappenlijst en recepten voor de komende 2 weken</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
<h1>Receptenkalender</h1>
<?php foreach ($summary as $key => $day): ?>
    <div class="recipe">
        <div class="title-container">
            <h4><?= $key+1; ?>) <?= $recipePlanning->getDutchDayName($day->date).' '.date('d-m-Y', strtotime($day->date)) ?></h4>
            <h3><?= $day->name ?></h3>
        </div>
        <div class="ingredients-container content-container">
            <h5>Ingredienten</h5>
            <table class="table table-sm table-striped ">
                <thead class="thead-dark">
                    <tr>
                        <th>Hoeveelheid</th>
                        <th>Meeteenheid</th>
                        <th>Eten</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $ingredients = $recipe->getIngredientsByRecipeId($day->id);
                        foreach ($ingredients as $line) {
                            echo '<tr>';
                            echo '<td>';
                            echo $line->amount;
                            echo '</td>';
                            echo '<td>';
                            echo $line->unit;
                            echo '</td>';
                            echo '<td>';
                            echo $line->food;
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="instructions-container content-container">
            <h5>Instructies</h5>
            <?= nl2br($day->instructions); ?>
        </div>
        <div class="url-container content-container">
            <h5>Website</h5>
            <?php if (isset($day->url) && $day->url !== "") {
                echo '<a href="'.$day->url.'">'.$day->url.'</a>';
            } else {
                echo 'Geen website ingevuld';
            }
            ?>
        </div>
        <div class="image-container content-container">
            <h5>Afbeelding</h5>
            <?php if (isset($day->image) && $day->image !== "") {
                echo '<img class="recipe-image" src="uploads/'.$day->image.'" alt="'.$day->name.'">';
            } else {
                echo 'Geen afbeelding opgeslagen';
            }
            ?>
        </div>
    </div>
<?php endforeach; ?>
<div class="shopping-list">
    <h1>Boodschappenlijst</h1>
        <h4>Week 1</h4>
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Hoeveelheid</th>
                    <th>Meeteenheid</th>
                    <th>Eten</th>
                    <th>Recept</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($weekOneIngredients as $ingredientLine): ?>
                    <tr>
                        <td><?= $ingredientLine->amount; ?></td>
                        <td><?= $ingredientLine->unit; ?></td>
                        <td><?= $ingredientLine->food; ?></td>
                        <td><?= $ingredientLine->recipe; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Week 2</h4>
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Hoeveelheid</th>
                    <th>Meeteenheid</th>
                    <th>Eten</th>
                    <th>Recept</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($weekTwoIngredients as $ingredientLine): ?>
                    <tr>
                        <td><?= $ingredientLine->amount; ?></td>
                        <td><?= $ingredientLine->unit; ?></td>
                        <td><?= $ingredientLine->food; ?></td>
                        <td><?= $ingredientLine->recipe; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>
</body>
</html>