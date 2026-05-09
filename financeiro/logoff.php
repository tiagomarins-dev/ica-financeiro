<?php
	#Abre Sessão
	session_save_path("/tmp");
	session_name('login');
	session_start();

#   Função autoload
    require_once __DIR__ . '/classes/autoload.php';
    $cadastro = new cadastro();
	$relatorio = new relatorios();	
	$login = new login();
	
	$login->DeslogarSistema();
	
?>
