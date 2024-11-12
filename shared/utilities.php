<?php

class Utilities
{
    public function getPaging($page, $total_rows, $records_per_page, $page_url)
    {
        // массив пагинации
        $paging_arr = array();

        // кнопка для первой страницы
        $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";

        // подсчёт всех товаров в базе данных для подсчета общего количества страниц
        $total_pages = ceil($total_rows / $records_per_page);

        // диапазон ссылок для показа
        $range = 2;

        // отображать диапазон ссылок вокруг текущей страницы
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        $paging_arr["pages"] = array();
        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            // убедимся, что $x > 0 И $x <= $total_pages
            if (($x > 0) && ($x <= $total_pages)) {
                $paging_arr["pages"][$page_count]["page"] = $x;
                $paging_arr["pages"][$page_count]["url"] = "{$page_url}page={$x}";
                $paging_arr["pages"][$page_count]["current_page"] = $x == $page ? "yes" : "no";
                $page_count++;
            }
        }

        // кнопка для последней страницы
        $paging_arr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";

        // формат json
        return json_encode($paging_arr);
    }
}