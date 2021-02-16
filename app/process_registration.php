<?php

require '../lib/phpPasswordHashing/passwordLib.php';
require 'DB.php';
require 'Util.php';
require 'dao/CustomerDAO.php';
require 'models/Customer.php';
require 'handlers/CustomerHandler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitBtn"])) {
    $errors_ = null;

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors_ .= Util::displayAlertV1("Proszę wpisać poprawny adres e-mail.", "warning");
    }
    if (strlen($_POST["password"]) < 4 || strlen($_POST["password2"]) < 4) {
        $errors_ .= Util::displayAlertV1("Wymagane hasło składające się z co najmniej 4 znaków", "warning");
    }
    if (strlen($_POST["phoneNumber"]) < 9 || strlen($_POST["phoneNumber"]) < 9) {
        $errors_ .= Util::displayAlertV1("Numer telefonu musi się składać z co najmniej 9 znaków", "warning");
    }
    if (!empty($_POST["password"]) && !empty($_POST["password2"])) {
        if ($_POST["password"] != $_POST["password2"]) {
            $errors_ .= Util::displayAlertV1("Hasło nie pasuje. Spróbuj jeszcze raz", "warning");
        }
    }

    if (!empty($errors_)) {
        echo $errors_;
    } else {
        $customer = new Customer();
        $customer->setFullName($_POST["fullName"]);
        $customer->setEmail($_POST["email"]);
        $customer->setPhone($_POST["phoneNumber"]);
        $customer->setPassword($_POST["password"]);

        $handler = new CustomerHandler();
        $handler->insertCustomer($customer);
        echo Util::displayAlertV1($handler->getExecutionFeedback(), "info");
    }
}

