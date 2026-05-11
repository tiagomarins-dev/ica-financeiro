<?php
	include __DIR__ . '/_configTabs.php';

	if(isset($_POST['btnInserirCliente']))      $cadastro->Cadastrarcliente();
	elseif(isset($_POST['btnEditarCliente']))   $cadastro->EditarCliente();
	elseif(isset($_POST['btnExcluirCliente']))  $cadastro->ApagarCliente();

	if((isset($_GET['e'])) && (isset($_GET['id']))) {
		$dados = $cadastro->RetornaDadosCliente($_GET['id']);
		$nomeBotao = 'btnEditarCliente';
		$labelBotao = 'Salvar alterações';
		$edicao = 'S';
	} else {
		$dados = ['id'=>'', 'nome_cliente'=>'', 'descricao'=>''];
		$nomeBotao = 'btnInserirCliente';
		$labelBotao = 'Inserir';
		$edicao = 'N';
	}
?>

<div class="space-y-6">

	<header class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-slate-900">Gerenciar Clientes</h1>
			<p class="mt-1 text-sm text-slate-500"><?php echo $edicao=='S' ? 'Editar cliente' : 'Inserir novo cliente'; ?></p>
		</div>
		<?php if ($edicao == 'S'): ?>
		<a href="?s=clientes" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
			Inserir novo
		</a>
		<?php endif; ?>
	</header>

	<?php renderConfigTabs('clientes'); ?>

	<form action="?s=clientes" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<input type="hidden" name="txtIdCliente" value="<?php echo $dados['id']; ?>" />

		<div>
			<label for="txtNomeCliente" class="block text-sm font-medium text-slate-700 mb-1.5">Nome do Cliente</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtNomeCliente" name="txtNomeCliente" value="<?php echo $dados['nome_cliente']; ?>" />
		</div>

		<div>
			<label for="txtDescricaoDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Descrição</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDescricaoDesconto" name="txtDescricaoDesconto" value="<?php echo $dados['descricao']; ?>" />
		</div>

		<div class="flex justify-end pt-2 border-t border-slate-100">
			<button type="submit" name="<?php echo $nomeBotao; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors"><?php echo $labelBotao; ?></button>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $cadastro->Listaclientes(); ?>
	</section>

</div>
