<?php
    require_once('inc/units.inc.php');
    require_once('inc/food.inc.php');
    require_once('inc/recipe.inc.php');

    $unit = new Unit();
    $units = $unit->getAll();

    $food = new Food();
    $foods = $food->getAll();

    $editMode = false;

    if (isset($_GET['id']) && $_GET['id'] != "") {
        $recipeId = $_GET['id'];
        $recipe = new Recipe();
        $currentRecipe = $recipe->getById($recipeId);
        $editMode = true;
    }

//    isset($currentRecipe) ? var_dump($currentRecipe) : "";
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
    <title>EatMe - Recept</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <div class="nav-container">
        <?php include "navbar.php"; ?>
    </div>
    <div class="page-content">
        <div class="page-title recipe-theme-color">
            Recept
        </div>
        <div class="message">
            <span></span>
            <i class="fa fa-remove float-right remove-warning-btn"></i>
        </div>
        <div class="page-info">
            Hier kan je een recept bekijken, aanmaken, aanpassen, verwijderen, of printen.
        </div>
        <div class="recipe-form-container">
            <form name="add-recipe" action="inc/actionHandler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo (isset($editMode)) && $editMode ? $currentRecipe[0]->recipe_id : "" ?> ">
                <input type="hidden" name="existing-image" value="<?php echo (isset($editMode)) && $editMode ? $currentRecipe[0]->image : "" ?>">
                <div class="form-group">
                    <input type="text" required
                        <?php if ($editMode) {
                            if (isset($currentRecipe[0]->name) && $currentRecipe[0]->name != "") {
                                echo 'value="'.$currentRecipe[0]->name.'" readonly';
                            } else {
                                echo "readonly";
                            }
                        }
                        ?>
                     class="form-control form-control-lg" name="recipe-name" placeholder="Recept naam" maxlength="255"
                    >
                </div>
                <div class="form-group">
                    <input type="text"
                        <?php if ($editMode) {
                            if (isset($currentRecipe[0]->description) && $currentRecipe[0]->description != "") {
                                echo 'value="'.$currentRecipe[0]->description.'" readonly';
                            } else {
                                echo " readonly";
                            }
                        }
                        ?>
                    class="form-control form-control-lg"  name="recipe-description" Placeholder="Omschrijving"
                    >
                </div>

                <?php if ($editMode): ?>
                    <?php foreach ($currentRecipe as $ingredients): ?>
                        <div class="ingredient-line-container">
                            <?php include "ingredient-line.php"; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="ingredient-line-container">
                        <?php include "ingredient-line.php"; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <textarea class="form-control form-control-lg"  name="recipe-instructions" Placeholder="Werkwijze (gemakkelijker om niet op de GSM te doen, tenzij je het copy/paste." rows="8"
                        <?= $editMode ? 'readonly' : '' ?>
                    ><?php if (isset($currentRecipe[0]->instructions) && $currentRecipe[0]->instructions != '') {
                            echo trim($currentRecipe[0]->instructions);
                            }
                            ?></textarea>
               </div>
                <div class="form-group">
                    <input type="text"
                        <?php if ($editMode) {
                            if (isset($currentRecipe[0]->url) && $currentRecipe[0]->url != "") {
                                echo "value=".$currentRecipe[0]->url." readonly";
                            } else {
                                echo "readonly";
                            }
                        }
                        ?>
                     class="form-control form-control-lg"  name="recipe-url" Placeholder="Website (http://...)">
                </div>
                <?php if (isset($currentRecipe[0]->image) && $currentRecipe[0]->image != "") {
                    echo '<div class="recipe-image bordered-grey">';
                    echo '<i class="remove-img-btn fa fa-close"></i>';
                    echo '<img src="uploads/'.$currentRecipe[0]->image.'">';
                    echo '</div>';
                }
                ?>
                <div class="inline-block">
                    <input type="file" class="form-control-file" name="recipe-image" id="recipe-image"
                        <?= ($editMode) ? "disabled" : "" ; ?>>
                    <label for="recipe-image" class="recipe-theme-color half-width-btn">Voeg afbeelding toe</label>
                </div>
                <input type="hidden" name="nrOfIngredientLines" value="">
                <div class="inline-block">
                    <?php if(!$editMode): ?>
                        <input type="submit" value="Bewaar recept" class="save-or-edit-recipe remove-standard-btn-styling recipe-theme-color half-width-btn" name="save-recipe">
                    <?php else: ?>
                        <button class="edit-recipe remove-standard-btn-styling recipe-theme-color half-width-btn">Pas recept aan</button>
                    <?php endif; ?>
                </div>
           </form>
        </div>
    </div>
</body>
<script src="js/layout.js"></script>
<script src="js/crud.js"></script>
</html>