<?php
    include('inc/recipe-planning.inc.php');
    include('inc/recipe.inc.php');

    $currentWeekNumber = date('W');
    $year = date('Y');
    $date = new DateTime();
    $date->setISODate($year,$currentWeekNumber);
    $firstDayOfWeek = $date->format('Y-m-d');
    $currentAndNextWeeksDates = [];

    for ($i = 0; $i <= 13; $i++) {
        $currentAndNextWeeksDates[] = $date->format('d-m-Y');
        $date->add(new DateInterval('P1D'));
    }

    $dateConverter = new RecipePlanning();

    $recipe = new Recipe();
    $recipes = $recipe->getAll();

    $currentPlanning = new RecipePlanning();
    $planning = $currentPlanning->getBiweeklyPlanningByWeekStart($firstDayOfWeek);
    $currentPlanningArray = [];
    foreach ($planning as $line) {
        $arr[date('d-m-Y', strtotime($line->date)).'&'.$line->dagdeel] = array(
            'date' => date('d-m-Y', strtotime($line->date)),
            'recipe_id' => $line->recipe_id,
            'ISOdate' => $line->date,
            'daypart' => $line->dagdeel,
        );
        $currentPlanningArray = $arr;
    }
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <title>EatMe - kalender</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <div class="nav-container">
        <?php include "navbar.php"; ?>
    </div>
    <div class="page-content">
        <div class="page-title calendar-theme-color">Kalender</div>
        <div class="message">
            <span></span>
            <i class="fa fa-remove float-right remove-warning-btn"></i>
        </div>
        <div class="page-info">Hier stel je weekrecepten samen om een boodschappenlijst te kunnen krijgen.</div>
        <div class="content-list">
            <div class="week">
                <form action="inc/actionHandler.php" method="POST">
                    <input type="hidden" value="<?= $firstDayOfWeek ?>" name="first-day-of-week">
                    <table class="day-planning">
                        <tr>
                            <td class="week-divider" colspan=2>Week 1</td>
                        </tr>
                        <?php foreach ($currentAndNextWeeksDates as $key=>$date): ?>
                            <?php if ($dateConverter->getDutchDayName($date) == "Ma" && $key > 1): ?>
                                <tr>
                                    <td class="week-divider" colspan=2>Week 2</td>
                                </tr>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < 2; $i++): ?>
                                <?php if ($date == date('d-m-Y')): ?>
                                    <tr<?php echo ' class="highlight"' ?>>
                                <?php else: ?>
                                    <tr>
                                <?php endif; ?>
                                <?php if ($i==0) {
                                    $dagdeel = "middag";
                                } else {
                                    $dagdeel = "avond";
                                } ?>
                                        <td>
                                            <?= $date." ".$dateConverter->getDutchDayName($date); ?> - <?= $dagdeel; ?>
                                        </td>
                                        <td>
                                            <select class="recipe-select" name="<?= $date.'-'.$dagdeel; ?>">
                                                <option value="">Geen recept</option>
                                                <?php foreach ($recipes as $line) {
                                                    $mealPlanned = false;
                                                    foreach ($currentPlanningArray as $planningLineKey => $planningLineValue) {
                                                        if (isset($planningLineKey) && $planningLineValue['recipe_id'] == $line['id']
                                                        && substr($planningLineKey,0,10) == $date
                                                        && $planningLineValue['daypart'] == $dagdeel) {
                                                            $mealPlanned = true;
                                                        }
                                                    }
                                                    echo $mealPlanned ? '<option selected value="'.$line['id'].'">'.$line['name'].'</option>' : '<option value="'.$line['id'].'">'.$line['name'].'</option>';
                                                } ?>
                                            </select>
                                        </td>
                                    </tr>
                            <?php endfor; ?>
                        <?php endforeach; ?>
                    </table>
                    <div class="planning-btn-container">
                        <div class="inline-block">
                            <a href="pdf.php?date=<?= $firstDayOfWeek;  ?>" class="inline-block half-width-btn calendar-theme-color">Boodschappenlijst</a>
                        </div>
                        <div class="inline-block">
                            <button class="remove-standard-btn-styling half-width-btn calendar-theme-color" name="save-week-planning" type="submit" value="Opslaan">Opslaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="js/layout.js"></script>
</html>