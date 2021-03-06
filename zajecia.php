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
    
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
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
    <title>Zajęcia w klubie fitness</title>
    
 </head>

<body>
<header>

<div class="bg-dark collapse" id="navbarHeader">
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
                        <h3> <a href="zalogujsie.php" class="text-warning">Zaloguj się</a> <span class="text-warning"> /</span>
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

    img#zajecia:hover{
      opacity : 0.70;
      filter : alpha(opacity=70);
    }

    #zajecia{
      -webkit-transition: all 1s ease; 
      -moz-transition: all 1s ease; 
      -o-transition: all 1s ease;
    }   

    #zajecia:hover {
      -o-transition: all 0.6s;
      -moz-transition: all 0.6s;
      -webkit-transition: all 0.6s;
      -moz-transform: scale(1.1);
      -o-transform: scale(1.1);
      -webkit-transform: scale(1.1);
  }
    .dropdown-item:hover {
        color: orange;
        background-color: #DCDCDC;
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

<section class="fitness text-center">
        <div class="container pt-lg-4 pl-4 px-4">
          <h1 class="display-3"><b>Zajęcia w klubie AleksFitness</b></h1><br/>
            <p class="lead">Zobacz nasze zajęcia treningowe i wybierz coś dla Siebie. <br/> Oferujemy 12 różnych treningów.</p>
            
            <p>
			        <?php if ($isSessionExists) { ?>
                <a href="#" class="btn btn-secondary my-2" data-toggle="modal" data-target=".book-now-modal-lg">Zarezerwuj teraz</a>
                <?php } else { ?>
                <a href="#" class="btn btn-secondary my-2" data-toggle="modal" data-target=".sign-in-to-book-modal">Zarezerwuj teraz</a>
              <?php } ?>
            </p>
        </div>
</section>


<div class="row row-cols-1 row-cols-md-3 g-4">
  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/joga.jpg" class="card-img-top" width="400" height="320" alt="Zajecia Joga"/>
      <div  class="card-body">
        <h5 class="card-title">Joga</h5>
          <ul>
            <li>Równoczesna praca ciała i umysłu</li>
            <li>Przywrócenie prawidłowego napięcia mięśniowego</li>
            <li>Złagodzenie bólu i objawów chorób</li>
            <li>Poprawienie regulacji pracy serca i ciśnienia krwi </li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 15 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/pilates.jpg" class="card-img-top" width="400" height="320" alt="Zajecia Pilates"/>
      <div class="card-body">
        <h5 class="card-title">Pilates</h5>
          <ul>
            <li>Wzmocnienie mięśni i uelastycznienie postawy</li>
            <li>Przywrócenie prawidłowego napięcia mięśniowego</li>
            <li>Odciążenie kręgosłupa </li>
            <li>Ćwiczenia dla każdego niezależnie od wieku</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 10 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/zumba.jpg" class="card-img-top" width="400" height="320" alt="Zajecia Zumba"/>
      <div class="card-body">
        <h5 class="card-title">Zumba</h5>
          <ul>
            <li>Energiczna muzyka, która zachęca do treningu</li>
            <li>Proste kroki taneczne</li>
            <li>Skuteczna utrata kalorii</li>
            <li>Poprawa kondycji fizycznej</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 12 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h</span>
      </div>
    </div>
  </div>



  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/liny.jpg" class="card-img-top" width="400" height="320" alt="Zajecia trening obwodowy"/>
      <div class="card-body">
        <h5 class="card-title">Trening obwodowy</h5>
          <ul>
            <li>Spalanie tkanki tłuszczowej i wyrzeźbienie mięśni</li>
            <li>Ćwiczenia na całe ciało, mnóstwo spalanych kalorii</li>
            <li>Spory wydatek energetyczny podczas treningu</li>
            <li>Przyśpieszenie metabolizmu</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 20 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h</span>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/pump.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia Body Pump"/>
      <div class="card-body">
        <h5 class="card-title">Body pump</h5>
          <ul>
            <li>Zwiększenie siły mięśniowej i wytrzymałości</li>
            <li>Można dostosować obciążenie do swoich możliwości</li>
            <li>Wyrzeźbiony brzuch, mocne zgrabne pośladki</li>
            <li>Pomaga w walce z cellulitem</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 20 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h</span>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/fitball.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia Fit Ball"/>
      <div class="card-body">
        <h5 class="card-title">FitBall</h5>
          <ul>
            <li>Połączenie zabawy z dużymi lub małymi piłkami i fitnessu</li>
            <li>Praca nad wzmocnieniem różnych partii mięśniowych</li>
            <li>Poprawa ruchomości stawów</li>
            <li>Doskonała forma aktywności dla kobiet w ciąży</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 15 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>
      </div>
    </div>
  </div>


  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/aerobik.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia Aerobik"/>
      <div class="card-body">
        <h5 class="card-title">Aerobik</h5>
          <ul>
            <li>Zajęcia w sali w rytmie muzyki (poziom trudności różny)</li>
            <li>Zwiększenie wydolności organizmu</li>
            <li>Skuteczne spalenie tłuszczu i kształtowanie sylwetki</li>
            <li>Doskonała forma aktywności dla kobiet w ciąży</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 10 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/aquaareobik.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia Aqua Aerobik"/>
      <div class="card-body">
        <h5 class="card-title">Aqua Aerobik</h5>
          <ul>
            <li>Świetna odmiana zajęć dla osób kochających wodę</li>
            <li>Ćwiczenia odbywają się w płytkim basenie.</li>
            <li>Ćwiczenia dobrane tak, aby wykorzystać opór wody</li>
            <li>Dużą zaletą jest to, że nie obciąża stawów.</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 20 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>          
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/kregoslup.jpg" class="card-img-top" width="400" height="320" alt="Zajecia zdrowy kregoslup"/>
      <div class="card-body">
        <h5 class="card-title">Zdrowy kręgosłup</h5>
          <ul>
            <li>Wykonywane kolejno po sobie ćwiczeń, które mają na celu poprawienie postawy ciała</li>
            <li>Wykonywane bardzo precyzyjnie, powoli i dokładnie.</li>
            <li>Prowadzone przy spokojnej muzyce, która ma odprężyć.</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 10 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span>  
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/fitboxing.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia FitBoxing"/>
      <div class="card-body">
        <h5 class="card-title">FitBoxing</h5>
          <ul>
            <li>Połączenie treningu cardio oraz treningu kondycyjnego</li>
            <li>Zawiera elementy boksu, kick boxingu i muay thai</li>
            <li>Wzmocnienie całego ciała</li>
            <li>Uczy umiejętności przezwyciężania zmęczenia i bólu.</li>
            <li>Poprawia wytrzymałość i elastyczność.</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 20 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span> 
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/kalistenika.jpg" class="card-img-top" width="400" height="320" alt="Zajęcia Kalistenika"/>
      <div class="card-body">
        <h5 class="card-title">Kalistenika</h5>
          <ul>
            <li>Poprawienie wytrzymałości siłowej</li>
            <li>Systematycznie wykonywanie prowadzi do zwiększenia siły generowanej przez mięśnie oraz wzrostu masy mięśniowej</li>
            <li>Poprawia koordynację ruchową oraz gibkość</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 20 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span> 
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card">
      <img id="zajecia" src="image/stretch.jpg" class="card-img-top" width="400" height="320" alt="Zajecia Brzuch + Stretch"/>
      <div class="card-body">
        <h5 class="card-title">Brzuch + stretch</h5>
        <ul>
            <li>Wykonywanie ćwiczeń w kontrolowany sposób (łagodnie)</li>
            <li>Angażowanie przede wszystkim mięśni brzucha</li>
            <li>Uelastycznienie mięśni i ścięgien</li>
            <li>Fundamentem ćwiczeń jest wyprostowana postawa</li>
          </ul>
          <span class="badge bg-warning text-dark" style="float:right">Cena: 12 zł</span> <br/>
          <span class="badge bg-warning text-dark" style="float:right">Czas trwania: 1h </span> 
      </div>
    </div>
  </div>
  </div>

 
</html>

    
<div class="modal fade book-now-modal-lg" tabindex="-1" role="dialog" aria-labelledby="bookNowModalLarge" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: rgba(192,192,192,1)">
                <div class="modal-header" >
                    <h4 class="modal-title"><strong >Rezerwacja - AleksFitness</strong> <i class="fas fa-laptop"></i></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="reservationModalBody">
                    <?php if ($isSessionExists == 1 && $isAdmin == 0) { ?>
                        <form role="form" autocomplete="off" method="post" id="multiStepRsvnForm">
                            <div class="rsvnTab">
                                <?php if ($isSessionExists) { ?>
                                    <input type="number" isForTest="false" name="cid" value="<?php echo $cHandler->getId() ?>" hidden>
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="startDate" class="col-sm-4 col-form-label font-weight-bold">Dzień rozpoczęcia treningu
                                        <span class="red-asterisk"> *</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" class="form-control"
                                                   name="startDate" isForTest="false" min="<?php echo Util::dateToday('0'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="endDate" class="col-sm-4 col-form-label font-weight-bold" >Dzień zakończenia treningu
                                        <span class="red-asterisk"> *</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" class="form-control" isForTest="false" min="<?php echo Util::dateToday('1'); ?>" name="endDate" required>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="treningType " >Rodzaj treningu <i class="fas fa-arrow-circle-right"></i>
                                        <span class="red-asterisk"> *  </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select required class="custom-select mr-sm-2" isForTest="false" name="treningType">
                                            <option value="<?php echo \models\RequirementEnum::JOGA; ?>">Joga</option>
                                            <option value="<?php echo \models\RequirementEnum::PILATES; ?>">Pilates</option>
                                            <option value="<?php echo \models\RequirementEnum::ZUMBA; ?>">Zumba</option>
                                            <option value="<?php echo \models\RequirementEnum::TRENING_OBWODOWY; ?>">Trening obwodowy</option>
                                            <option value="<?php echo \models\RequirementEnum::BODY_PUMP; ?>">Body pump</option>
                                            <option value="<?php echo \models\RequirementEnum::FITBALL; ?>">FitBall</option>
                                            <option value="<?php echo \models\RequirementEnum::AEROBIK; ?>">Aerobik</option>
                                            <option value="<?php echo \models\RequirementEnum::AQUA_AEROBIK; ?>">Aqua aerobik</option>
                                            <option value="<?php echo \models\RequirementEnum::ZDROWY_KREGOSLUP; ?>">Zdrowy kręgosłup</option>
                                            <option value="<?php echo \models\RequirementEnum::FITBOXING; ?>">FitBoxing</option>
                                            <option value="<?php echo \models\RequirementEnum::KALISTENIKA; ?>">Kalistenika</option>
                                            <option value="<?php echo \models\RequirementEnum::BRZUCH_STRETCH; ?>">Brzuch + stretch</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="treningRequirement" >Typ treningu <i class="fa fa-users"></i> <i class="fa fa-street-view"></i></label>
                                    <div class="col-sm-9">
                                        <select class="custom-select mr-sm-2" isForTest="false" name="treningRequirement">
                                            <option selected value="Grupowy">Grupowy</option>
                                            <option value="Indywidualny">Indywidualny</option>
                                        </select>
                                    </div>
                                    </div>


                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="cadre">Kadra  <i class="fa fa-user-circle"></i></label>
                                    <div class="col-sm-9">
                                        <select required class="custom-select mr-sm-2"isForTest="false" name="cadre">
                                            <option selected value="Brak">Brak</option>
                                            <option value="Trener personalny">Trener personalny</option>
                                            <option value="Instruktor">Instruktor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="service">Dodatkowe usługi <i class="fa fa-plus-square"></i></label>
                                    <div class="col-sm-9">
                                        <select class="custom-select mr-sm-2" isForTest="false" name="service">
                                            <option selected value="-">-</option>
                                            <option value="<?php echo \models\RequirementEnum::SZATNIA; ?>">Szatnia</option>
                                            <option value="<?php echo \models\RequirementEnum::MASAZ; ?>">Masaż</option>
                                            <option value="<?php echo \models\RequirementEnum::DIETETYK; ?>">Dietetyk</option>
                                            <option value="<?php echo \models\RequirementEnum::SAUNA; ?>">Sauna</option>
                                            <option value="<?php echo \models\RequirementEnum::BASEN; ?>">Basen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="specialMemo">Notatka <i class="fas fa-keyboard"></i></label>
                                    <div class="col-sm-9">
                                        <textarea rows="2" maxlength="500" isForTest="false" name="specialMemo" class="form-control"></textarea>
                                    </div>
                                </div>
                             
                            </div>

                            <div class="rsvnTab">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="bookedDate">Data rezerwacji <i class="fas fa-calendar-check"></i></label>
                                    <div class="col-sm-9 bookedDateTxt">
                                        Styczeń 01, 2021
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="treningPrice">Cena treningu <i class="fas fa-money-bill-alt"></i></label>
                                    <div class="col-sm-9 treningPriceTxt">45.00</div>
                                </div>
                                <p class="font-weight-bold">Podsumowanie karnetu:</p>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="numHours"><span class="numHoursTxt">3</span> godziny  <i class="fas fa-clock"></i></label>
                                    <div class="col-sm-9">
                                        <span class="treningPricePerHourTxt">15.00</span> zł za godzinę
                                    </div>
                                    <label class="col-sm-3 col-form-label font-weight-bold" for="numHours">Od - Do  <i class="far fa-calendar"></i></label>
                                    <div class="col-sm-9 fromToTxt">
                                        Pon. Styczeń 4 do Śr. Styczeń 6
                                    </div>
                                   
                                    <label class="col-sm-3 col-form-label font-weight-bold">Suma <i class="fas fa-piggy-bank"></i> </label>
                                    <div class="col-sm-9">
                                        <span class="totalTxt">0.00</span> zł
                                    </div>
                                </div>
                            </div>
                            <p style="font-size:13px; text-align:center;">Dni otwarte: <br/>Pn-Pt: 09:00 - 22:00 <br/> 
                             Sb-Ndz: 10:00 - 19:00 </p>

                            <div style="text-align:center;margin-top:40px;">
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>
                           

                        </form>
                        <div style="overflow:auto;">
                            <div style="float:right;">
                                <button type="button" class="btn btn-secondary" id="rsvnPrevBtn" onclick="rsvnNextPrev(-1)">Poprzedni</button>
                                <button type="button" class="btn btn-warning" id="rsvnNextBtn" onclick="rsvnNextPrev(1)" readySubmit="false">Wyślij</button>
                            </div>
                        </div>
                    <?php } else { ?>
                      <p style="color:red">Rezerwacja dotyczy wyłącznie klientów!</p>
                    <?php } ?>
                </div>

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
                                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
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