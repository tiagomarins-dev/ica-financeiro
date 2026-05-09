<div class="main">

<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="?s=despesas"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;Despesas</a></li>
<li role="presentation"><a href="?s=descontos"><span class="glyphicon glyphicon-barcode"></span>&nbsp;&nbsp;Pagamentos</a></li>
<li role="presentation"><a href="?s=clientes"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;&nbsp;Clientes</a></li>
</ul>
</div>


<?php
	if(isset($_POST['btnInserirUsuario']))
	{
		$cadastro->CadastrarUsuario();
	}
	elseif(isset($_POST['btnEditarUsuario']))
	{
		$cadastro->EditarUsuario();
	}
	elseif(isset($_POST['btnExcluirUsuario']))
	{
		$cadastro->ApagarUsuario();
	}


	
	if((isset($_GET['e'])) && (isset($_GET['id'])))
	{
		$cadastro->RetornaDadosUsuario($_GET['id']);
		
		$nomeBotao = 'btnEditarUsuario';
		$labelBotao = 'Editar';
		$edicao = 'S';
		
		$dados['id'] = $cadastro->dadosUsuario['id'];
		$dados['nome_usuario'] = $cadastro->dadosUsuario['nome_usuario'];
		$dados['sobrenome_usuario'] = $cadastro->dadosUsuario['sobrenome_usuario'];
		$dados['login_usuario'] = $cadastro->dadosUsuario['login_usuario'];
		$senha_usuario = '**********';
		
		$disabled = 'disabled';
	}
	else
	{
		$dados['id'] = '';
		$dados['nome_usuario'] = '';
		$dados['sobrenome_usuario'] = '';
		$dados['login_usuario'] = '';
		$senha_usuario = '';
		
		$disabled = '';
		
		$nomeBotao = 'btnInserirUsuario';
		$labelBotao = 'Inserir';
		$edicao = 'N';
		
	}
	
	
	echo $dados['nome_usuario'];
?>
	
	<h1 class="page-header">Gerenciar Usuários</h1>
	<h2 style="margin:10px 0 50px 0"><?php echo $labelBotao; ?></h2>

	<form class="form-horizontal" role="form" action="?s=usuarios" method="post">
        
        <input type="hidden" name="txtidUsuario" value="<?php echo $dados['id']; ?>" />
        
        <div class="form-group">
          	<label for="txtNomeUsuario" class="col-sm-2 control-label">Nome: </label>
           	<div class="col-sm-2 padInput">
				<input type="text" class="form-control" id="txtNomeUsuario" name="txtNomeUsuario" value="<?php echo $dados['nome_usuario'] ?>" />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtSobreNomeUsuario" class="col-sm-2 control-label">Sobrenome: </label>
           	<div class="col-sm-3 padInput">
				<input type="text" class="form-control" id="txtSobreNomeUsuario" name="txtSobreNomeUsuario" value="<?php echo $dados['sobrenome_usuario'] ?>" />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtLoginUsuario" class="col-sm-2 control-label">Login: </label>
           	<div class="col-sm-2 padInput">
				<input type="text" class="form-control" id="txtLoginUsuario" name="txtLoginUsuario" value="<?php echo $dados['login_usuario'] ?>" maxlength="16" />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtSenhaUsuario" class="col-sm-2 control-label">Senha: </label>
           	<div class="col-sm-2 padInput">
				<input type="password" class="form-control" id="txtSenhaUsuario" name="txtSenhaUsuario" value="<?php echo $senha_usuario; ?>" <?php echo $disabled; ?> maxlength="8" />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtSenhaUsuario2" class="col-sm-2 control-label">Confirmar Senha: </label>
           	<div class="col-sm-2 padInput">
				<input type="password" class="form-control" id="txtSenhaUsuario2" name="txtSenhaUsuario2" value="<?php echo $senha_usuario; ?>" <?php echo $disabled; ?> maxlength="8" />
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
			echo '<a href="?s=usuarios"><button class="btn btn-info btn-lg"><span class="glyphicon glyphicon-plus"></span>&nbsp; Inserir</button></a>';
		}
	
		$cadastro->ListaUsuarios();
	
	?>
			
	
  
</div>
