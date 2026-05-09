<div class="main">

<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=usuarios"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Usuários</a></li>
<li role="presentation"><a href="?s=despesas"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;Despesas</a></li>
<li role="presentation"><a href="?s=clientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;&nbsp;Clientes</a></li>
</ul>
</div>

<?php
	if(isset($_POST['btnInserirDesconto']))
	{
		$cadastro->CadastrarFormaPagamento();
	}
	elseif(isset($_POST['btnEditarDesconto']))
	{
	    $cadastro->EditarFormaPagamento();
	}
	elseif(isset($_POST['btnExcluirFormaPagamento']))
	{
		$cadastro->ApagarFormaPagamento();
	}
	
	if((isset($_GET['e'])) && (isset($_GET['id'])))
	{
		$dados = $cadastro->RetornaDadosFormaPagamento($_GET['id']);
		
		$nomeBotao = 'btnEditarDesconto';
		$labelBotao = 'Editar';
		$edicao = 'S';
		
		$disabled = 'disabled';
	}
	else
	{
		$dados['id'] = '';
		$dados['tipo'] = '';
		$dados['dias'] = '';
		$dados['descricao'] = '';
		$dados['p_desconto'] = '';
		
		$nomeBotao = 'btnInserirDesconto';
		$labelBotao = 'Inserir';
		$edicao = 'N';
		$disabled = '';
		
	}
?>
	
	<h1 class="page-header">Gerenciar Formas de Pagamento</h1>
	<h2 style="margin:10px 0 50px 0"><?php echo $labelBotao; ?></h2>

	<form class="form-horizontal" role="form" action="?s=descontos" method="post">
        
        <input type="hidden" name="txtIdPagamento" value="<?php echo $dados['id']; ?>" />
        <input type="hidden" name="txtTipoDescontoEdit" value="<?php echo $dados['tipo']; ?>" />

        <div class="form-group">
          	<label for="txtTipoDesconto" class="col-sm-2 control-label">Forma de Pagamento: </label>
           	<div class="col-sm-4 padInput">
				<input type="text" class="form-control" id="txtTipoDesconto" name="txtTipoDesconto" value="<?php echo $dados['tipo'] ?>" <?php echo $disabled; ?>/>
          	</div>
        </div>
            
        <div class="form-group">
        	<label for="txtDescricaoDesconto" class="col-sm-2 control-label">Descrição: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDescricaoDesconto" name="txtDescricaoDesconto" value="<?php echo $dados['descricao'] ?>" />
        	</div>
        </div> 
                       
        <div class="form-group">
        	<label for="txtValorDesconto" class="col-sm-2 control-label">Valor do Desconto (%): </label>
        	<div class="col-sm-2 padInput">
        		<input type="text" class="form-control" id="txtValorDesconto" name="txtValorDesconto" value="<?php echo $dados['p_desconto'] ?>" />
        	</div>
        </div>

        <div class="form-group">
            <label for="txtDiasRecebimento" class="col-sm-2 control-label">Dias para recebimento: </label>
            <div class="col-sm-2 padInput">
                <input type="text" class="form-control" id="txtDiasRecebimento" name="txtDiasRecebimento" value="<?php echo $dados['dias'] ?>" />
            </div>
        </div>


        <div class="col-sm-10 padInput" style="text-align:right;">
			<input type="submit" class="btn btn-primary" value="<?php echo $labelBotao; ?>" name="<?php echo $nomeBotao; ?>" />
		</div>
    </form>
    
    
	
    <br /><br />
	<hr />
	<?php
		if($edicao == 'S')
		{
			echo '<a href="?s=descontos"><button class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span>&nbsp; Inserir</button></a>';
		}
	
		$cadastro->ListaFormaPagamento();
	
	?>
	<!--
	<div class="table-responsive" style="margin-top:20px; padding:50px;">
		<table class="table table-striped table-hovered" style="font-size:12px;">
			<thead>
				<tr>
					<th></th>
					<th>Despesa</th>
					<th>Descrição</th>
					<th style="text-align:center" width="10%">Dia de Vencimento</th>
					<th style="text-align:center" width="15%">Valor</th>
					<th style="text-align:center" width="5%"></th>
					<th style="text-align:center" width="5%"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td>Contador</td>
					<td>Pagamento mensal ao contador</td>
					<td align="center">20</td>
					<td align="center">R$ 2000,00</td>
					<td><button class="btn btn-success">Desativar</button></td>
					<td><button class="btn btn-danger">Excluir</button></td>
				</tr>
				
			</tbody>
		</table>
	</div>
	-->		
	
  
</div>
