<?php

require_once('pdo_conn.php');

class RecipePlanning extends DBConnection {

    public $dutchDayNames =
    [
        'Monday' => 'Ma',
        'Tuesday' => 'Di',
        'Wednesday' => 'Wo',
        'Thursday' => 'Do',
        'Friday' => 'Vr',
        'Saturday' => 'Za',
        'Sunday' => 'Zo'
    ];

    public function getDutchDayName($date) {
        $englishDay = date('l', strtotime($date));

        if (isset($this->dutchDayNames[$englishDay])) {
            return $this->dutchDayNames[$englishDay];
        }
    }

    public function clearPlanningForNextTwoWeeks($date) {
        $deletePlanningSql = ("DELETE FROM `recipe-planning` WHERE (date BETWEEN '$date' AND DATE_ADD('$date', INTERVAL 13 DAY))");
        $deleteStmt = $this->connect()->prepare($deletePlanningSql);
        $deleteStmt->execute();
    }

    public function createWeekPlanning($weekPlanning){
        // clear the relevant weeks and then fill them again
        $firstDayOfWeek = $weekPlanning[1][0];
        $this->clearPlanningForNextTwoWeeks($firstDayOfWeek);

        $nothingToPlan = true;
        $planningSql = ("INSERT INTO `recipe-planning`(`date`, `dagdeel`, `recipe_id`) VALUES ");
        for ($i = 1; $i < 29; $i++) {
            if ($weekPlanning[$i][2] !== "") {
                $planningSql .= "('".$weekPlanning[$i][0]."', '".$weekPlanning[$i][1]."', ".$weekPlanning[$i][2]."),";
                $nothingToPlan = false;
            }
        }
        $planningSql = rtrim($planningSql, ",");
        if (!$nothingToPlan) {
            $planningStmt = $this->connect()->prepare($planningSql);
            $planningStmt->execute();
        }
        header('Location: ../index.php');
    }

    public function getBiweeklyPlanningByWeekStart($startDate) {
        $stmt = $this->connect()->prepare("SELECT date, dagdeel, recipe_id FROM `recipe-planning` WHERE (date BETWEEN '$startDate' AND DATE_ADD('$startDate', INTERVAL 13 DAY)) ORDER BY date ASC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getSummaryForPdf($startDate) {
        $stmt = $this->connect()->prepare("
            SELECT t1.date,
                   t1.dagdeel,
                   t2.id,
                   t2.name,
                   t2.description,
                   t2.instructions,
                   t2.url,
                   t2.image
            FROM   `recipe-planning` t1
                   JOIN `recipe` t2
                     ON t2.id = t1.recipe_id
            WHERE  ( date BETWEEN '$startDate' AND Date_add('$startDate', INTERVAL 13 day) )
            ORDER  BY date ASC
        ");
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
    * Gets most or least popular recipes
    *
    * @param string $rank DESC for most, ASC for least
    * @return array
    */
    public function getPopularRecipes($rank) {
        $stmt = $this->connect()->prepare("SELECT name, count(*) AS amount FROM `recipe-planning` JOIN recipe ON recipe.id = `recipe-planning`.recipe_id GROUP BY recipe_id ORDER BY amount $rank LIMIT 3");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_OBJ));
    }
}