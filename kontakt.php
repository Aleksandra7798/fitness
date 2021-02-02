<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Aleksandra Wesolowska">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <?php
    require 'lib/phpPasswordHashing/passwordLib.php';
    require 'app/DB.php';
    require 'app/Util.php';
    require 'app/dao/CustomerDAO.php';
    require 'app/dao/BookingDetailDAO.php';
    require 'app/models/RequirementEnum.php';
    require 'app/models/Customer.php';
    require 'app/models/Booking.php';
    require 'app/models/Reservation.php';
    require 'app/handlers/CustomerHandler.php';
    require 'app/handlers/BookingDetailHandler.php';

    $username = $cHandler = $bdHandler = $cBookings = null;
    $isSessionExists = false;
    $isAdmin = 0;
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];

        $cHandler = new CustomerHandler();
        $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);
        $cAdmin = new Customer();
        $cAdmin->setEmail($cHandler->getEmail());

        $bdHandler = new BookingDetailHandler();
        $cBookings = $bdHandler->getCustomerBookings($cHandler);
        $isSessionExists = true;
    }
    if (isset($_SESSION["isAdmin"]) && isset($_SESSION["username"])) {
        $isSessionExists = true;
        $username = $_SESSION["username"];
        $isAdmin = $_SESSION["isAdmin"];
    }
    ?>
     <title>Kontakt z klubem fitness</title>
   
</head>

<body>
<header>
<div class="bg-dark collapse" id="navbarHeader" >
        <div class="container">
            <div class="row">
            <div class="col-sm-6 col-md-6 py-2">
                <img src="image/logoaleksfitness.png" width="220" height="170">
                </div>

                <div class="col-sm-4 offset-md-2 py-4">
                <?php if ($isSessionExists) { ?>
                    <h3 class="text-warning"><?php echo $username; ?></h3>
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
                        <h3> <a href="zalogujsie.php" class="text-warning" >Zaloguj się</a> <span class="text-warning">/</span>
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

    strong:hover {
        color: dimgray;
    }

    h1, p {
        font-weight:bold;
    }
    .profile-img-card {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
    
    :root {
        --color: goldenrod;
    }

    .kontakt-animation {
        display:inline-block;
        font-size: 5em;
        font-weight: 750;
    }

    .kontakt-animation::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        color: var(--color);
        overflow: hidden;
        width: 100%;
        animation: kontakt 12s linear infinite;
    }
    /* Animacja */
    @keyframes kontakt {
        50% {
            width: 70%;
        } 
        
        100% {
            width: 100%;
        }
    }   

    .dropdown-item:hover {
        color: orange;
        background-color: #DCDCDC;
    }
    label {
      background-color:rgb(127, 255, 0, 0.3);
      border-radius: 4px;
    }
</style>
</head>


<div class="navbar navbar-dark bg-warning box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <span style="color: black"><strong>Klub AleksFitness</strong></span>
            </a>
			
            <a href="zajecia.php" class="nav-link active ">
                <span style="color: black"><strong>Zajęcia</strong></span>
            </a>
			
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span style="color: black"><strong>Kadra</strong></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="trenerzy.php">Trenerzy</a>
                    <a class="dropdown-item" href="instruktorzy.php">Instruktorzy</a>
                </div>
            </div>

            <a href="uslugi.php" class="nav-link active">
                <span style="color: black"><strong>Usługi</strong><span>
            </a>

            <a href="grafik.php" class="nav-link active">
                <span style="color: black"><strong>Grafik</strong></span>
            </a>
		
            <a href="onas.php" class="nav-link active">
                <span style="color: black"><strong>O nas</strong><span>
            </a>

            <a href="kontakt.php" class="nav-link active">
                <span style="color: black"><strong>Kontakt</strong><span>
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>  
    
    <div class="container my-3" id="my-reservations-div">
        <h4>Rezerwacje</h4>
        <table id="myReservationsTbl" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th class="text-hide p-0" data-bookId="12">12</th>
                <th scope="col">Data rozpoczęcia</th>
                <th scope="col">Data zakończenia</th>
                <th scope="col">Rodzaj treningu</th>
                <th scope="col">Typ treningu</th>
                <th scope="col">Kadra</th>
                <th scope="col">Dodatkowe usługi</th>
                <th scope="col">Notatka</th>
                <th scope="col">Data rezerwacji</th>
                <th scope="col">Status rezerwacji</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($cBookings) && $bdHandler->getExecutionFeedback() == 1) { ?>
                <?php   foreach ($cBookings as $k => $v) { ?>
                    <tr>
                        <th scope="row"><?php echo ($k + 1); ?></th>
                        <td class="text-hide p-0"><?php echo $v["id"]; ?></td>
                        <td><?php echo $v["start"]; ?></td>
                        <td><?php echo $v["end"]; ?></td>
                        <td><?php echo $v["type"]; ?></td>
                        <td><?php echo $v["requirement"]; ?></td>
                        <td><?php echo $v["cadre"]; ?></td>
                        <td><?php echo $v["service"]; ?></td>
                        <td><?php echo $v["memo"]; ?></td>
                        <td><?php echo $v["timestamp"]; ?></td>
                        <td><?php echo $v["status"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</header>


<main role="main">


<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="image/kontakt.jpg" class="d-block w-100" alt="Kontakt">
      <div class="carousel-caption d-none d-md-block">
      
      <section class="fitness">
        <div class="kontakt-animation" data-text="Klub AleksFitness">
        <br/>
            <h1 class="display-3"><b>Skontaktuj się z nami.</b></h1>
        </div>
    </section>
<br/> 

<div class="container">
    <div class="pricing-header px-5 py-5 pt-md-5 pb-md-4 mx-auto text-center">
	<br/>
        <h1>Adres:</h1>
        <p>ul. Warszawska 26</p>
		<p> 25-043 Kielce, Polska</p> <br/>
        
        <h1>Dane kontaktowe:</h1>
        <p>+48 721 631 808</p>
		<p>+48 661 654 221</p>
        <p>aleksfitness@gmail.com</p> <br/>
            
        <h1>Godziny otwarcia:</h1>
        <p>Pon. - Pt.: 09:00 - 22:00</p>
        <p>Sob. - Niedz.: 10:00 - 19:00</p>
    </div>
</div>
</div>
    
    
<div class="modal sign-in-to-book-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Prosimy sie zalogować!</h5>
                </div>
                <div class="modal-body">
                    <p>Najpierw prosimy o zalogowanie się do systemu, jeśli chcesz skorzystać z ofert naszego klubu.</p>
                </div>
                    <div class="modal-footer">
                    <a href="zalogujsie.php" type="button" class="btn btn-warning" >Zaloguj się</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal" id="myProfileModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edytuj profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <?php if ($isSessionExists) { ?>
                            <form class="form" role="form" autocomplete="off" id="update-profile-form" method="post">
                                <input type="number" id="customerId" hidden
                                       name="customerId" value="<?php echo $cHandler->getId(); ?>" >
                                <div class="form-group">
                                    <label for="updateFullName">Imię i Nazwisko</label>
                                    <input type="text" class="form-control" id="updateFullName"
                                           name="updateFullName" value="<?php echo $cHandler->getFullName(); ?>" >
                                </div>
                                <div class="form-group">
                                    <label for="updatePhoneNumber">Nr telefonu</label>
                                    <input type="text" class="form-control" id="updatePhoneNumber"
                                           name="updatePhoneNumber" value="<?php echo $cHandler->getPhone(); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="updateEmail">E-mail</label>
                                    <input type="email" class="form-control" id="updateEmail"
                                           name="updateEmail" value="<?php echo $cHandler->getEmail(); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="updatePassword">Nowe hasło</label>
                                    <input type="password" class="form-control" id="updatePassword"
                                           name="updatePassword"
                                           title="At least 4 characters with letters and numbers">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning btn-md float-right"
                                           name="updateProfileSubmitBtn" value="Zmień">
                                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>


<script src="js/utilityFunctions.js"></script>
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
        integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="js/animatejscx.js"></script>
<script src="js/form-submission.js"></script>
<script>
    $(document).ready(function () {
      let reservationDiv = $("#my-reservations-div");
      reservationDiv.hide();
      $(".my-reservations").click(function () {
        reservationDiv.slideToggle("slow");
      });
      $('#myReservationsTbl').DataTable();

      
      $('.book-now-modal-lg').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let treningType = button.data('rtype');
        let modal = $(this);
        modal.find('.modal-body select[name=treningType]').val(treningType);
      });

      
      $('[data-toggle="popover"]').popover();

    });
</script>
<script src="js/multiStepsRsvn.js"></script>
</body>
</html>