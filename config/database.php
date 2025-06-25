<?php
// config/database.php
class Database {
    private $host = 'localhost';
    private $port = '5432'; 
    private $db_name = 'crop_medicine_db';
    private $username = 'postgres';
    private $password = '8266';
    private $connection;

    public function connect() {
        $this->connection = null;
        try {
            $this->connection = new PDO(
                "pgsql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->connection;
    }
}


?>