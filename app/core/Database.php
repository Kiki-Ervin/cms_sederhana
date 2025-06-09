<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require_once __DIR__ . '/../config/database.php';
        
        $this->conn = new \mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = [], $types = '') {
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function fetch($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function fetchAll($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $types = str_repeat('s', count($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        $stmt = $this->query($sql, array_values($data), $types);
        
        return $this->conn->insert_id;
    }

    public function update($table, $data, $where, $whereParams = [], $whereTypes = '') {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $types = str_repeat('s', count($data)) . $whereTypes;
        
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        $params = array_merge(array_values($data), $whereParams);
        
        $stmt = $this->query($sql, $params, $types);
        return $stmt->affected_rows;
    }

    public function delete($table, $where, $params = [], $types = '') {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params, $types);
        return $stmt->affected_rows;
    }
} 