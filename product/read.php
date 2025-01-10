<?php

// erforderliche HTTP-Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Datenbankverbindung und Datei mit Objekten
include_once "../config/database.php";
include_once "../objects/product.php";

// Wir bekommen eine Verbindung zur Datenbank
$database = new Database();
$db = $database->getConnection();

// Initialisieren Sie das Objekt
$product = new Product($db);
 
// Ware anfordern
$stmt = $product->read();
$num = $stmt->rowCount();

// Überprüfen, ob mehr als 0 Datensätze gefunden wurden
if ($num > 0) {
    // Warensortiment
    $products_arr = array();
    $products_arr["records"] = array();

    // Holen Sie sich den Inhalt unserer Tabelle
    // fetch() ist schneller als fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extrahieren Sie die Zeichenfolge
        extract($row);
        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
        array_push($products_arr["records"], $product_item);
    }

    // setze den Antwortcode auf 200 OK
    http_response_code(200);

    // Produktdaten im JSON-Format ausgeben
    echo json_encode($products_arr);
}

else {
    // setze den Antwortcode - 404 Nicht gefunden
    http_response_code(404);

    // Wir informieren den Benutzer, dass keine Produkte gefunden wurden
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}
