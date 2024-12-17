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

    // создание массива
    $product_arr = array(
        "id" =>  $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($product_arr);
} else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что такой товар не существует
    echo json_encode(array("message" => "Товар не существует"), JSON_UNESCAPED_UNICODE);
}
