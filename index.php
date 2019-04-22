<?php
require __DIR__ . '/vendor/autoload.php';

use Forseti\Bot\Name\PageObject\DefaultPageObject;

$page = new DefaultPageObject();
$andamento = $page->getAndamento();
$futuros = $page->getFuturos();
$byId = $page->getById('10647');

echo "<pre>";
var_dump($byId);
die();