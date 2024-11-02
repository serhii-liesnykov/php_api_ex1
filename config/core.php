<?php

// Fehlermeldungen anzeigen
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Homepage-URL
$home_url = "http://localhost/api/";

// Die Seite wird im URL-Parameter angegeben, die Standardseite ist eine
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

// Festlegen der Anzahl der Datensätze pro Seite
$records_per_page = 5;

// Berechnung für Datensatzlimitabfrage
$from_record_num = ($records_per_page * $page) - $records_per_page;
