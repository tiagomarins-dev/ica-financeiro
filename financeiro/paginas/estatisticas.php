<?php
	$mes = (isset($_GET['m'])) ? $_GET['m'] : date('m');
	$ano = (isset($_GET['a'])) ? $_GET['a'] : date('Y');	

	if(($mes >= 1) && ($mes <= 12))
	{
		$mes = $mes;
	}
	else
	{
		$mes = date('m');
	}

	if(($ano > 1980) && ($mes < 2114))
	{
		$ano = $ano;
	}
	else
	{
		$ano = date('Y');
	}

?>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php $relatorio->RetornaSomaEquipeChart($mes,$ano); ?>
	<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>	

<div class="main">
<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=relatorios"><span class="glyphicon glyphicon-search"></span>&nbsp;Busca</a></li>

<li role="presentation"><a href="?s=relatCirurgia"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Cirurgias</a></li>
<li role="presentation"><a href="?s=relatClientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Clientes</a></li>
<li role="presentation"><a href="?s=relatPagamento"><span class="glyphicon glyphicon-usd"></span>&nbsp;Formas de Pagamento</a></li>
<li role="presentation"><a href="?s=relatImpostos"><span class="glyphicon glyphicon-tag"></span>&nbsp;Impostos</a></li>
<li role="presentation"><a href="?s=naoCompensado">Não Compensados</a></li>
<li role="presentation"><a href="?s=naoRecebido">Não Recebidos</a></li>
</ul>
</div>
<h1 class="page-header">Anestesistas</h1>
   
<!-- <h2>Em desenvolvimento... </h2> -->

<div class="dropdown" style="margin-bottom:20px;">
        	
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
        		<?php echo $cadastro->RenomeiaMeses($mes,$ano); ?>
        		<span class="caret"></span>
       		</button>
        	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<?php $cadastro->ListaMesesStats($mes,$ano); ?>
				<!--
        		<li role="presentation" class="divider"></li>
        		<li role="presentation"><a role="menuitem" tabindex="-1" href="#">2013</a></li>
        		<li role="presentation"><a role="menuitem" tabindex="-1" href="#">2012</a></li>
				-->
        	</ul>
</div>

<?php $relatorio->RelatorioPorAnestesista($mes,$ano); ?>
<div id="chart_div" style="width: 900px; height: 500px; margin:20px;"></div>
  
</div>