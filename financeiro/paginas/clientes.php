<div class="main">

<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=usuarios"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Usuários</a></li>
<li role="presentation"><a href="?s=despesas"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;Despesas</a></li>
<li role="presentation"><a href="?s=descontos"><span class="glyphicon glyphicon-barcode"></span>&nbsp;&nbsp;Pagamentos</a></li>
</ul>
</div>

<?php
	if(isset($_POST['btnInserirCliente']))
	{
		$cadastro->Cadastrarcliente();
	}
	elseif(isset($_POST['btnEditarCliente']))
	{
		$cadastro->EditarCliente();
	}
	elseif(isset($_POST['btnExcluirCliente']))
	{
		$cadastro->ApagarCliente();
	}
	
	if((isset($_GET['e'])) && (isset($_GET['id'])))
	{
		$dados = $cadastro->RetornaDadosCliente($_GET['id']);
		
		$nomeBotao = 'btnEditarCliente';
		$labelBotao = 'Editar';
		$edicao = 'S';
	}
	else
	{
		$dados['id'] = '';
		$dados['nome_cliente'] = '';
		$dados['descricao'] = '';;
		
		$nomeBotao = 'btnInserirCliente';
		$labelBotao = 'Inserir';
		$edicao = 'N';
		
	}
?>
	
	<h1 class="page-header">Gerenciar Clientes</h1>
	<h2 style="margin:10px 0 50px 0"><?php echo $labelBotao; ?></h2>

	<form class="form-horizontal" role="form" action="?s=clientes" method="post">
        
        <input type="hidden" name="txtIdCliente" value="<?php echo $dados['id']; ?>" />
        
        <div class="form-group">
          	<label for="txtNomeCliente" class="col-sm-2 control-label">Nome Cliente: </label>
           	<div class="col-sm-4 padInput">
				<input type="text" class="form-control" id="txtNomeCliente" name="txtNomeCliente" value="<?php echo $dados['nome_cliente'] ?>" />
          	</div>
        </div>
            
        <div class="form-group">
        	<label for="txtDescricaoDesconto" class="col-sm-2 control-label">Descrição: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDescricaoDesconto" name="txtDescricaoDesconto" value="<?php echo $dados['descricao'] ?>" />
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
			echo '<a href="?s=clientes"><button class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span>&nbsp; Inserir</button></a>';
		}
	
		$cadastro->Listaclientes();
	
	?>
			
	
  
</div>
