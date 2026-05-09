<?php

	
	if(isset($_POST['btnSalvarCartao'])) {
			
		$cadastro->EditarDepositoCartao();
		
	}

	if(isset($_GET['i'])) {

	    $data_inicial = $_GET['i'];
    }
    else {

	    if(isset($_POST['data_inicio'])) {

            $data_inicial = $_POST['data_inicio'];
            $data_inicial_br = $_POST['data_inicio'];
        }
        else {

            $data_inicial = "01/" . date('m/Y');
            $data_inicial_br = "01/" . date('m/Y');
        }


    }

    if(isset($_GET['f'])) {

        $data_final = $_GET['f'];
    }
    else {


	    if(isset($_POST['data_fim'])) {

	        $data_final = $_POST['data_fim'];
	        $data_final_br = $_POST['data_fim'];

        }
        else {

            $mes = date('m');
            $ano = date("Y");
            $dia = date("t", mktime(0,0,0,$mes,'01',$ano));
            $data_final = "$dia/$mes/$ano";
            $data_final_br = "$dia/$mes/$ano";
        }


    }

    if(isset($_POST['filtro'])) {

	    $id_cartao = $_POST['filtro'];
    }
    else {

	    $id_cartao = 0;
    }

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
	
	$valorCartao = $cadastro->RetornaResumoCartao($mes, $ano);
	$totalReceber = $cadastro->RetornaTotalReceber($data_inicial, $data_final, $id_cartao);
	$totalRecebido = $cadastro->RetornaTotalRecebido($data_inicial, $data_final, $id_cartao);

	$lista_cartoes = $cadastro->listaCartoesSelect();

?>
<div class="main">

<h1 class="page-header">Conferência</h1>

<!-- <h2>Em desenvolvimento... </h2> -->

<!--<div class="dropdown">
        	
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
        		<?php /*echo $cadastro->RenomeiaMeses($mes,$ano); */?>
        		<span class="caret"></span>
       		</button>
			
        	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<?php /*$cadastro->ListaMesesCartoes($mes,$ano); */?>
				
        	</ul>

    <hr class="col-xs-12 col-md-12">



</div>-->

    <div class="col-xs-12 col-md-6 nPadding">

        <form action="?s=checkCartao" method="post">

            <div class="col-xs-6 col-md-6">
                <label for="data_inicio"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>&nbsp;Data Inicial</label>
                <input type="text" name="data_inicio" id="data_inicio" class="form-control" value="<?php echo $data_inicial_br; ?>" />
            </div>

            <div class="col-xs-6 col-md-6">
                <label for="data_fim"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>&nbsp;Data Final</label>
                <input type="text" name="data_fim" id="data_fim" class="form-control" value="<?php echo $data_final_br; ?>" />
            </div>


            <div class="col-xs-12 col-md-10" style="margin-top: 10px;">
                <label for="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp;Filtro</label>
                <select name="filtro" id="filtro" class="form-control">
                    <option value="<?php echo $id_cartao ?>"><?php echo $cadastro->getNomeCartao($id_cartao); ?></option>
                    <?php foreach ($lista_cartoes as $cartao) { ?>
                        <option value="<?php echo $cartao['id']; ?>"><?php echo $cartao['tipo']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-xs-12 col-md-2" style="margin-top: 10px">
                <label for="btnFiltrar">&nbsp</label>
                <input type="submit" name="btnFiltrar" id="btnFiltrar" class="form-control btn btn-primary" value="Filtrar" />
            </div>
        </form>

    </div>

<div class="col-xs-12 col-md-6 nPadding" style="margin-top: 20px;">
    <ul class="list-group pull-right col-xs-12 col-md-4">
        <li class="list-group-item">
            <span class="badge"><?php echo $totalReceber; ?></span>
            Total a receber:
        </li>
        <li class="list-group-item">
            <span class="badge"><?php echo $totalRecebido; ?></span>
            Total recebido:
        </li>
    </ul>
</div>

    <hr class="col-xs-12 col-md-12">

   

<link href="estilos/switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
<!-- <link href="estilos/switch/docs/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="estilos/switch/docs/css/highlight.css" rel="stylesheet">
<!-- <link href="http://getbootstrap.com/assets/css/docs.min.css" rel="stylesheet"> -->
<link href="estilos/switch/docs/css/main.css" rel="stylesheet">

<?php 
	$cadastro->ListaServicoCartao($data_inicial, $data_final, $id_cartao);
?>


  
</div>

<script src="estilos/js/jquery-1.12.1.min.js"></script>
<script src="estilos/datepicker/js/bootstrap-datepicker.js"></script>
<script>
    $('#data_inicio').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });
    $('#data_fim').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR'
    });
</script>

<script src="estilos/switch/docs/js/jquery.min.js"></script>
<script src="estilos/switch/docs/js/bootstrap.min.js"></script>
<script src="estilos/switch/docs/js/highlight.js"></script>
<script src="estilos/switch/dist/js/bootstrap-switch.js"></script>
<script src="estilos/switch/docs/js/main.js"></script>



