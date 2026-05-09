<?php
//    die('teste');
	#Abre Sessão
    session_save_path("/tmp");
	session_name('login');
	session_start();

#   Função autoload
    require_once __DIR__ . '/classes/autoload.php';
    $cadastro = new cadastro();
	$relatorio = new relatorios();	
	$login = new login();
?>
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if($login->LogarSistema() === true)
    {
        #Logado com sucesso - Redirecionar para a página index.php
        header("Location: index.php");
    }
    else
    {
        echo '<div class="alert alert-danger">Usuário ou senha incorretos. Tente novamente</div>';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICA ..:::.. Login</title>
<link rel="shortcut icon" type="image/x-icon" href="Imagens/favicon.ico">
<link rel="icon" href="Imagens/favicon.ico" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="estilos/css/bootstrap.min.css">
<link rel="stylesheet" href="estilos/css/dashboard.css">
<!-- Optional theme -->
<link rel="stylesheet" href="estilos/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="estilos/js/bootstrap.min.js"></script>
<!-- Estilo Principal do Site -->
<link rel="stylesheet" href="estilos/estilo.css">

<link rel="stylesheet" href="jquery/development-bundle/themes/base/jquery.ui.all.css">
<script src="autocomplete/jquery-1.6.2.js"></script>
<script src="autocomplete/jquery.ui.core.js"></script>
<script src="autocomplete/jquery.ui.widget.js"></script>
<script src="autocomplete/jquery.ui.position.js"></script>
<script src="autocomplete/jquery.ui.autocomplete.js"></script> 
<script type="text/javascript" src="script/autocomplete_ica.js"></script>

<link rel="stylesheet" href="estilos/js/validacao.js">

<!-- Estilo Principal do Site -->
<link rel="stylesheet" href="estilos/estilo.css">

<link href="estilos/css/signin.css" rel="stylesheet">
</head>

<body>


	
	<div class="container" style="width:350px; margin-top:50px;">

      <form class="form-signin" role="form" action="login.php" method="post">
        <h2 class="form-signin-heading">Controle Financeiro</h2>
        <label for="login" class="sr-only">Login</label>
        <input type="text" id="login" name="login" class="form-control" placeholder="Login" required autofocus>
		
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      </form>

    </div> <!-- /container -->
    
    
    <script src="estilos/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="estilos/js/ie10-viewport-bug-workaround.js"></script>
    
    
  </body>
</body>
</html>
