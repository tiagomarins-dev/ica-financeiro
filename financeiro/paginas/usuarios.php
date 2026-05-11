<?php
	include __DIR__ . '/_configTabs.php';

	if(isset($_POST['btnInserirUsuario']))      $cadastro->CadastrarUsuario();
	elseif(isset($_POST['btnEditarUsuario']))   $cadastro->EditarUsuario();
	elseif(isset($_POST['btnExcluirUsuario']))  $cadastro->ApagarUsuario();

	if((isset($_GET['e'])) && (isset($_GET['id']))) {
		$cadastro->RetornaDadosUsuario($_GET['id']);
		$nomeBotao = 'btnEditarUsuario';
		$labelBotao = 'Salvar alterações';
		$edicao = 'S';
		$dados['id'] = $cadastro->dadosUsuario['id'];
		$dados['nome_usuario'] = $cadastro->dadosUsuario['nome_usuario'];
		$dados['sobrenome_usuario'] = $cadastro->dadosUsuario['sobrenome_usuario'];
		$dados['login_usuario'] = $cadastro->dadosUsuario['login_usuario'];
		$senha_usuario = '**********';
		$disabled = 'disabled';
	} else {
		$dados = ['id'=>'', 'nome_usuario'=>'', 'sobrenome_usuario'=>'', 'login_usuario'=>''];
		$senha_usuario = '';
		$disabled = '';
		$nomeBotao = 'btnInserirUsuario';
		$labelBotao = 'Inserir';
		$edicao = 'N';
	}
?>

<div class="space-y-6">

	<header class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-slate-900">Gerenciar Usuários</h1>
			<p class="mt-1 text-sm text-slate-500"><?php echo $edicao=='S' ? 'Editar usuário' : 'Inserir novo usuário'; ?></p>
		</div>
		<?php if ($edicao == 'S'): ?>
		<a href="?s=usuarios" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
			Inserir novo
		</a>
		<?php endif; ?>
	</header>

	<?php renderConfigTabs('usuarios'); ?>

	<form action="?s=usuarios" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<input type="hidden" name="txtidUsuario" value="<?php echo $dados['id']; ?>" />

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtNomeUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Nome</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtNomeUsuario" name="txtNomeUsuario" value="<?php echo $dados['nome_usuario']; ?>" />
			</div>
			<div>
				<label for="txtSobreNomeUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Sobrenome</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSobreNomeUsuario" name="txtSobreNomeUsuario" value="<?php echo $dados['sobrenome_usuario']; ?>" />
			</div>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
			<div>
				<label for="txtLoginUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Login</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtLoginUsuario" name="txtLoginUsuario" value="<?php echo $dados['login_usuario']; ?>" maxlength="16" />
			</div>
			<div>
				<label for="txtSenhaUsuario" class="block text-sm font-medium text-slate-700 mb-1.5">Senha</label>
				<input type="password" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSenhaUsuario" name="txtSenhaUsuario" value="<?php echo $senha_usuario; ?>" <?php echo $disabled; ?> maxlength="8" />
			</div>
			<div>
				<label for="txtSenhaUsuario2" class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar Senha</label>
				<input type="password" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSenhaUsuario2" name="txtSenhaUsuario2" value="<?php echo $senha_usuario; ?>" <?php echo $disabled; ?> maxlength="8" />
			</div>
		</div>

		<div class="flex justify-end pt-2 border-t border-slate-100">
			<button type="submit" name="<?php echo $nomeBotao; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors"><?php echo $labelBotao; ?></button>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $cadastro->ListaUsuarios(); ?>
	</section>

</div>
