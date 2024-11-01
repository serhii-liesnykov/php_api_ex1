<?php

// HTTP-Header setzen
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verbinden von Dateien, um eine Verbindung zur Datenbank und einer Datei mit einem Kategorieobjekt herzustellen
include_once "../config/database.php";
include_once "../objects/category.php";

// Erstellen einer Datenbankverbindung
$database = new Database();
$db = $database->getConnection();

// Objektinitialisierung
$category = new Category($db);

// Wir bekommen Kategorien
$stmt = $category->readAll();
$num = $stmt->rowCount();

// Überprüfen Sie, ob mehr als 0 Datensätze gefunden werden
if ($num > 0) {

    // Array für Datensätze
    $categories_arr = array();
    $categories_arr["records"] = array();

    // Holen Sie sich den Inhalt unserer Tabelle
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // Extrahieren Sie die Zeichenfolge
        extract($row);
        $category_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
        array_push($categories_arr["records"], $category_item);
    }
    // Antwortcode - 200 OK
    http_response_code(200);

    // Kategoriedaten im JSON-Format anzeigen
    echo json_encode($categories_arr);
} else {

    // Antwortcode – 404 Nichts gefunden
    http_response_code(404);

    // Informieren Sie den Benutzer darüber, dass keine Kategorien gefunden wurden
    echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);
}
