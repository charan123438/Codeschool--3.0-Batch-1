<?php

require("./utils/function.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    sendResponse(false, "Invalid request method");
}

if (!isset($_POST["name"])) {
    sendResponse(false, "Name is required");
}
if (!isset($_POST["mobile"])) {
    sendResponse(false, "mobile is required");
}
if (!isset($_POST["email"])) {
    sendResponse(false, "Email is required");
}
if (!isset($_POST["password"])) {
    sendResponse(false, "Password is required");
}

$name = $_POST["name"];
$email = $_POST["email"];
$password = md5($_POST["password"]);
$mobile = $_POST["mobile"];

if (strlen($name) < 3 || strlen($email) > 40) {
    sendResponse(false, "Name must be at least 3 characters and at most 25 characters");
}

$pdo = connect();

$query = "SELECT * FROM task_register WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam("email", $email, PDO::PARAM_STR);

$stmt->execute();
if ($stmt->rowCount() > 0) {
    sendResponse(false, "Email already exists");
}


$query = "INSERT INTO task_register (name, email, password,mobile) VALUES (:name, :email, :password,:mobile)";

$stmt = $pdo->prepare($query);
$stmt->bindParam("name", $name, PDO::PARAM_STR);
$stmt->bindParam("email", $email, PDO::PARAM_STR);
$stmt->bindParam("password", $password, PDO::PARAM_STR);
$stmt->bindParam("mobile",$mobile,PDO::PARAM_STR);

$stmt->execute();
if ($stmt->rowCount() > 0) {
    sendResponse(true, "Registered Successfully");
}

sendResponse(false, "User registration failed");