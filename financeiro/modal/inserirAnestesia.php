<!-- Modal: Inserir Servico (Anestesia) -->
<div id="modalInserirAnestesia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-anestesia-titulo" role="dialog" aria-modal="true">
	<div class="flex min-h-full items-end sm:items-center justify-center p-4">
		<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" data-modal-close="modalInserirAnestesia"></div>

		<div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200">
			<!-- Header -->
			<div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
				<h2 id="modal-anestesia-titulo" class="text-lg font-semibold text-slate-900">Inserir Serviço</h2>
				<button type="button" data-modal-close="modalInserirAnestesia" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
				</button>
			</div>

			<!-- Body -->
			<form action="index.php" method="post" onsubmit="return validaFormServico();">
				<div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">

					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<div>
							<label for="txtData" class="block text-sm font-medium text-slate-700 mb-1.5">Data <span class="text-red-500">*</span></label>
							<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtData" name="txtData" />
						</div>
						<div>
							<label for="txtDataPrevisao" class="block text-sm font-medium text-slate-700 mb-1.5">Previsão de Depósito</label>
							<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataPrevisao" name="txtDataPrevisao" />
						</div>
					</div>

					<div>
						<label for="txtCliente" class="block text-sm font-medium text-slate-700 mb-1.5">Cliente (Cirurgião) <span class="text-red-500">*</span></label>
						<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtCliente" name="txtCliente" />
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<div>
							<label for="txtAtendimento1" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 1</label>
							<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento1" name="txtAtendimento1">
								<option value="">Selecione...</option>
								<option value="01">Edu e Pietro</option>
								<option value="02">Jaime</option>
								<option value="03">Antônio</option>
								<option value="04">Sérgio</option>
								<option value="05">Edu e Jaime</option>
								<option value="06">Eduardo</option>
							</select>
						</div>
						<div>
							<label for="txtAtendimento2" class="block text-sm font-medium text-slate-700 mb-1.5">Anestesista 2</label>
							<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtAtendimento2" name="txtAtendimento2">
								<option value="">Selecione...</option>
								<option value="01">Edu e Pietro</option>
								<option value="02">Jaime</option>
								<option value="03">Antônio</option>
								<option value="04">Sérgio</option>
								<option value="05">Edu e Jaime</option>
								<option value="06">Eduardo</option>
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

					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<div>
							<label for="txtValorBruto" class="block text-sm font-medium text-slate-700 mb-1.5">Valor Bruto <span class="text-red-500">*</span></label>
							<div class="relative">
								<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
								<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorBruto" name="txtValorBruto" placeholder="0,00" />
							</div>
						</div>
						<div>
							<label for="txtDesconto" class="block text-sm font-medium text-slate-700 mb-1.5">Desconto</label>
							<div class="relative">
								<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
								<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDesconto" name="txtDesconto" placeholder="0,00" />
							</div>
						</div>
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
						<div>
							<label for="txtPagamento" class="block text-sm font-medium text-slate-700 mb-1.5">Pagamento <span class="text-red-500">*</span></label>
							<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtPagamento" name="txtPagamento">
								<option value="">Selecione...</option>
								<?php $cadastro->ListaFormaPagamentoForm(); ?>
							</select>
						</div>
						<div>
							<span class="block text-sm font-medium text-slate-700 mb-1.5">Nota</span>
							<div class="flex items-center gap-4 py-2">
								<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
									<input type="radio" name="chkNota" value="S" checked class="w-4 h-4 text-brand-600" />
									Sim
								</label>
								<label class="inline-flex items-center gap-1.5 text-sm cursor-pointer">
									<input type="radio" name="chkNota" value="N" class="w-4 h-4 text-brand-600" />
									Não
								</label>
							</div>
						</div>
						<div>
							<label class="inline-flex items-center gap-2 text-sm cursor-pointer py-2">
								<input type="checkbox" name="chkRecebido" id="chkRecebido" class="w-4 h-4 text-brand-600 rounded" />
								Recebido
							</label>
						</div>
					</div>

					<div>
						<label for="txtObs" class="block text-sm font-medium text-slate-700 mb-1.5">Observações</label>
						<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtObs" name="txtObs" />
					</div>

				</div>

				<!-- Footer -->
				<div class="flex items-center justify-end gap-2 px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl">
					<button type="button" data-modal-close="modalInserirAnestesia" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">Cancelar</button>
					<button type="submit" name="btnInserirAnestesia" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">Inserir Serviço</button>
				</div>
			</form>
		</div>
	</div>
</div>
