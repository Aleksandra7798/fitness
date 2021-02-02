<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zarządzanie rezerwacjami</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Aleksandra Wesolowska">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
    <link rel="stylesheet" href="css/main.css">
    <?php

    require 'lib/phpPasswordHashing/passwordLib.php';
    require 'app/DB.php';
    require 'app/Util.php';
    require 'app/models/StatusEnum.php';
    require 'app/models/RequirementEnum.php';
    require 'app/dao/CustomerDAO.php';
    require 'app/dao/BookingDetailDAO.php';
    require 'app/models/Customer.php';
    require 'app/models/Booking.php';
    require 'app/models/Reservation.php';
    require 'app/handlers/CustomerHandler.php';
    require 'app/handlers/BookingDetailHandler.php';

    $username = null;
    $isSessionExists = $isAdmin = false;
    $oczekujacaReservation = $potwierdzonaReservation = $totalCustomers = $totalReservations = null;
    $allBookings = $cCommon = $allCustomer = null;
    if (isset($_SESSION["username"]))
    {
        $username = $_SESSION["username"];
        $isSessionExists = true;

        $cHandler = new CustomerHandler();
        $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);

        $cAdmin = new Customer();
        $cAdmin->setEmail($cHandler->getEmail());

        // display all reservations
        $bdHandler = new BookingDetailHandler();
        $allBookings = $bdHandler->getAllBookings();
        $cCommon = new CustomerHandler();
        $allCustomer = $cCommon->getAllCustomer();

        // reservation stats
        $oczekujacaReservation = $bdHandler->getOczekujaca();
        $potwierdzonaReservation = $bdHandler->getPotwierdzona();
        $anulowanaReservation = $bdHandler->getAnulowana();
        $totalCustomers = $cCommon->totalCustomersCount();
        $totalReservations = count($bdHandler->getAllBookings());
    }
    if (isset($_SESSION["isAdmin"]) && isset($_SESSION["username"])) {
        $isSessionExists = true;
        $username = $_SESSION["username"];
        $isAdmin = $_SESSION["isAdmin"];
    }

    ?>
</head>

<body>
<header>
<div class="bg-dark collapse" id="navbarHeader" >
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h1 class="text-muted"><i class="bi bi-briefcase">Klub AleksFitness</i></h1>
                </div>

                <div class="col-sm-4 offset-md-1 py-4 text-right">
                    <?php if ($isSessionExists) { ?>
                    <h4 class="text-warning"><?php echo $username; ?></h4>
                    <ul class="list-unstyled">
                        <?php if ($isAdmin == 1) { ?>
                        <li><a href="admin.php" class="text-warning">Zarządzaj rezerwacjami klientów</a></li>
                        <li><a href="admin.php" class="text-warning">Zarządzaj treningami</a></li>
                        <?php } else { ?>
                        <li><a href="#" class="text-warning my-reservations">Moje rezerwacje</a></li>
                        <li>
                            <a href="#" class="text-warning" data-toggle="modal" data-target="#myProfileModal">Edytuj profil</a>
                        </li>
                        <?php } ?>
                        <li><a href="#" id="sign-out-link" class="text-warning">Wyloguj</a></li>
                    </ul>
                    <?php } else { ?>
                        <h3> <a href="zalogujsie.php" class="text-warning">Zaloguj się</a> <span class="text-warning">/</span>
                             <a href="rejestracja.php" class="text-warning">Zarejestruj się</a>
                        </h3>
                        <h5 style="color: gray">Zaloguj się, jeśli chcesz skorzystać z ofert naszego klubu.</h5>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <style>
     
    body {
      background-color: gainsboro;
    }
        .nav-link:hover {
        color: orange;
        background-color: #DCDCDC;
    }
    </style>
    </head>


    <div class="navbar navbar-dark bg-warning box-shadow">
        <div class="container-fluid d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <span style="color: black"><strong>Klub AleksFitness</strong></span>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>

<main role="main">

    <?php if ($isSessionExists && $isAdmin) { ?>
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-secondary o-hidden h-100">
                    <div class="card-body">
                    
                        <div class="mr-5">  Rezerwacje   <i class="fas fa-address-book"></i> <br/><?php echo $totalReservations; ?></div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#reservation">
                        <span class="float-left">Pokaż szczegóły</span>
                        <span class="float-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="mr-5">  Klienci  <i class="fas fa-users ml-2"></i> <br/><?php echo $totalCustomers; ?></div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#customers">
                        <span class="float-left">Pokaż szczegóły</span>
                        <span class="float-right"><i class="fa fa-angle-right"></i></span>
                    </a>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6 mb-3">
                <div class="card text-white bg-info o-hidden h-100">
                    <div class="card-body">
                        <div class="mr-4">  Potwierdzone rezerwacje  <i class="fas fa-check"></i> <br/> <?php echo $potwierdzonaReservation; ?> </div>
                    </div>
                    <div class="card-footer text-white clearfix small z-1"></div>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6 mb-3">
                <div class="card text-white bg-dark o-hidden h-100">
                    <div class="card-body">
                        <div class="mr-4">  Anulowane rezerwacje <i class="fas fa-times"></i> <br/> <?php echo $anulowanaReservation; ?> </div>
                    </div>
                    <div class="card-footer text-white clearfix small z-1"></div>
                </div>
            </div>

            <div class="col-xl-2 col-sm-6 mb-3">
                <div class="card text-white bg-danger o-hidden h-100">
                    <div class="card-body">
                        <div class="mr-5"> Oczekujące rezerwacje <i class="fa fa-fw fa-support"></i> <br/><?php echo $oczekujacaReservation; ?></div>
                    </div>
                    <div class="card-footer text-white clearfix small z-1"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="tableContainer">
    <a href="grafik.php" type="button" class="btn btn-outline-secondary" style="float:right" >Grafik</a>

        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="reservation-tab" data-toggle="tab" href="#reservation" role="tab"
                   aria-controls="reservation" aria-selected="true"><b>Rezerwacje</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers" role="tab"
                   aria-controls="customers" aria-selected="false"><b>Klienci</b></a>
            </li>
            

        </ul>
        <div class="tab-content py-3" id="adminTabContent">
            <div class="tab-pane fade show active" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">
                <table id="reservationDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr style="background-color:orange">
                        <th scope="col">#</th>
                        <th class="text-hide p-0" data-bookId="12">12</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Data rozpoczęcia</th>
                        <th scope="col">Data zakończenia</th>
                        <th scope="col">Rodzaj treningu</th>
                        <th scope="col">Typ treningu</th>
                        <th scope="col">Kadra</th>
                        <th scope="col">Dodatkowe usługi</th>
                        <th scope="col">Data rezerwacji</th>
                        <th scope="col">Status rezerwacji</th>
                        <th scope="col">Notatka</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($allBookings)) { ?>
                        <?php   foreach ($allBookings as $k => $v) { ?>
                            <tr>
                                <th scope="row"><?php echo ($k + 1); ?></th>
                                <td class="text-hide p-0" data-id="<?php echo $v["id"]; ?>">
                                    <?php echo $v["id"]; ?>
                                </td>
                                <?php $cid = $v["cid"]; ?>
                                <td><?php echo $cCommon->getCustomerObjByCid($cid)->getEmail(); ?></td>
                                <td><?php echo $v["start"]; ?></td>
                                <td><?php echo $v["end"]; ?></td>
                                <td><?php echo $v["type"]; ?></td>
                                <td><?php echo $v["requirement"]; ?></td>
                                <td><?php echo $v["cadre"]; ?></td>
                                <td><?php echo $v["service"]; ?></td>
                                <td><?php echo $v["timestamp"]; ?></td>
                                <td><?php echo $v["status"]; ?></td>
                                <td><?php echo $v["memo"]; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="my-3">
                    <div class="row">
                        <div class="col-6">
                            <label class="text-black font-weight-bold">Po wybraniu:</label>
                            <button type="button" id="confirm-booking" class="btn btn-warning">Potwierdź
                            </button>
                            <button type="button" id="cancel-booking" class="btn btn-secondary">Anuluj
                            </button>
                        </div>
                        <div class="col-6 text-right">
                        <label class="text-black font-weight-bold">Pokaż rezerwacje:  </label>
                            <input type="radio" name="viewOption" value="potwierdzona">&nbsp;Potwierdzone &nbsp;
                            <input type="radio" name="viewOption" value="anulowana">&nbsp;Anulowane &nbsp;
                            <input type="radio" name="viewOption" value="oczekująca">&nbsp;W oczekiwaniu &nbsp;
                            <input type="radio" name="viewOption" value="all">&nbsp;Wszystko
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                <table id="customerTable" class="table table-striped table-bordered">
                    <thead>
                    <tr style="background-color:orange">
                        <th scope="col">#</th>
                        <th scope="col">Imię i nazwisko</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Nr telefonu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($allCustomer)) { ?>
                        <?php foreach ($cCommon->getAllCustomer() as $key => $value) { ?>
                        <tr>
                            <td scope="row"><?php echo ($key + 1); ?></td>
                            <td><?php echo $value->getFullName(); ?></td>
                            <td><?php echo $value->getEmail(); ?></td>
                            <td><?php echo $value->getPhone(); ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potwierdź wybrane rezerwacje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Zamknij">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Czy na pewno chcesz kontynuować to działanie?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="confirmTrue">Tak</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nie</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anuluj wybrane rezerwacje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Zamknij">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Czy na pewno chcesz kontynuować to działanie?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="cancelTrue">Tak</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nie</button>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>

</main>

<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/popper.js/dist/popper.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
        integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="js/form-submission.js"></script>
<script src="js/admin.js"></script>
</body>
</html>