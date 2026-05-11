<?php
	$idUser = (isset($_SESSION['idUser'])) ? $_SESSION['idUser'] : '';
	$dados = $login->BuscarDadosUsuario($idUser);
	$senha_usuario = '';
	$disabled = '';
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Minha Conta</h1>
		<p class="mt-1 text-sm text-slate-500">Atualize sua senha</p>
	</header>

	<form action="?s=minhaConta" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4 max-w-2xl">

		<input type="hidden" name="txtidUsuario" value="<?php echo $dados['id']; ?>" />

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtNomeUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Nome</label>
				<input type="text" class="block w-full px-3 py-2 text-sm bg-slate-50 text-slate-600 border border-slate-300 rounded-lg" id="txtNomeUsuario" name="txtNomeUsuario" value="<?php echo $dados['nome_usuario'] ?>" disabled />
			</div>
			<div>
				<label for="txtSobreNomeUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Sobrenome</label>
				<input type="text" class="block w-full px-3 py-2 text-sm bg-slate-50 text-slate-600 border border-slate-300 rounded-lg" id="txtSobreNomeUsuario" name="txtSobreNomeUsuario" value="<?php echo $dados['sobrenome_usuario'] ?>" disabled />
			</div>
		</div>

		<div>
			<label for="txtLoginUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Login</label>
			<input type="text" class="block w-full px-3 py-2 text-sm bg-slate-50 text-slate-600 border border-slate-300 rounded-lg" id="txtLoginUsuario" name="txtLoginUsuario" value="<?php echo $dados['login_usuario'] ?>" maxlength="16" disabled />
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtSenhaUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Nova Senha</label>
				<input type="password" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSenhaUsuario" name="txtSenhaUsuario" maxlength="8" />
			</div>
			<div>
				<label for="txtSenhaUsuario2" class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar Senha</label>
				<input type="password" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSenhaUsuario2" name="txtSenhaUsuario2" maxlength="8" />
			</div>
		</div>

		<div class="flex justify-end pt-2 border-t border-slate-100">
			<button type="submit" name="btnNovaSenha" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
				Atualizar senha
			</button>
		</div>
	</form>

</div>
