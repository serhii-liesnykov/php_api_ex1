<?php

// erforderliche HTTP-Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Verbinden einer Datei mit der Datenbank und einer Datei mit dem Objekt
include_once "../config/database.php";
include_once "../objects/product.php";

// Wir bekommen eine Verbindung zur Datenbank
$database = new Database();
$db = $database->getConnection();

// Vorbereitung der Baustelle
$product = new Product($db);

// Legen Sie die Datensatz-ID-Eigenschaft zum Lesen fest
$product->id = isset($_GET["id"]) ? $_GET["id"] : die();

// Wir erhalten Produktdetails
$product->readOne();

if ($product->name != null) {

    // Erstellen eines Arrays
    $product_arr = array(
        "id" =>  $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
    );

    // Antwortcode - 200 OK
    http_response_code(200);

    // Ausgabe im JSON-Format
    echo json_encode($product_arr);
} else {
    // Antwortcode - 404 Nicht gefunden
    http_response_code(404);

    // сообщим пользователю, что такой товар не существует
    echo json_encode(array("message" => "Товар не существует"), JSON_UNESCAPED_UNICODE);
}
