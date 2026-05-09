<?php

	if(isset($_GET['u'])) {
	
		$usuarioPendente = $_GET['u'];
	
	}
	else {
		$usuarioPendente = '';
	}


	$dataInicio = (isset($_POST['txtDataInicio'])) ? $_POST['txtDataInicio'] : '';
	$dataFim = (isset($_POST['txtDataFim'])) ? $_POST['txtDataFim'] : '';
	$cliente = (isset($_POST['txtCliente'])) ? $_POST['txtCliente'] : '';
	$paciente = (isset($_POST['txtPaciente'])) ? $_POST['txtPaciente'] : '';
?>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php $relatorio->RetornaSomaEquipeChart('10','2014'); ?>
	<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>
	
	
	
	

<div class="main">
<h1 class="page-header">Depósitos Pendentes <span style="font-size:22px;"> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
Total: <?php echo $relatorio->RetornaSomaDepositoPendente(''); ?></span></h1>



<h3>Por Anestesistas</h3>

<?php 

	$relatorio->TotalPendentePorUsuario(); 

?>  

<br />

<h3>Total</h3>

<?php 
	//$relatorio->Pendentes('deposito',$usuarioPendente); 
?>  

</div>