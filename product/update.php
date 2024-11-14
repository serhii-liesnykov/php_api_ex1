<?php

// HTTP-Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Verbinden Sie die Datei, um mit der Datenbank und dem Produktobjekt zu arbeiten
include_once "../config/database.php";
include_once "../objects/product.php";

// Wir bekommen eine Verbindung zur Datenbank
$database = new Database();
$db = $database->getConnection();

// Vorbereitung der Baustelle
$product = new Product($db);

// Rufen Sie die Produkt-ID zur Bearbeitung ab
$data = json_decode(file_get_contents("php://input"));

// Legen Sie die Produkteigenschafts-ID zur Bearbeitung fest
$product->id = $data->id;

// Legen Sie die Werte der Produkteigenschaften fest
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;

// Produktaktualisierung
if ($product->update()) {
    // Setzen Sie den Antwortcode auf 200 ok
    http_response_code(200);

    // wir werden den Nutzer informieren
    echo json_encode(array("message" => "Товар был обновлён"), JSON_UNESCAPED_UNICODE);
}
// Wenn das Produkt nicht aktualisiert werden kann, benachrichtigen wir den Benutzer
else {
    // Antwortcode – 503 Dienst nicht verfügbar
    http_response_code(503);

    // Nachricht an den Benutzer
    echo json_encode(array("message" => "Невозможно обновить товар"), JSON_UNESCAPED_UNICODE);
}
