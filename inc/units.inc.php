<?php

require_once('pdo_conn.php');

class Unit extends DBConnection {

    public function getAll() {
        $stmt = $this->connect()->prepare("SELECT * FROM units ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) { // with placeholder
        $stmt = $this->connect()->prepare("SELECT * FROM units WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function deleteById($id){
        $stmt = $this->connect()->prepare("DELETE FROM units WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteByName($name){ // with named param
        $stmt = $this->connect()->prepare("DELETE FROM units WHERE name = :name");
        $stmt->execute(['name' => $name]);
    }

    public function create($name){
        $stmt = $this->connect()->prepare("INSERT INTO units (name) VALUES (?)");
        $stmt->execute([$name]);
    }

    public function selectUnitsWhereNameEquals($name) {
        $name = "%$name%";
        $results = [];

        $stmt = $this->connect()->prepare("SELECT name FROM units WHERE name LIKE ?");
        $stmt->execute([$name]);
        while ($row = $stmt->fetchColumn()) {
            $results[] = $row;
        }

        echo json_encode($results);
    }
}