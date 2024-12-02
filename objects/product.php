<?php

class Product
{
    // Verbindung zur Datenbank und zur Tabelle „Produkte“ herstellen
    private $conn;
    private $table_name = "products";

    // Objekteigenschaften
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    // Konstruktor für die Verbindung zu einer Datenbank
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Methode zur Warenannahme
function read()
{
    // Wählen Sie alle Datensätze aus
    $query = "SELECT
        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM
        " . $this->table_name . " p
        LEFT JOIN
            categories c
                ON p.category_id = c.id
    ORDER BY
        p.created DESC";

    // Vorbereitung einer Anfrage
    $stmt = $this->conn->prepare($query);

    // die Anfrage ausführen
    $stmt->execute();
    return $stmt;
}
// Methode zur Herstellung von Produkten
function create()
{
    // Abfrage zum Einfügen (Erstellen) von Datensätzen
    $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

    // Vorbereitung einer Anfrage
    $stmt = $this->conn->prepare($query);

    // Reinigung
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->created = htmlspecialchars(strip_tags($this->created));

    // Wertbindung
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":created", $this->created);

    // die Anfrage ausführen
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// Methode zum Erhalten eines bestimmten Produkts anhand der ID
function readOne()
{
    // Anfrage zum Lesen eines Datensatzes (Produkts)
    $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        WHERE
            p.id = ?
        LIMIT
            0,1";
            
    // Vorbereitung einer Anfrage
    $stmt = $this->conn->prepare($query);

    // Binden Sie die ID des Produkts, das empfangen wird
    $stmt->bindParam(1, $this->id);

    // die Anfrage ausführen
    $stmt->execute();

    // Wir erhalten die extrahierte Zeichenfolge
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Legen Sie die Werte der Objekteigenschaften fest
    $this->name = $row["name"];
    $this->price = $row["price"];
    $this->description = $row["description"];
    $this->category_id = $row["category_id"];
    $this->category_name = $row["category_name"];
}
// Methode zum Aktualisieren eines Produkts
function update()
{
    // Anfrage zur Aktualisierung eines Datensatzes (Produkts)
    $query = "UPDATE
            " . $this->table_name . "
        SET
            name = :name,
            price = :price,
            description = :description,
            category_id = :category_id
        WHERE
            id = :id";

    // Vorbereitung einer Anfrage
    $stmt = $this->conn->prepare($query);

    // Reinigung
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Werte binden
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":id", $this->id);

    // die Anfrage ausführen
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// Methode zum Löschen eines Produkts
function delete()
{
    // Anfrage zum Löschen eines Datensatzes (Produkts)
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // Vorbereitung einer Anfrage
    $stmt = $this->conn->prepare($query);

    // Reinigung
    $this->id = htmlspecialchars(strip_tags($this->id));

    // привязываем id записи для удаления
    $stmt->bindParam(1, $this->id);

    // выполняем запрос
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// метод для поиска товаров
function search($keywords)
{
    // поиск записей (товаров) по "названию товара", "описанию товара", "названию категории"
    $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        WHERE
            p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
        ORDER BY
            p.created DESC";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // привязка
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);

    // выполняем запрос
    $stmt->execute();

    return $stmt;
}
// получение товаров с пагинацией
public function readPaging($from_record_num, $records_per_page)
{
    // выборка
    $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        ORDER BY p.created DESC
        LIMIT ?, ?";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // свяжем значения переменных
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // выполняем запрос
    $stmt->execute();

    // вернём значения из базы данных
    return $stmt;
}
// данный метод возвращает кол-во товаров
public function count()
{
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row["total_rows"];
}
}
