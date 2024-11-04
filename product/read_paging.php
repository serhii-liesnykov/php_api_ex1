<?php

// установим HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once "../config/core.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/product.php";

// utilities
$utilities = new Utilities();

// создание подключения
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$product = new Product($db);

// запрос товаров
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей
if ($num > 0) {

    // массив товаров
    $products_arr = array();
    $products_arr["records"] = array();
    $products_arr["paging"] = array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлечение строки
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

    // подключим пагинацию
    $total_rows = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"] = $paging;

    // установим код ответа - 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($products_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что товаров не существует
    echo json_encode(array("message" => "Товары не найдены"), JSON_UNESCAPED_UNICODE);
}