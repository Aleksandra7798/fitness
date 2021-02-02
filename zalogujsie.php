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

    <title>Zaloguj się</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Aleksandra Wesolowska">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .profile-img-card {
        width: 115px;
        height: 115px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }

body{
	background-image:url(image/logowanie.jpg)
}

#login{
background-color:rgba(13,13,13,0.2);
min-height:500px;
padding: 25px 70px 20px 70px;
box-shadow: -10px -10px 10px  10px orange;
}



    
    </style>
</head>

<body>
    <div class="container py-5 ">
        <div class="row">
             <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 mx-auto" id="login">
                        <div class="card-header">
                             <h2 class="text-center mb-5" ><b>Logowanie - Klub AleksFitness</b></h2>
                             <h2 class="mb-0 my-2 text-center"><b>Zaloguj się</b></h2>
                        </div>
                       
                        <div class="card-body">
                            <form class="form" role="form" autocomplete="off" id="login-form" method="post">
                            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"/>

                                <div class="form-group">
                                    <label for="loginEmail" style="color:white">E-mail</label>
                                    <span class="red-asterisk"> *</span>
                                    <input type="text" class="form-control"
                                           id="loginEmail"
                                           name="loginEmail"
                                           placeholder="E-mail"
                                           required>
                                </div>
                               
                                <div class="form-group">
                                    <label for="loginPassword" style="color:white">Hasło</label>
                                    <span class="red-asterisk"> *</span>
                                    <input type="password" class="form-control"
                                           id="loginPassword"
                                           name="loginPassword"
                                           placeholder="Hasło"
                                           required>
                                           
                                </div>
                                
                                <div class="form-group">
                                    <p style="color:white">Jeśli jesteś niezarejestrowany - <a href="rejestracja.php" class="btn btn-outline-info">Zarejestruj się tutaj</a>
                                </div>
                                
                                <a  href="index.php" class="btn btn-secondary">Strona Główna</a>
                                    <input type="submit" class="btn btn-warning btn-md float-right"
                                    value="Zaloguj się" name="loginSubmitBtn">
                                
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