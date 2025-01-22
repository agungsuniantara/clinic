<?php
require_once '../model/database.php';
require_once 'controller.php';
require_once 'request.php';

$koneksi = new Database('localhost', 'root', '', 'klinik');
$controller = new Controller($koneksi);
$requestHandler = new RequestHandler($controller);
$requestHandler->handleRequest();
