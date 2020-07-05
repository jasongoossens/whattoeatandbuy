<?php
    require_once('inc/units.inc.php');
    require_once('inc/food.inc.php');

    $unit = new Unit();
    $units = $unit->getAll();

    $food = new Food();
    $foods = $food->getAll();
?>

<div class="form-row ingredient-info">
    <div class="col-2">
        <input type="number" step="0.01" required
        <?php if (isset($editMode) && $editMode) {
            if (isset($ingredients->amount) && $ingredients->amount != "") {
                echo "value=".$ingredients->amount." readonly";
            } else {
                echo "readonly";
            }
        }
        ?>
        name="ingredient-amount-1" class="form-control ingredient-amount" placeholder="999">
    </div>
    <div class="col-4">
        <select name="ingredient-unit-1" required
            <?= (isset($editMode) && $editMode) ? ' disabled' : '' ?>
        class="form-control ingredient-unit">
            <option value="" selected disabled>-Eenheid-</option>
            <?php
                foreach ($units as $row) {
                    if (isset($ingredients->unit_id) && $ingredients->unit_id != "" && $row['id'] == $ingredients->unit_id) {
                        echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
                    } else {
                        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                }
            ?>
        </select>
    </div>
    <div class="col-4">
        <select name="ingredient-food-1" required
            <?= (isset($editMode) && $editMode) ? ' disabled' : '' ?>
         class="form-control ingredient-food">
            <option value="" selected disabled>-Eten-</option>
            <?php
                foreach ($foods as $row) {
                    if (isset($ingredients->food_id) && $ingredients->food_id != "" && $row['id'] == $ingredients->food_id) {
                        echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
                    } else {
                        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                }
            ?>
        </select>
    </div>
    <div class="col-1" title="Voeg een ingredient toe">
        <i class="fa fa-plus add-line ingredient-line-btn<?= (isset($editMode) && $editMode) ? ' disabled-line-btn' : '' ?>" ></i>
    </div>
    <div class="col-1" title="Verwijder dit lijn">
        <i class="fa fa-minus remove-line ingredient-line-btn<?= (isset($editMode) && $editMode) ? ' disabled-line-btn' : '' ?>" ></i>
    </div>
</div>