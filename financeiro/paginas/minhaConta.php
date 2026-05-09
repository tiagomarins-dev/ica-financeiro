<?php
	
	$idUser = (isset($_SESSION['idUser'])) ? $_SESSION['idUser'] : '';
	
	//Buscar dados no banco do usuário pelo ID $_SESSION['idUser']
	$dados = $login->BuscarDadosUsuario($idUser);	
	
	$senha_usuario = '';
	$disabled = '';
	
	if(isset($_POST['btnNovaSenha']))
	{
		
	}
?>



<div class="main">
	
	<h1 class="page-header">Minha Conta</h1>

	<form class="form-horizontal" role="form" action="?s=minhaConta" method="post">
        
        <input type="hidden" name="txtidUsuario" value="<?php echo $dados['id']; ?>" />
        
        <div class="form-group">
          	<label for="txtNomeUsuario" class="col-sm-2 control-label">Nome: </label>
           	<div class="col-sm-2 padInput">
				<input type="text" class="form-control" id="txtNomeUsuario" name="txtNomeUsuario" value="<?php echo $dados['nome_usuario'] ?>" disabled />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtSobreNomeUsuario" class="col-sm-2 control-label">Sobrenome: </label>
           	<div class="col-sm-3 padInput">
				<input type="text" class="form-control" id="txtSobreNomeUsuario" name="txtSobreNomeUsuario" value="<?php echo $dados['sobrenome_usuario'] ?>" disabled />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtLoginUsuario" class="col-sm-2 control-label">Login: </label>
           	<div class="col-sm-2 padInput">
				<input type="text" class="form-control" id="txtLoginUsuario" name="txtLoginUsuario" value="<?php echo $dados['login_usuario'] ?>" maxlength="16" disabled />
          	</div>
        </div>
		
		<div class="form-group">
          	<label for="txtSenhaUsuario" class="col-sm-2 control-label">Nova Senha: </label>
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
			<input type="submit" class="btn btn-primary" value="Enviar" name="btnNovaSenha" />
		</div>
    </form>
    
    
	
</div>
