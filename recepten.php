<?php
    require_once('inc/recipe.inc.php');

    $recipe = new Recipe();
    $recipes = $recipe->getAll();
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
    <script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
    <title>EatMe - beheer etenswaren</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <div class="nav-container">
        <?php include "navbar.php"; ?>
    </div>
    <div class="page-content">
        <div class="page-title recipe-theme-color">
            Recepten
        </div>
        <div class="message">
            <span></span>
            <i class="fa fa-remove float-right remove-warning-btn"></i>
        </div>
        <div class="page-info">
            Op dit pagina kan je je recepten beheren.
        </div>
        <div class="find-recipe-form">
            <form action="inc/actionHandler.php" method="GET">
                <input type="text" name="find-recipe" class="autocomplete" autofocus>
                <button name="submit" class="remove-standard-btn-styling find-btn recipe-theme-color">Zoek recept <i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="content-list">
            <div class="content-list-title">
                Bestaande recepten (<?= count($recipes) ?>)
                <i class="fa fa-angle-double-down format-btn"></i>
                <i class="fa fa-angle-double-right format-btn"></i>
            </div>
            <ul>
                <?php
                    foreach ($recipes as $row) {
                        echo '<a href="recept.php?id='.$row['id'].'"><li id="'.$row['id'].'" class="recipe-theme-color">'.ucfirst($row['name']).' <i class="fa fa-remove remove-btn"></i></li></a>';
                    }
                ?>
            </ul>
        </div>
        <div class="bottom-right">
            <a href="recept.php" class="add-new-recipe-btn recipe-theme-color">Voeg toe <i class="fa fa-plus"></i></a>
        </div>
    </div>

</body>
<script src="js/crud.js"></script>
<script src="js/layout.js"></script>
</html>