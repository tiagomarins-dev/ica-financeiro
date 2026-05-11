<?php

	if(isset($_POST['btnEditarServico'])) {
		$cadastro->EditarServico();
	}

	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
	$cadastro->DetalhesServico($id,$typeData);

	$recebidoCheck = ($cadastro->dadosServico['recebido'] == 'S') ? 'checked' : '';

?>

<div class="space-y-6">

	<div class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-slate-900">Editar Serviço</h1>
			<p class="mt-1 text-sm text-slate-500"><?php echo $cadastro->dadosServico['nome_paciente']; ?></p>
		</div>
		<a href="?s=detalhes&id=<?php echo $id; ?>" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
			Voltar
		</a>
	</div>

	<form action="index.php?s=editarServico&id=<?php echo $id; ?>" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<input type="hidden" name="txtIdServico" value="<?php echo $cadastro->dadosServico['id']; ?>" />

		<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
			<div>
				<label for="txtData" class="block text-sm font-medium text-slate-700 mb-1.5">Data <span class="text-red-500">*</span></label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtData" name="txtData" value="<?php echo $cadastro->dadosServico['dataServico'] ?>" />
			</div>
			<div>
				<label for="txtDataPrevisao" class="block text-sm font-medium text-slate-700 mb-1.5">Previsão Depósito</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataPrevisao" name="txtDataPrevisao" value="<?php echo $cadastro->dadosServico['dataPrevisao'] ?>" />
			</div>
			<div>
				<label for="txtDataDeposito" class="block text-sm font-medium text-slate-700 mb-1.5">Data de Depósito</label>
				<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataDeposito" name="txtDataDeposito" value="<?php echo $cadastro->dadosServico['dataDeposito'] ?>" />
			</div>
		</div>

		<div>
			<label for="txtCliente" class="block text-sm font-medium text-slate-700 mb-1.5">Cliente (Cirurgião) <span class="text-red-500">*</span></label>
			<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtCliente" name="txtCliente">
				<option value="<?php echo $cadastro->dadosServico['idCliente'] ?>"><?php echo $cadastro->dadosServico['nome_cliente']; ?></option>
				<?php $cadastro->ListaClientesFormEdit($cadastro->dadosServico['idCliente']); ?>
			</select>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtAtendimento1" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 1</label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento1" name="txtAtendimento1">
					<option value="<?php echo $cadastro->dadosServico['atendimento1'] ?>"><?php echo $cadastro->dadosServico['nomeAtendimento1'] ?></option>
					<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosServico['atendimento1']); ?>
					<option value="0">---</option>
				</select>
			</div>
			<div>
				<label for="txtAtendimento2" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 2</label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento2" name="txtAtendimento2">
					<option value="<?php echo $cadastro->dadosServico['atendimento2'] ?>"><?php echo $cadastro->dadosServico['nomeAtendimento2'] ?></option>
					<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosServico['atendimento2']); ?>
					<option value="0">---</option>
				</select>
			</div>
		</div>

		<div>
			<label for="txtPaciente" class="block text-sm font-medium text-slate-700 mb-1.5">Paciente <span class="text-red-500">*</span></label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtPaciente" name="txtPaciente" value="<?php echo $cadastro->dadosServico['nome_paciente'] ?>" />
		</div>

		<div>
			<label for="txtCirurgia" class="block text-sm font-medium text-slate-700 mb-1.5">Cirurgia <span class="text-red-500">*</span></label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtCirurgia" name="txtCirurgia" value="<?php echo $cadastro->dadosServico['cirurgia'] ?>" />
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtValorBruto" class="block text-sm font-medium text-slate-700 mb-1.5">Valor Bruto <span class="text-red-500">*</span></label>
				<div class="relative">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
					<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorBruto" name="txtValorBruto" value="<?php echo $cadastro->dadosServico['valor_bruto'] ?>" />
				</div>
			</div>
			<div>
				<label for="txtDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Desconto</label>
				<div class="relative">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
					<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDesconto" name="txtDesconto" value="<?php echo $cadastro->dadosServico['valor_desconto'] ?>" />
				</div>
			</div>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
			<div>
				<label for="txtPagamento" class="block text-sm font-medium text-slate-700 mb-1.5">Pagamento <span class="text-red-500">*</span></label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtPagamento" name="txtPagamento">
					<option value="<?php echo $cadastro->dadosServico['idPagamento'] ?>"><?php echo $cadastro->dadosServico['tipoPagamento']; ?></option>
					<?php $cadastro->ListaFormaPagamentoFormEdit($cadastro->dadosServico['idPagamento']); ?>
				</select>
			</div>
			<div>
				<span class="block text-sm font-medium text-slate-700 mb-1.5">Gratuidade</span>
				<div class="flex items-center gap-4 py-2">
					<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
						<input type="radio" name="chkNota" value="N" class="w-4 h-4 text-brand-600" <?php echo $cadastro->dadosServico['notaSim']; ?> />
						Sim
					</label>
					<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
						<input type="radio" name="chkNota" value="S" class="w-4 h-4 text-brand-600" <?php echo $cadastro->dadosServico['notaNao']; ?> />
						Não
					</label>
				</div>
			</div>
			<div>
				<label class="inline-flex items-center gap-2 text-sm cursor-pointer py-2">
					<input type="checkbox" name="chkRecebido" id="chkRecebido" class="w-4 h-4 text-brand-600 rounded" <?php echo $recebidoCheck; ?> />
					Recebido
				</label>
			</div>
		</div>

		<div>
			<label for="txtObs" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtObs" name="txtObs" value="<?php echo $cadastro->dadosServico['observacoes'] ?>" />
		</div>

		<div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
			<a href="?s=detalhes&id=<?php echo $id; ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">Cancelar</a>
			<button type="submit" name="btnEditarServico" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
				Salvar alterações
			</button>
		</div>

	</form>
</div>
