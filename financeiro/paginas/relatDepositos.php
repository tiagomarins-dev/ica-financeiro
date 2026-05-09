<?php
	
	//echo date('m/Y', strtotime('-5 months'));
	
	$dataAtual = date('d/m/Y');	
	$dataInicial01 = '01/' . date('m/Y', strtotime('-1 months'));	
	$dataInicio = (isset($_POST['txtDataInicio'])) ? $_POST['txtDataInicio'] : $dataInicial01;
	$dataFim = (isset($_POST['txtDataFim'])) ? $_POST['txtDataFim'] : $dataAtual ;	
?>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php $relatorio->RetornaSomaEquipeChart('10','2014'); ?>
	<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>
	
		
	

	<div class="main">

	<!-- 
	<div style="width:100%; text-align:right; border:0px solid #000;">
		<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
			<li role="presentation"><a href="?s=relatorios"><span class="glyphicon glyphicon-search"></span>&nbsp;Busca</a></li>
			<li role="presentation"><a href="?s=estatisticas"><span class="glyphicon glyphicon-user"></span>&nbsp;Anestesistas</a></li>
			<li role="presentation"><a href="?s=relatCirurgia"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Cirurgias</a></li>
			<li role="presentation"><a href="?s=relatClientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Clientes</a></li>
			<li role="presentation"><a href="?s=relatPagamento"><span class="glyphicon glyphicon-usd"></span>&nbsp;Formas de Pagamento</a></li>
			<li role="presentation"><a href="?s=naoCompensado">Não Compensados</a></li>
			<li role="presentation"><a href="?s=naoRecebido">Não Recebidos</a></li>
		</ul>
	</div>
-->

	<h1 class="page-header">Relatório de Depósitos</h1>

	<form class="form-inline" role="form" style="padding:20px;" action="?s=relatDepositos" method="post">
		
		
		<div class="form-group" style="margin:10px;">		
			<div class="form-group">
				<label for="txtDataInicio">Período:&nbsp;</label>
				<input type="<?php echo $typeData; ?>" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataInicio" name="txtDataInicio" placeholder="" style="width:160px;" value="<?php echo $dataInicio; ?>">
			</div>
			<div class="form-group">
				<label for="txtDataFim">&nbsp;&nbsp; à&nbsp;&nbsp; </label>
				<input type="<?php echo $typeData; ?>" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataFim" name="txtDataFim" placeholder="" style="width:160px;" value="<?php echo $dataFim; ?>">
			</div>		
		</div>
		
		
		<button type="submit" class="btn btn-primary" name="btnBuscar">Gerar Relatório</button>
		</form>
		<hr />

  
      
<?php 

	//echo $dataInicio . ' - ' . $dataFim;;	
	
	echo '<div style="margin:20px; float:left;">';
	echo '<h2 style="margin: 10px 0; float:left;">Resumo</h2>';
	$relatorio->DepositosRealizadosResumo($dataInicio,$dataFim); 
	echo '</div>';

?>
	
	
<?php
	
	echo '<div style="margin:20px; float:left;">';

	echo '<hr /><h2>Detalhes</h2>';
	$relatorio->DepositosRealizados($dataInicio,$dataFim); 
	echo '</div>';
?>
<!-- <div id="columnchart_stacked" style="width: 900px; height: 300px;"></div> -->
  
</div>