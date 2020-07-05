<?php

require_once('pdo_conn.php');

class Food extends DBConnection {

    public function getAll() {
        $stmt = $this->connect()->prepare("SELECT * FROM food ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM food WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function deleteById($id){
        $stmt = $this->connect()->prepare("DELETE FROM food WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteByName($name){
        $stmt = $this->connect()->prepare("DELETE FROM food WHERE name = ?");
        $stmt->execute([$name]);
    }

    public function create($name){
        $stmt = $this->connect()->prepare("INSERT INTO food (name) VALUES (?)");
        $stmt->execute([$name]);
    }

    public function selectFoodWhereNameEquals($name) {
        $name = "%$name%";
        $results = [];

        $stmt = $this->connect()->prepare("SELECT name FROM food WHERE name LIKE ?");
        $stmt->execute([$name]);
        while ($row = $stmt->fetchColumn()) {
            $results[] = $row;
        }

        echo json_encode($results);
    }
}