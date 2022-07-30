<?php
// Database Connection
$dsn = "mysql:host=localhost;port=3306;dbname=test;charset=utf8mb4";
$user = "root" ; // root at home
$pass = "" ; 

try {
   $db = new PDO($dsn, $user, $pass) ; 
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
} catch(PDOException $ex) {
  echo "Connection Problem" ; 
  exit ; 
}

function getTag($team) {
    global $db;
    $stmt = $db->prepare("select * from tags where team = ?") ;
    $stmt->execute([$team]) ;
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ; 
}