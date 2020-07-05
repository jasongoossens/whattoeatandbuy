<?php

require_once('config.php');

class DBConnection {

    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;

    public function connect() {
        global $config_servername;
        $this->servername = $config_servername;
        global $config_username;
        $this->username = $config_username;
        global $config_password;
        $this->password = $config_password;
        global $config_dbname;
        $this->dbname = $config_dbname;
        global $config_charset;
        $this->charset = $config_charset;

        try {
            $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);
            return $pdo;
        } catch(PDOException $e){
            echo "Connection failed: ".$e->getMessage();
        }
    }
}