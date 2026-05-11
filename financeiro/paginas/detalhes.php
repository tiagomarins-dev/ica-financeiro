<?php

	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

	$cadastro->DetalhesServico($id,$typeData);
	$cadastro->RetornaDadosCheque($id);

	$nameButtonSolucao = ($cadastro->dadosCheque['count'] > 0) ? 'btnSolucaoCompensadoEdit' : 'btnSolucaoCompensadoInsert';

	if(isset($_POST['btnDepositado']))                $cadastro->MarcarDepositado($id);
	elseif(isset($_POST['btnRecebido']))              $cadastro->MarcarRecebido($id);
	elseif(isset($_POST['btnNCompensado']))           $cadastro->MarcarNaoCompensado($id);
	elseif(isset($_POST['btnSolucaoCompensadoInsert'])) $cadastro->CadastrarSolucao($id);
	elseif(isset($_POST['btnSolucaoCompensadoEdit']))   $cadastro->EditarSolucao($id);

	// Helper para celulas de detalhe
	function detalheLinha($label, $valor, $extra = '') {
		echo '<div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-3 border-b border-slate-100 last:border-0">'
			.'<div class="text-sm font-medium text-slate-600">'.$label.'</div>'
			.'<div class="sm:col-span-2 text-sm text-slate-900 '.$extra.'">'.$valor.'</div>'
			.'</div>';
	}
?>

<div class="space-y-6">

	<!-- Top bar: voltar / editar -->
	<div class="flex items-center justify-between">
		<a href="javascript:window.history.go(-1)" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
			Voltar
		</a>
		<a href="?s=editarServico&id=<?php echo $id; ?>" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.549 2.799a2.121 2.121 0 1 1 3 3L5.232 22.117a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
			Editar
		</a>
	</div>

	<!-- Page header -->
	<header>
		<h1 class="text-xl font-semibold text-slate-900">Detalhes do Serviço</h1>
		<p class="mt-1 text-sm text-slate-500">
			<span class="font-medium text-rose-600"><?php echo $cadastro->dadosServico['dataServico']; ?></span>
			· <?php echo $cadastro->dadosServico['nome_paciente']; ?>
		</p>
	</header>

	<!-- Card de detalhes -->
	<section class="bg-white rounded-xl ring-1 ring-slate-200 px-6 py-2">
		<?php
			detalheLinha('Cliente', $cadastro->dadosServico['nome_cliente']);
			detalheLinha('Atendimento', $cadastro->dadosServico['nome_usuario']);
			detalheLinha('Cirurgia', $cadastro->dadosServico['cirurgia']);
			detalheLinha('Valor Bruto', $cadastro->converteValorSite($cadastro->dadosServico['valor_bruto']), 'tabular-nums');
			detalheLinha('Desconto', $cadastro->converteValorSite($cadastro->dadosServico['valor_desconto']), 'tabular-nums');
			detalheLinha(
				'Forma de Pagamento',
				$cadastro->dadosServico['tipoPagamento'] . ' <span class="ml-2 text-xs text-slate-500">desconto: ' . $cadastro->converteValorSite($cadastro->dadosServico['valor_desconto_pagamento']) . ' (' . $cadastro->dadosServico['p_desconto'] . '%)</span>'
			);
			detalheLinha('Valor Final', '<span class="font-semibold text-emerald-700 text-base tabular-nums">' . $cadastro->converteValorSite($cadastro->dadosServico['valor_final']) . '</span>');
			detalheLinha('Recebido', $cadastro->dadosServico['recebidoCheck'] . ($cadastro->dadosServico['data_recebido'] ? ' <span class="ml-2 text-xs text-slate-500">em ' . $cadastro->dadosServico['data_recebido'] . '</span>' : ''));
			detalheLinha('Gratuidade', $cadastro->dadosServico['nota']);
			detalheLinha('Observações', $cadastro->dadosServico['observacoes'] ?: '<span class="text-slate-400">—</span>');

			if (in_array($cadastro->dadosServico['tipoPagamento'], ['DINHEIRO', 'CHEQUE', 'TRANSFERÊNCIA BANCÁRIA'], true)) {
				detalheLinha(
					'Previsão de Depósito',
					'até <span class="font-medium">' . $cadastro->dadosServico['dataPrevisao'] . '</span>'
					. ($cadastro->dadosServico['dataDeposito'] ? ' · depositado em <span class="font-medium">' . $cadastro->dadosServico['dataDeposito'] . '</span>' : '')
				);
			}
		?>
	</section>

	<!-- Acoes condicionais: marcar recebido / depositado -->
	<form action="?s=detalhes&id=<?php echo $id; ?>" method="post" class="space-y-4">

		<?php if ($cadastro->dadosServico['recebido'] == 'N'): ?>
		<section class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
			<h3 class="text-base font-semibold text-slate-900 mb-4">Marcar como recebido</h3>
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
				<div>
					<label for="txtDataRecebimento" class="block text-sm font-medium text-slate-700 mb-1.5">Data do Recebimento</label>
					<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataRecebimento" name="txtDataRecebimento" onkeyup="Formatadata(this,event)" />
				</div>
				<div>
					<label for="txtDataPrevisao" class="block text-sm font-medium text-slate-700 mb-1.5">Previsão de Depósito</label>
					<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataPrevisao" name="txtDataPrevisao" onkeyup="Formatadata(this,event)" />
				</div>
				<div>
					<button type="submit" name="btnRecebido" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg cursor-pointer transition-colors">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
						Confirmar recebimento
					</button>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php
			if (
				$cadastro->dadosServico['recebido'] == 'S' &&
				$cadastro->dadosServico['depositado'] == 'N' &&
				in_array($cadastro->dadosServico['tipoPagamento'], ['DINHEIRO', 'CHEQUE', 'TRANSFERÊNCIA BANCÁRIA'], true)
			):
		?>
		<section class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
			<h3 class="text-base font-semibold text-slate-900 mb-4">Marcar como depositado</h3>
			<?php echo $cadastro->dadosServico['depositadoButton']; ?>
		</section>
		<?php endif; ?>

		<?php if ($cadastro->dadosServico['depositado'] == 'S' && strlen($cadastro->dadosCheque['data_solucao']) < 3): ?>
		<section class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
			<?php echo $cadastro->dadosServico['compensadoButton']; ?>
		</section>
		<?php endif; ?>

	</form>

	<!-- Formulario de Nao Compensado / Solucao -->
	<?php if ($cadastro->dadosServico['compensado'] != 'S'): ?>
	<section class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
		<h3 class="text-base font-semibold text-slate-900 mb-4">Solução de pagamento não compensado</h3>

		<form action="?s=detalhes&id=<?php echo $id; ?>" method="post" class="space-y-4">

			<div>
				<label for="txtMotivo" class="block text-sm font-medium text-slate-700 mb-1.5">Motivo</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtMotivo" name="txtMotivo" value="<?php echo $cadastro->dadosCheque['motivo']; ?>" />
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label for="txtBanco" class="block text-sm font-medium text-slate-700 mb-1.5">Banco</label>
					<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtBanco" name="txtBanco" value="<?php echo $cadastro->dadosCheque['banco']; ?>" />
				</div>
				<div>
					<label for="txtCheque" class="block text-sm font-medium text-slate-700 mb-1.5">Info Cheque / Conta</label>
					<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtCheque" name="txtCheque" value="<?php echo $cadastro->dadosCheque['n_cheque']; ?>" />
				</div>
			</div>

			<div>
				<label for="txtSolucao" class="block text-sm font-medium text-slate-700 mb-1.5">Solução</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtSolucao" name="txtSolucao" value="<?php echo $cadastro->dadosCheque['solucao']; ?>" />
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label for="txtDataSolucao" class="block text-sm font-medium text-slate-700 mb-1.5">Data da Solução</label>
					<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataSolucao" name="txtDataSolucao" onkeyup="Formatadata(this,event)" value="<?php echo $cadastro->dadosCheque['data_solucao']; ?>" />
				</div>
				<div>
					<label for="txtRespSolucao" class="block text-sm font-medium text-slate-700 mb-1.5">Responsável pela Solução</label>
					<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtRespSolucao" name="txtRespSolucao">
						<?php if ($cadastro->dadosCheque['resp_usuario'] < 1) { ?>
							<option value="">Selecione...</option>
							<?php $cadastro->RetornaUsuariosEdit('0'); ?>
						<?php } else { ?>
							<option value="<?php echo $cadastro->dadosCheque['resp_usuario']; ?>"><?php echo $cadastro->dadosCheque['nome_usuario']; ?></option>
							<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosCheque['resp_usuario']); ?>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="flex justify-end">
				<button type="submit" name="<?php echo $nameButtonSolucao; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
					Salvar
				</button>
			</div>

		</form>
	</section>
	<?php endif; ?>

</div>
