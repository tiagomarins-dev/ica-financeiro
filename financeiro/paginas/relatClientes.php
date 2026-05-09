<?php
	$dataAtual = date('d/m/Y');	
	$dataInicial01 = '01/' . date('m/Y');	
	$dataInicio = (isset($_POST['txtDataInicio'])) ? $_POST['txtDataInicio'] : $dataInicial01;
	$dataFim = (isset($_POST['txtDataFim'])) ? $_POST['txtDataFim'] : $dataAtual ;
	$cliente = (isset($_POST['txtCliente'])) ? $_POST['txtCliente'] : '';
	$paciente = (isset($_POST['txtPaciente'])) ? $_POST['txtPaciente'] : '';
?>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php $relatorio->RetornaSomaEquipeChart('10','2014'); ?>
	<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>
	
	
	
	

<div class="main">

<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=relatorios"><span class="glyphicon glyphicon-search"></span>&nbsp;Busca</a></li>
<li role="presentation"><a href="?s=estatisticas"><span class="glyphicon glyphicon-user"></span>&nbsp;Anestesistas</a></li>
<li role="presentation"><a href="?s=relatCirurgia"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Cirurgias</a></li>

<li role="presentation"><a href="?s=relatPagamento"><span class="glyphicon glyphicon-usd"></span>&nbsp;Formas de Pagamento</a></li>
<li role="presentation"><a href="?s=relatImpostos"><span class="glyphicon glyphicon-tag"></span>&nbsp;Impostos</a></li>
<li role="presentation"><a href="?s=naoCompensado">Não Compensados</a></li>
<li role="presentation"><a href="?s=naoRecebido">Não Recebidos</a></li>
</ul>
</div>
<h1 class="page-header">Relatório de Clientes</h1>
<form class="form-inline" role="form" style="padding:20px;" action="?s=relatClientes" method="post">
		
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
		
		<!--
		<div class="form-group" style="margin:10px;">		
		<div class="form-group">
		<label for="txtCliente">Cliente:&nbsp;</label>
		<input type="text" class="form-control" id="txtCliente" name="txtCliente" placeholder="" style="width:200px;" value="<?php echo $cliente; ?>">
		</div>		
		</div>	
		-->
		
		<div class="form-group" style="margin-right:10px;">
        	<label for="txtCliente">Cliente:&nbsp;</label>
            	<select class="form-control" id="txtCliente" name="txtCliente">
            		<option value="">TODOS</option>					
					<?php $cadastro->ListaClientesForm(); ?>            		
            	</select>
            	
			</div>
		
		<button type="submit" class="btn btn-primary" name="btnBuscar">Gerar Relatório</button>
		</form>

  
  
<!--Div that will hold the pie chart
    <div id="chart_div" style="width: 70%; height: 500px;"></div>
	<div id="chart_cliente" style="width: 70%; height: 500px;"></div>
-->

    

<?php	$relatorio->RetornaSomaClientesPeriodo($dataInicio,$dataFim,$cliente); ?>


  
</div>