<?php
require_once('dbpw.php');
$conn = new PDO("mysql:host=localhost;dbname=vateir_statistics", 'vateir', $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);#
