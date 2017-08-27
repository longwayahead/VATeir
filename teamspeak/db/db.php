<?php
require_once('dbpw.php');
$conn = new PDO("mysql:host=localhost;dbname=vateir_teamspeak", 'vateir', $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
