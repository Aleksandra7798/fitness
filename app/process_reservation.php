<?php

require 'DB.php';
require 'Util.php';
require 'dao/BookingReservationDAO.php';
require 'models/Booking.php';
require 'models/Reservation.php';
require 'models/Pricing.php';
require 'models/StatusEnum.php';
require 'handlers/BookingReservationHandler.php';



    if (empty($_POST["start"])) {
        $errors_ .= Util::displayAlertV1("Wybierz datę początkową.", "info");
    }
    if (empty($_POST["end"])) {
        $errors_ .= Util::displayAlertV1("Wybierz datę zakończenia.", "info");
    }
    if (!DateTime::createFromFormat('Y-m-d', $_POST["start"])) {
        $errors_ .= Util::displayAlertV1("Nieprawidłowa data rozpoczęcia.", "info");
    }
    if (!DateTime::createFromFormat('Y-m-d', $_POST["end"])) {
        $errors_ .= Util::displayAlertV1("Nieprawidłowa data zakończenia.", "info");
    }
    if (empty($_POST["type"])) {
        $errors_ .= Util::displayAlertV1("Wybierz rodzaj treningu.", "info");
    }
   
    try {
        $startDate = new DateTime($_POST["start"]);
        $endDate = new DateTime($_POST["end"]);
        if ($endDate <= $startDate) {
            $errors_ .= Util::displayAlertV1("Data zakończenia nie może być mniejsza ani równa dacie rozpoczęcia.", "info");
        }
    } catch (Exception $e) {
        $errors_ .= Util::displayAlertV1("Nieprawidłowy data", "info");
    }

    if (!empty($errors_)) {
        echo $errors_;
    } else {
        $r = new Reservation();
        $r->setCid($_POST["cid"]);
        $r->setStatus(\models\StatusEnum::OCZEKUJACA_STR);
        $r->setNotes(null);
        $r->setStart($_POST["start"]);
        $r->setEnd($_POST["end"]);
        $r->setType($_POST["type"]);
        $r->setRequirement($_POST["requirement"]);
        $r->setCadre($_POST["cadre"]);
        $r->setService($_POST["service"]);
        $r->setMemo($_POST["memo"]);
        $unique = uniqid();
        $r->setHash($unique);

        $p = new Pricing();
        $p->setBookedDate($_POST['bookedDate']);
        $p->setHours($_POST['numHours']);
        $p->setTotalPrice($_POST['totalPrice']);

        $brh = new BookingReservationHandler($r, $p);
        $brh->create();
        $out = array(
            "success" => "true",
            "response" => Util::displayAlertV2($brh->getExecutionFeedback(), "success")
        );
        echo json_encode($out, JSON_PRETTY_PRINT);
    }

