<?php
ob_start();
session_start();

if (isset($_SESSION["authenticated"]))
{
    if ($_SESSION["authenticated"] == "1")
    {
        header("Location: index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Rejestracja do systemu</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Aleksandra Wesolowska">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
    body {
	    background-image:url(image/logowanie.jpg)
    }
  

    #rejestracja {
        background-color:rgba(13,13,13,0.2);
        min-height:500px;
        padding: 5px 65px 0px 65px;
        box-shadow: -10px -10px 10px  10px orange;
    }

    </style>
</head>

<body>


<div class="container py-5 ">
        <div class="row">
             <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 mx-auto" id="rejestracja">
                        <div class="card-header">
                             <h2 class="text-center mb-5" ><b>Rejestracja - Klub AleksFitness</b></h2>
                             <h2 class="mb-0 my-2 text-center"><b>Zarejestruj się</b></h2>
                        </div>

                        <div class="card-body">
                            <form class="form" role="form" autocomplete="off" id="registration-form" method="post">
                                <div class="form-group">
                                    <label for="registrationFullName" style="color:white"> Imię i Nazwisko</label>
                                    <input type="text" class="form-control"
                                           id="registrationFullName"
                                           name="registrationFullName"
                                           placeholder="Aleksandra Nowak">
                                </div>

                                <div class="form-group">
                                    <label for="registrationPhoneNumber" style="color:white">Numer telefonu</label>
                                    <input type="text" class="form-control"
                                           id="registrationPhoneNumber"
                                           name="registrationPhoneNumber"
                                           placeholder="(+48) 721-633-254">
                                </div>

                                <div class="form-group">
                                    <label for="registrationEmail" style="color:white">E-mail</label>
                                    <span class="red-asterisk"> * </span>
                                    <input type="email" class="form-control"
                                           id="registrationEmail"
                                           name="registrationEmail"
                                           placeholder="email@gmail.com" required="">
                                </div>

                                <div class="form-group">
                                    <label for="registrationPassword" style="color:white">Hasło</label>
                                    <span class="red-asterisk"> *</span>
                                    <input type="password" class="form-control"
                                           id="registrationPassword"
                                           name="registrationPassword"
                                           placeholder="Hasło"
                                           title="Co najmniej 4 znaki z literami i cyframi"
                                           required="">
                                </div>

                                <div class="form-group">
                                    <label for="registrationPassword2" style="color:white">Powtórz hasło</label>
                                    <span class="red-asterisk"> * </span>
                                    <input type="password" class="form-control"
                                           id="registrationPassword2"
                                           name="registrationPassword2"
                                           placeholder="Powtórz hasło" 
                                           required="">
                                </div>

                                <div class="form-group">
                                    <p style="color:white">Jeśli jesteś już zarejestrowany - <a href="zalogujsie.php" class="btn btn-outline-info"> Zaloguj się tutaj</a></p>
                                </div>
                                
                                    <a  href="index.php" class="btn btn-secondary">Strona główna</a>
                                    <input type="submit" class="btn btn-warning btn-md float-right" value="Zarejestruj się" name="registerSubmitBtn">
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/popper.js/dist/popper.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="js/form-submission.js"></script>
</body>
</html>