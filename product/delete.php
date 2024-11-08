<?php

// HTTP-Header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Fügen wir die Datei zur Verbindung mit der Datenbank und dem Produktobjekt ein
include_once "../config/database.php";
include_once "../objects/product.php";

// Wir bekommen eine Verbindung zur Datenbank
$database = new Database();
$db = $database->getConnection();

// Vorbereitung der Baustelle
$product = new Product($db);

// Wir erhalten die Produkt-ID
$data = json_decode(file_get_contents("php://input"));

// Legen Sie die zu löschende Produkt-ID fest
$product->id = $data->id;

// Produktentfernung
if ($product->delete()) {
    // Antwortcode - 200 ok
    http_response_code(200);

    // Nachricht an den Benutzer
    echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
}
// wenn Sie das Produkt nicht löschen können
else {
    // Antwortcode – 503 Dienst nicht verfügbar
    http_response_code(503);

    // Wir werden den Nutzer hierüber informieren
    echo json_encode(array("message" => "Не удалось удалить товар"));
}
