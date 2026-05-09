<?php 

	//print_r($_GET);
	
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
	
	//echo $mes . ' - ' . $ano;

?>
<div class="main">

		
		<?php if($tipoSistema == 'Mobile') { ?>
		
			<div class="alert alert-warning fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<a href="?s=depositoPendente" style="color:#8A6D3B;"><h3>Total de depósitos pendentes: <strong><?php echo $relatorio->RetornaSomaDepositoPendente(''); ?></strong></h3></a>
			</div>
		
		<?php } else { ?>
		
			<div class="alert alert-warning fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<a href="?s=depositoPendente" style="color:#8A6D3B;">Total de depósitos pendentes: <strong><?php echo $relatorio->RetornaSomaDepositoPendente(''); ?></strong></a>
			</div>
		
		<?php }  ?>
	
	  
		  <h1 class="page-header">Resumo</h1>
          <div class="table-responsive">
            <?php $relatorio->RetornaResumo(); ?>
            <ul class="pager">  
  				<li class="next"><a href="index.php?s=historico">Ver mais &rarr;</a></li>
			</ul>

				
          </div>
          
		
<?php
		if(isset($_POST['btnInserirAnestesia']))
		{
			$cadastro->Cadastrar();
		}
		elseif(isset($_POST['btnInserirDespesa']))
		{
			$cadastro->CadastrarDespesa();
		}
		elseif(isset($_POST['btnImportaDespesa']))
		{
			$cadastro->ImportacaoDespesas();
		}
		elseif(isset($_POST['btnDelServico']))
		{
			$cadastro->ApagarServico();
		}
		elseif(isset($_POST['btnDelDespesa']))
		{
			$cadastro->ApagarDespesaMensal();	
		}
		
		//echo $cadastro->converteValor('1000.00');
?>       
        <div class="dropdown" style="margin-bottom:20px;">
        	
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
        		<?php echo $cadastro->RenomeiaMeses($mes,$ano); ?>
        		<span class="caret"></span>
       		</button>
        	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<?php $cadastro->ListaMeses($mes,$ano); ?>
				<!--
        		<li role="presentation" class="divider"></li>
        		<li role="presentation"><a role="menuitem" tabindex="-1" href="#">2013</a></li>
        		<li role="presentation"><a role="menuitem" tabindex="-1" href="#">2012</a></li>
				-->
        	</ul>
             <a name="inicio" />
            <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="glyphicon glyphicon-pencil"></span>&nbsp; Inserir Serviço</button>-->
            <a href="?s=inserirServico"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span>&nbsp; Inserir Serviço</button></a>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target=".bs-example-modal-lg2"><span class="glyphicon glyphicon-pencil"></span>&nbsp; Inserir Despesas do Mês</button>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg3"><span class="glyphicon glyphicon-import"></span>&nbsp; Importar Despesas Fixas</button>
			<a href="#final"><button type="button" class="btn btn-default" style="float:right;"><span class="glyphicon glyphicon-arrow-down"></span>&nbsp; Ir para o final</button></a>
           
        </div>
        
        <div class="panel-group" id="accordion">
 <!--
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
          Collapsible Group Item #1
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div> -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="col-md-11" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Despesas<span style="color:#000; font-weight:bold; font-size:12px; float:right;">Total de despesas: R$ <?php echo $cadastro->SomaDespesaMes($mes,$ano); ?></span></a>  <span class="">&nbsp;</span>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
		
		<!-- Lista Despesas -->
		<div class="panel-body nPadding" style="padding:0;">
			<?php $cadastro->ListaDespesas($mes,$ano); ?>  
		</div>
    </div>
  </div>
  
  
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="col-md-10" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
					Anestesias
				</a><span class="">&nbsp;</span>
			</h4>
		</div>
    
		<div id="collapseThree" class="panel-collapse collapse in">
			<div class="panel-body nPadding" style="padding:0;">
				<?php $cadastro->ListaServico($mes,$ano); ?>  
        
			</div>
		</div>
    </div>
  </div>
  
  <a href="#inicio"><button type="button" class="btn btn-default" style="float:right; margin-top:20px;"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp; Voltar para o começo</button></a>
  
</div>
