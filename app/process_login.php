<?php

ob_start();
session_start();



require 'DB.php';
require 'Util.php';
require 'dao/CustomerDAO.php';
require 'dao/AdminDAO.php';
require 'models/Customer.php';
require 'models/Admin.php';
require 'handlers/CustomerHandler.php';
require 'handlers/AdminHandler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitBtn"])) {
    $errors_ = null;

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors_ .= Util::displayAlertV1("Proszę wpisać poprawny adres e-mail", "warning");
    }
    if (empty($_POST["password"])) {
        $errors_ .= Util::displayAlertV1("Wymagane jest hasło.", "warning");
    }
    if (!empty($errors_)) {
        echo $errors_;
    } else {

       
        $adminHandler = new AdminHandler();
        $admin = new Admin();
       
        $admin->setEmail($_POST["email"]);
        $adminId = ($adminHandler->getObjectUtil($admin->getEmail())->getAdminId());

        if ($adminId > 1 || intval($adminId) > 0) {
            $_SESSION["username"] = $_POST["email"];
            $_SESSION["accountEmail"] = $_POST["email"];
            $_SESSION['isAdmin'] = 1;
            echo $_SESSION['isAdmin'];
        }
         else {
            $handler = new CustomerHandler();
            $customer = new Customer();
            $customer->setEmail($_POST["email"]);

            $newCustomer = new Customer();
            if (!$handler->isPasswordMatchWithEmail($_POST['password'], $customer)) {
                echo Util::displayAlertV1("Niepoprawne hasło.", "warning");
            } 
            else {
                $_SESSION["username"] = $handler->getUsername($_POST["email"]);
                $_SESSION["accountEmail"] = $customer->getEmail();
                $_SESSION["authenticated"] = 1;
                $_SESSION["password"] = $_POST["password"];

                // set the session phone number too
                if ($handler->getCustomerObj($_POST["email"])->getPhone()) {
                    $_SESSION["phoneNumber"] = $handler->getCustomerObj($_POST["email"])->getPhone();
                }
                echo $_SESSION["authenticated"];
            }
        }
    }
}