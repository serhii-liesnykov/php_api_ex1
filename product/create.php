<?php

// erforderliche HTTP-Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Wir bekommen eine Verbindung zur Datenbank
include_once "../config/database.php";

// Erstellen eines Produktobjekts
include_once "../objects/product.php";
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Wir erhalten die gesendeten Daten
$data = json_decode(file_get_contents("php://input"));

// Stellen Sie sicher, dass die Daten nicht leer sind
if (
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
) {
    // Legen Sie die Werte der Produkteigenschaften fest
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date("Y-m-d H:i:s");

    // Produkterstellung
    if ($product->create()) {
        // Legen Sie den Antwortcode fest - 201 erstellt
        http_response_code(201);

        // wir werden den Nutzer informieren
        echo json_encode(array("message" => "Товар был создан."), JSON_UNESCAPED_UNICODE);
    }
    // Wenn das Produkt nicht erstellt werden kann, benachrichtigen wir den Benutzer
    else {
        // Legen Sie den Antwortcode fest: 503-Dienst nicht verfügbar
        http_response_code(503);

        // wir werden den Nutzer informieren
        echo json_encode(array("message" => "Невозможно создать товар."), JSON_UNESCAPED_UNICODE);
    }
}
// Informieren Sie den Benutzer darüber, dass die Daten unvollständig sind
else {
    // Legen Sie den Antwortcode fest – 400 ungültige Anfrage
    http_response_code(400);

    // wir werden den Nutzer informieren
    echo json_encode(array("message" => "Невозможно создать товар. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
