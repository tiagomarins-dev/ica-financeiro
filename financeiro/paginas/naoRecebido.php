<?php
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
<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=relatorios"><span class="glyphicon glyphicon-search"></span>&nbsp;Busca</a></li>
<li role="presentation"><a href="?s=estatisticas"><span class="glyphicon glyphicon-user"></span>&nbsp;Anestesistas</a></li>
<li role="presentation"><a href="?s=relatCirurgia"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Cirurgias</a></li>
<li role="presentation"><a href="?s=relatClientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Clientes</a></li>
<li role="presentation"><a href="?s=relatPagamento"><span class="glyphicon glyphicon-usd"></span>&nbsp;Formas de Pagamento</a></li>
<li role="presentation"><a href="?s=relatImpostos"><span class="glyphicon glyphicon-tag"></span>&nbsp;Impostos</a></li>
<li role="presentation"><a href="?s=naoCompensado">Não Compensados</a></li>


</ul>
</div>

<h1 class="page-header">Não Recebidos</h1>
<?php $relatorio->Pendentes('recebido',''); ?>  
</div>