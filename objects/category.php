<?php

class Category
{
    // соединение с БД и таблицей "categories"
    private $conn;
    private $table_name = "categories";

    // свойства объекта
    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

 // метод для получения всех категорий товаров
public function readAll()
{
    $query = "SELECT
                id, name, description
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
}
}