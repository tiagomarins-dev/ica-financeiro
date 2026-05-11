<?php
	include __DIR__ . '/_configTabs.php';

	if(isset($_POST['btnInserirDespesa']))   $cadastro->CadastrarDespesaFixa();
	elseif(isset($_POST['btnDelDespesa']))   $cadastro->ApagarDespesaFixa();
	elseif(isset($_POST['btnEditarDespesa'])) $cadastro->EditarDespesaFixa();

	if((isset($_GET['e'])) && (isset($_GET['id']))) {
		$dados = $cadastro->RetornaDadosDespesa($_GET['id']);
		$nomeBotao = 'btnEditarDespesa';
		$labelBotao = 'Salvar alterações';
		$edicao = 'S';
	} else {
		$dados = ['id'=>'', 'despesa'=>'', 'descricao'=>'', 'valor_despesa'=>'', 'dia_vencimento'=>''];
		$nomeBotao = 'btnInserirDespesa';
		$labelBotao = 'Inserir';
		$edicao = 'N';
	}
?>

<div class="space-y-6">

	<header class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-slate-900">Despesas Fixas</h1>
			<p class="mt-1 text-sm text-slate-500"><?php echo $edicao=='S' ? 'Editar despesa fixa' : 'Cadastrar despesa fixa'; ?></p>
		</div>
		<?php if ($edicao == 'S'): ?>
		<a href="?s=despesas" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
			Inserir nova
		</a>
		<?php endif; ?>
	</header>

	<?php renderConfigTabs('despesas'); ?>

	<form action="?s=despesas" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<input type="hidden" name="chkDespesa" value="<?php echo $dados['id']; ?>" />

		<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
			<div>
				<label for="txtDiaVencimento" class="block text-sm font-medium text-slate-700 mb-1.5">Dia de Vencimento</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDiaVencimento" name="txtDiaVencimento" maxlength="2" value="<?php echo $dados['dia_vencimento']; ?>" />
			</div>
			<div class="sm:col-span-2">
				<label for="txtValorDespesa" class="block text-sm font-medium text-slate-700 mb-1.5">Valor</label>
				<div class="relative">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
					<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorDespesa" name="txtValorDespesa" placeholder="0,00" value="<?php echo $dados['valor_despesa']; ?>" />
				</div>
			</div>
		</div>

		<div>
			<label for="txtDespesa" class="block text-sm font-medium text-slate-700 mb-1.5">Despesa</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDespesa" name="txtDespesa" value="<?php echo $dados['despesa']; ?>" />
		</div>

		<div>
			<label for="txtDescricao" class="block text-sm font-medium text-slate-700 mb-1.5">Descrição</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDescricao" name="txtDescricao" value="<?php echo $dados['descricao']; ?>" />
		</div>

		<div class="flex justify-end pt-2 border-t border-slate-100">
			<button type="submit" name="<?php echo $nomeBotao; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors"><?php echo $labelBotao; ?></button>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $cadastro->ListaDespesasFixas(); ?>
	</section>

</div>
