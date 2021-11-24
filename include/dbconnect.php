<?php
set_time_limit(0);
try {
  $db = new \PDO("mysql:host=localhost;dbname=myres;charset=utf8", "root", "", [
    // $db = new \PDO("mysql:host=localhost;dbname=codebuz_transferbook;charset=utf8", "codebuz_transferbook", "transferbook", [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
} catch (\PDOException $e) {
  echo "Error connecting to mysql: " . $e->getMessage();
}
?>