<?php
	include __DIR__ . '/_configTabs.php';

	if(isset($_POST['btnInserirDesconto']))           $cadastro->CadastrarFormaPagamento();
	elseif(isset($_POST['btnEditarDesconto']))        $cadastro->EditarFormaPagamento();
	elseif(isset($_POST['btnExcluirFormaPagamento'])) $cadastro->ApagarFormaPagamento();

	if((isset($_GET['e'])) && (isset($_GET['id']))) {
		$dados = $cadastro->RetornaDadosFormaPagamento($_GET['id']);
		$nomeBotao = 'btnEditarDesconto';
		$labelBotao = 'Salvar alterações';
		$edicao = 'S';
		$disabled = 'disabled';
	} else {
		$dados = ['id'=>'', 'tipo'=>'', 'dias'=>'', 'descricao'=>'', 'p_desconto'=>''];
		$nomeBotao = 'btnInserirDesconto';
		$labelBotao = 'Inserir';
		$edicao = 'N';
		$disabled = '';
	}
?>

<div class="space-y-6">

	<header class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-slate-900">Formas de Pagamento</h1>
			<p class="mt-1 text-sm text-slate-500"><?php echo $edicao=='S' ? 'Editar forma de pagamento' : 'Cadastrar forma de pagamento'; ?></p>
		</div>
		<?php if ($edicao == 'S'): ?>
		<a href="?s=descontos" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
			Inserir novo
		</a>
		<?php endif; ?>
	</header>

	<?php renderConfigTabs('descontos'); ?>

	<form action="?s=descontos" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<input type="hidden" name="txtIdPagamento" value="<?php echo $dados['id']; ?>" />
		<input type="hidden" name="txtTipoDescontoEdit" value="<?php echo $dados['tipo']; ?>" />

		<div>
			<label for="txtTipoDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Forma de Pagamento</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 disabled:bg-slate-50 disabled:text-slate-600" id="txtTipoDesconto" name="txtTipoDesconto" value="<?php echo $dados['tipo'] ?>" <?php echo $disabled; ?> />
		</div>

		<div>
			<label for="txtDescricaoDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Descrição</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDescricaoDesconto" name="txtDescricaoDesconto" value="<?php echo $dados['descricao'] ?>" />
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtValorDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Desconto (%)</label>
				<div class="relative">
					<input type="text" class="block w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorDesconto" name="txtValorDesconto" value="<?php echo $dados['p_desconto'] ?>" />
					<span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-slate-500">%</span>
				</div>
			</div>
			<div>
				<label for="txtDiasRecebimento" class="block text-sm font-medium text-slate-700 mb-1.5">Dias para recebimento</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDiasRecebimento" name="txtDiasRecebimento" value="<?php echo $dados['dias'] ?>" />
			</div>
		</div>

		<div class="flex justify-end pt-2 border-t border-slate-100">
			<button type="submit" name="<?php echo $nomeBotao; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors"><?php echo $labelBotao; ?></button>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $cadastro->ListaFormaPagamento(); ?>
	</section>

</div>
