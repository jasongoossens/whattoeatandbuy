<?php
    require_once("inc/units.inc.php");

    $unit = new Unit();
    $allUnits = $unit->getAll();
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
    <title>EatMe - beheer eenheden</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <div class="nav-container">
        <?php include "navbar.php"; ?>
    </div>
    <div class="page-content">
        <div class="page-title units-theme-color">
            Eenheden
        </div>
        <div class="message">
            <span></span>
            <i class="fa fa-remove float-right remove-warning-btn"></i>
        </div>
        <div class="page-info">
            Op dit pagina kan je de eenheden voor je recepten toevoegen of verwijderen.  Met eenheden word bedoel gram (gr), milligram (mg), maar ook een sneetje, teentje, mespunt, krop, enz.
        </div>
        <div class="add-element-form">
            <form>
                <input type="text" name="new-unit" class="autocomplete">
                <div class="form-btn units-theme-color">Voeg toe <i class="fa fa-plus"></i></div>
            </form>
        </div>
        <div class="content-list">
            <div class="content-list-title">
                Bestaande eenheden <i class="fa fa-angle-double-down format-btn"></i> <i class="fa fa-angle-double-right format-btn"></i>
            </div>
            <ul>
                <?php foreach($allUnits as $row) {
                    echo '<li class="units-theme-color">'.strtolower($row['name']).' <i class="fa fa-remove remove-btn"></i></li>';
                }
                ?>
            </ul>
        </div>
    </div>

</body>
<script src="js/crud.js"></script>
<script src="js/layout.js"></script>
</html>