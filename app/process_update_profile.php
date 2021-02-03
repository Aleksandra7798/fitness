<?php

ob_start();
session_start();

require '../lib/phpPasswordHashing/passwordLib.php';
require 'DB.php';
require 'Util.php';
require 'dao/CustomerDAO.php';
require 'models/Customer.php';
require 'handlers/CustomerHandler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitBtn"])) {
    $errors_ = null;

    $tel = null;
    if (!empty($_POST["newPhone"])) {
        if (strlen($_POST["newPhone"]) < 9) {
            $errors_ .= Util::displayAlertV1("Wymagane są co najmniej 9 znaki.", "info");
        } else {
            $tel = $_POST["newPhone"];
        }
    } else {
        if (isset($_SESSION["phone"])) {
            $tel = $_SESSION["phone"];
        }
    } 

    $pwd = null;
    if (!empty($_POST["newPassword"])) {
        if (strlen($_POST["newPassword"]) < 4) {
            $errors_ .= Util::displayAlertV1("Wymagane są co najmniej 4 znaki.", "info");
        } else {
            $pwd = $_POST["newPassword"];
        }
    } else {
        if (isset($_SESSION["password"])) {
            $pwd = $_SESSION["password"];
        }
    }

    if (!empty($errors_)) {
        echo $errors_;
    } else {
        $c = new Customer();
        $c->setId($_POST["cid"]);
        $c->setFullName($_POST["fullName"]);
        $c->setPhone($tel);
        $c->setEmail($_POST["email"]);
        $c->setPassword($pwd);

        $cHandler = new CustomerHandler();
        $cHandler->updateCustomer($c);
        echo Util::displayAlertV1($cHandler->getExecutionFeedback(), "success");

        if (isset($_SESSION["username"])) {
            $_SESSION["username"] = $cHandler->getUsername($_POST["email"]);
        }
        
    }
}
