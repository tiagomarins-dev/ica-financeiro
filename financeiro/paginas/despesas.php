<div class="main">

<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=usuarios"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Usuários</a></li>
<li role="presentation"><a href="?s=descontos"><span class="glyphicon glyphicon-barcode"></span>&nbsp;&nbsp;Pagamentos</a></li>
<li role="presentation"><a href="?s=clientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;&nbsp;Clientes</a></li>
</ul>
</div>

<?php
	if(isset($_POST['btnInserirDespesa']))
	{
		$cadastro->CadastrarDespesaFixa();
	}
	elseif(isset($_POST['btnDelDespesa']))
	{
		$cadastro->ApagarDespesaFixa();
	}
	elseif(isset($_POST['btnEditarDespesa']))
	{
		$cadastro->EditarDespesaFixa();
	}
	
	if((isset($_GET['e'])) && (isset($_GET['id'])))
	{
		$dados = $cadastro->RetornaDadosDespesa($_GET['id']);
		
		$nomeBotao = 'btnEditarDespesa';
		$labelBotao = 'Editar';
		$edicao = 'S';
	}
	else
	{
		$dados['id'] = '';
		$dados['despesa'] = '';
		$dados['descricao'] = '';
		$dados['valor_despesa'] = '';
		$dados['dia_vencimento'] = '';
		
		$nomeBotao = 'btnInserirDespesa';
		$labelBotao = 'Inserir';
		$edicao = 'N';
		
	}
?>
	<h2 style="margin-bottom:50px;">Despesas Fixas</h2>

	<form class="form-horizontal" role="form" action="?s=despesas" method="post">
        
		<input type="hidden" name="chkDespesa" value="<?php echo $dados['id']; ?>" />
		
		<div class="form-group">
          	<label for="txtDiaVencimento" class="col-sm-2 control-label">Dia de Vencimento: </label>
           	<div class="col-sm-1 padInput">
				<input type="text" class="form-control" id="txtDiaVencimento" name="txtDiaVencimento" maxlength="2" value="<?php echo $dados['dia_vencimento']; ?>">
          	</div>
        </div>
            
        <div class="form-group">
        	<label for="txtDespesa" class="col-sm-2 control-label">Despesa: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDespesa" name="txtDespesa" placeholder="" value="<?php echo $dados['despesa']; ?>">
        	</div>
        </div> 
                       
        <div class="form-group">
        	<label for="txtDescricao" class="col-sm-2 control-label">Descrição: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDescricao" name="txtDescricao" placeholder="" value="<?php echo $dados['descricao']; ?>">
        	</div>
        </div> 
        
        <div class="form-group form-inline">
        	<label for="txtValorDespesa" class="col-sm-2 control-label">Valor: </label>
        	<div class="input-group">
        		<!--<input type="text" class="form-control" id="txtValorBruto" name="txtValorBruto" placeholder="Valor Bruto">-->
                <span class="input-group-addon">R$</span>
 				<input type="text" class="form-control money" id="txtValorDespesa" name="txtValorDespesa" placeholder="000,00" value="<?php echo $dados['valor_despesa']; ?>">
        	</div>           
        </div> 
        
		<div class="col-sm-10 padInput" style="text-align:right;">
			<input type="submit" class="btn btn-primary" value="<?php echo $labelBotao; ?>" name="<?php echo $nomeBotao; ?>" />
		</div>
    </form>
	
	<br /><br /><br /><br />
	
	<?php
		if($edicao == 'S')
		{
			echo '<a href="?s=despesas"><button class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span>&nbsp; Inserir</button></a>';
		}
		$cadastro->ListaDespesasFixas();
	?>
  
</div>