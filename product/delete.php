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

// получаем id товара
$data = json_decode(file_get_contents("php://input"));

// установим id товара для удаления
$product->id = $data->id;

// удаление товара
if ($product->delete()) {
    // код ответа - 200 ok
    http_response_code(200);

    // сообщение пользователю
    echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
}
// если не удается удалить товар
else {
    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // сообщим об этом пользователю
    echo json_encode(array("message" => "Не удалось удалить товар"));
}
