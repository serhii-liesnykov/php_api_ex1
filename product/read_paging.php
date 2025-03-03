<?php

// HTTP-Header festlegen
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Dateien verbinden
include_once "../config/core.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/product.php";

// utilities
$utilities = new Utilities();

// eine Verbindung herstellen
$database = new Database();
$db = $database->getConnection();

// Objektinitialisierung
$product = new Product($db);

// Anfrage nach Waren
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// wenn es mehr als 0 Datensätze gibt
if ($num > 0) {

    // Auswahl an Waren
    $products_arr = array();
    $products_arr["records"] = array();
    $products_arr["paging"] = array();

    // Wir erhalten den Inhalt unserer Tabelle
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // Zeichenfolge extrahieren
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

    // Lassen Sie uns die Paginierung verbinden
    $total_rows = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"] = $paging;

    // Setzen wir den Antwortcode auf 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($products_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что товаров не существует
    echo json_encode(array("message" => "Товары не найдены"), JSON_UNESCAPED_UNICODE);
}
