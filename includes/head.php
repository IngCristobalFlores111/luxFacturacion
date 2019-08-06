
<head>  <!--HEAD-->
    <title>Facturar Cliente</title>
    
    <!--MetaArea-->
    <meta charset="utf-8"> <!--Caracteres Codificados-->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Para Dispositivos Moviles habilita touch-zoom etc-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--/MetaArea-->
    
    <!--CSS-->
    <link href="https://fonts.googleapis.com/css?family=Caveat+Brush|Dosis|Indie+Flower|Lobster|Fjalla+One|Open+Sans+Condensed:300|Roboto|Sansita" rel="stylesheet">

    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->

    <script src="js/bootstrap.min.js"></script> 
   
    <link href="css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="icon" href="img/luxline1.ico" type="image/x-icon">
    <link href="css/stacktable.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.6/angular.min.js"></script>
<script src="js/bootbox.min.js"></script>
    <!--/CSS-->
    <style>
        .no-results{
            color:black;

        }
           #embededpdf { overflow: auto; -webkit-overflow-scrolling: touch; height: 250px; }
        select option {
    /*background-color: rgba(255,255,255,0.25);*/
        border-color: rgba(0,0,0,0.25);
        color:black;
}
        table.tablesaw
{
    table-layout: fixed;
    max-width: none;
    width: auto;
    min-width: 100%;
}
    </style>

    <!--Google+ api-->
    <script src="https://apis.google.com/js/api.js?onload=handleClientLoad"></script>
    <!--Google+ api-->
    <!--ApiLogIn-->
    <script>
        var ApiLogIn = function (btnSingIn, btnSignOut, msgContainer,phpLogin) {
            //var test = 10; private variable
            //var method = function(){} private method
            //this.test = 10; public variable
            //this.method = function(){} public method
            var self = this;
            var m_auth2 = null; // The Sign-In object.
            var m_btnSignIn = btnSingIn;
            var m_btnSignOut = btnSignOut;
            var m_message = msgContainer;
            var m_apiKey = 'accesssecurity-140019';
            var m_clientId = "1043488027097-f77grlc3h5cli90m6c0b4urra6jmcakn.apps.googleusercontent.com";
            var m_phpLogin = phpLogin;
            // Enter one or more authorization scopes. Refer to the documentation for
            // the API or https://developers.google.com/identity/protocols/googlescopes
            // for details.
            var m_scopes = 'profile https://www.googleapis.com/auth/userinfo.profile';
            var m_cookie_policy = 'single_host_origin';

            this.getStatus = function(){
                return m_auth2.isSignedIn.get();
            }

            this.signIn = function () {
                m_auth2.signIn();
            }

            this.logOut = function () {
                var User = m_auth2.currentUser.get();
                var l_RO = User.getAuthResponse();
                var access_token = l_RO.access_token;

                var location = (parseInt(screen.availWidth) / 2) - 290;
                var revokeUrl = window.open('https://accounts.google.com/Logout', "Logout", "left=" + location + ",top=10 width=580,height=800");
                revokeUrl.focus();
                setTimeout(function () {
                    revokeUrl.close();
                }, 500);
            }

            var updateSigninStatus = function (isSignedIn) { //Callback function
                if (isSignedIn) {
                    //*****************googleUser**************
                    //A GoogleUser object represents one user account.
                    var User = m_auth2.currentUser.get()
                    var profile = User.getBasicProfile();
                    //*****************googleUser**************
                    var user_id = profile.getId();

                    //console.log('ID: ' + user_id); // Do not send to your backend! Use an ID token instead.
                    //console.log('Name: ' + profile.getName());
                    //console.log('Image URL: ' + profile.getImageUrl());
                    //console.log('Email: ' + profile.getEmail());

                    // The ID token you need to pass to your backend:
                    var l_RO = User.getAuthResponse();

                    var id_token = l_RO.id_token;
                    //var access_token = l_RO.access_token;
                    //var log_hint = l_RO.login_hint;
                    //var scopes = l_RO.scope;
                    //var expire_time = l_RO.expires_in;
                    //var f_issued_at = l_RO.first_issued_at;
                    //var expires_at = l_RO.expires_at;

                    //console.log("ID Token: " + id_token);
                    //console.log("Access Token: " + access_token);
                    //console.log("Log Hint: " + log_hint);
                    //console.log("Scopes: " + scopes);
                    //console.log("Expire Time: " + expire_time);
                    //console.log("f_issued_at: " + f_issued_at);
                    //console.log("Expires at: " + expire_time);

                    $.post(m_phpLogin, { ID_TOKEN: id_token }, function (data, status) {
                        var indexer = parseInt(data);
                        switch (indexer) {
                            case 0:
                                if (window.location.href === "http://www.luxline.com.mx") {
                                    setTimeout(function () {
                                        window.location.href = "http://www.luxline.com.mx/testLuxFact";
                                    }, 1000);
                                }

                                if(m_btnSignIn !== null)
                                    $(m_btnSignIn).hide();

                                if(m_btnSignOut !== null)
                                    $(m_btnSignOut).show();
                                break;
                            case -1:
                                console.log("Token Invalido");
                                break;
                            case -2:
                                console.log("Estampa de tiempo expirada");
                                break;
                            case -3:
                                if(m_message !== null)
                                    $(m_message).html("<h3>El usuario no existe en la base de datos. Ingrese a su cuenta de Google+ con un usuario válido.</h3>");
                                else
                                    console.log("El usuario no existe en la base de datos. Ingrese a su cuenta de Google+ con un usuario válido.");

                                if (window.location.href !== "http://www.luxline.com.mx") {
                                    setTimeout(function () {
                                        window.location.href = "http://www.luxline.com.mx";
                                    }, 1000);
                                }


                                if(m_btnSignOut !== null)
                                    $(m_btnSignOut).hide();

                                if(m_btnSignIn !== null)
                                    $(m_btnSignIn).show();
                                break;
                            case -4:
                                console.log("Hay inconsistencia en la base de datos");
                                break;
                            default:
                                break;
                        }
                    });
                }
                else {
                    if(m_message !== null)
                        m_message.html("<h3>De click a Ingresar para comenzar</h3>")
                    else
                        console.log("De click a Ingresar para comenzar");

                    if(m_btnSignIn !== null)
                        $(m_btnSignIn).show();

                    if(m_btnSignOut !== null)
                        $(m_btnSignOut).hide();


                    $.post("../../Login/php/clearSession.php", {}, function () {
                    if (window.location.href !== "http://www.luxline.com.mx") {
                        setTimeout(function () {
                            window.location.href = "http://www.luxline.com.mx";
                        }, 1000);
                    }
                    });

                }
            }

           this.initAuth = function () {
                //make gapi calls
                gapi.client.setApiKey(m_apiKey);
                gapi.auth2.init({
                    client_id: m_clientId,
                    scope: m_scopes,
                    cookie_policy: m_cookie_policy
                }).then(function () {
                    //Obtener instancia de autorización

                    //****************auth2***************************************
                    //auth 2 is a GoogleAuth object that is a singleton class that provides methods to allow
                    //the user to sign in with a Google account, get the user's current
                    //sign-in status, get specific data from the user's Google profile,
                    //request additional scopes, and sign out from the current account.
                    m_auth2 = gapi.auth2.getAuthInstance();
                    //****************auth2***************************************

                    //Víncular un cambio de estado a una función

                    m_auth2.isSignedIn.listen(updateSigninStatus);

                    //Checar el estado inicial, podría ya estar loggeado
                    updateSigninStatus(m_auth2.isSignedIn.get());

                    //Listen to signin states
                    if(m_btnSignIn !== null)
                        m_btnSignIn.addEventListener("click", self.signIn);

                    if(m_btnSignOut !== null)
                        m_btnSignOut.addEventListener("click", self.logOut);
                });
            }

        };
    </script>
    <!--ApiLogIn-->

    <script>
        var go_ApiLogIn = null;
        var authorizeButton = null;
        var signoutButton = null;
        var g_message = null;
        var g_signOutBtn = null;
        var g_signInBtn = null;

        $(document).ready(function () {
            g_message = null;
            g_signInBtn = null;
            g_signOutBtn = document.getElementById('signout-button');

            go_ApiLogIn = new ApiLogIn(g_signInBtn, g_signOutBtn, g_message, "../../Login/php/logInFacturacion.php");
            gapi.load('client:auth2', go_ApiLogIn.initAuth);
        });
    </script>

