<div class="space-y-6">

	<div class="flex items-center justify-between">
		<h1 class="text-xl font-semibold text-slate-900">Inserir Serviço</h1>
		<a href="?s=home" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
			Voltar
		</a>
	</div>

	<form action="index.php" method="post" onsubmit="return validaFormServico();" class="bg-white rounded-xl ring-1 ring-slate-200 p-6 space-y-4">

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtData" class="block text-sm font-medium text-slate-700 mb-1.5">Data <span class="text-red-500">*</span></label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtData" name="txtData" onkeyup="Formatadata(this,event)" />
			</div>
			<div>
				<label for="txtDataPrevisao" class="block text-sm font-medium text-slate-700 mb-1.5">Previsão de Depósito</label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataPrevisao" name="txtDataPrevisao" onkeyup="Formatadata(this,event)" />
			</div>
		</div>

		<div>
			<label for="txtCliente" class="block text-sm font-medium text-slate-700 mb-1.5">Cliente (Cirurgião) <span class="text-red-500">*</span></label>
			<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtCliente" name="txtCliente">
				<option value="">Selecione...</option>
				<?php $cadastro->ListaClientesForm(); ?>
			</select>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
			<div>
				<label for="txtAtendimento1" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 1 <span class="text-red-500">*</span></label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento1" name="txtAtendimento1">
					<option value="">Selecione...</option>
					<?php $cadastro->RetornaUsuariosAtendimento(); ?>
				</select>
			</div>
			<div>
				<label for="txtAtendimento2" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 2</label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento2" name="txtAtendimento2">
					<option value="">Selecione...</option>
					<?php $cadastro->RetornaUsuariosAtendimento(); ?>
				</select>
			</div>
		</div>

		<div>
			<label for="txtPaciente" class="block text-sm font-medium text-slate-700 mb-1.5">Paciente <span class="text-red-500">*</span></label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtPaciente" name="txtPaciente" />
		</div>

		<div>
			<label for="txtCirurgia" class="block text-sm font-medium text-slate-700 mb-1.5">Cirurgia <span class="text-red-500">*</span></label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtCirurgia" name="txtCirurgia" />
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
			<div>
				<label for="txtValorBruto" class="block text-sm font-medium text-slate-700 mb-1.5">Valor Bruto <span class="text-red-500">*</span></label>
				<div class="relative">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
					<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorBruto" name="txtValorBruto" placeholder="0,00" />
				</div>
			</div>
			<div>
				<label for="txtPagamento" class="block text-sm font-medium text-slate-700 mb-1.5">Pagamento <span class="text-red-500">*</span></label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtPagamento" name="txtPagamento">
					<option value="">Selecione...</option>
					<?php $cadastro->ListaFormaPagamentoForm(); ?>
				</select>
			</div>
			<div>
				<label class="inline-flex items-center gap-2 text-sm cursor-pointer py-2">
					<input type="checkbox" name="chkRecebido" id="chkRecebido" class="w-4 h-4 text-brand-600 rounded" />
					Recebido
				</label>
			</div>
		</div>

		<div>
			<span class="block text-sm font-medium text-slate-700 mb-1.5">Gratuidade</span>
			<div class="flex items-center gap-4 py-2">
				<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
					<input type="radio" name="chkNota" value="N" class="w-4 h-4 text-brand-600" />
					Sim
				</label>
				<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
					<input type="radio" name="chkNota" value="S" checked class="w-4 h-4 text-brand-600" />
					Não
				</label>
			</div>
		</div>

		<div>
			<label for="txtObs" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
			<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtObs" name="txtObs" />
		</div>

		<div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
			<a href="?s=home" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">Cancelar</a>
			<button type="submit" name="btnInserirAnestesia" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
				Inserir Serviço
			</button>
		</div>

	</form>
</div>
