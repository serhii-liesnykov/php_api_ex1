<?php

class Utilities
{
    public function getPaging($page, $total_rows, $records_per_page, $page_url)
    {
        // Paginierungsarray
        $paging_arr = array();

        // Schaltfläche für die erste Seite
        $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";

        // Zählen aller Produkte in der Datenbank, um die Gesamtzahl der Seiten zu zählen
        $total_pages = ceil($total_rows / $records_per_page);

        // Auswahl an anzuzeigenden Links
        $range = 2;

        // Zeigt eine Reihe von Links rund um die aktuelle Seite an
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        $paging_arr["pages"] = array();
        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            // Stellen wir sicher, dass $x > 0 UND $x <= $total_pages
            if (($x > 0) && ($x <= $total_pages)) {
                $paging_arr["pages"][$page_count]["page"] = $x;
                $paging_arr["pages"][$page_count]["url"] = "{$page_url}page={$x}";
                $paging_arr["pages"][$page_count]["current_page"] = $x == $page ? "yes" : "no";
                $page_count++;
            }
        }

        // Schaltfläche für die letzte Seite
        $paging_arr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";

        // JSON-Format
        return json_encode($paging_arr);
    }
}
