<!-- Modal: Inserir Despesa -->
<div id="modalInserirDespesa" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-despesa-titulo" role="dialog" aria-modal="true">
	<div class="flex min-h-full items-end sm:items-center justify-center p-4">
		<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" data-modal-close="modalInserirDespesa"></div>

		<div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200">
			<!-- Header -->
			<div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
				<h2 id="modal-despesa-titulo" class="text-lg font-semibold text-slate-900">Inserir Despesa</h2>
				<button type="button" data-modal-close="modalInserirDespesa" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
				</button>
			</div>

			<form action="index.php" method="post">
				<div class="px-6 py-5 space-y-4">

					<div>
						<label for="txtDataDespesa" class="block text-sm font-medium text-slate-700 mb-1.5">Data <span class="text-red-500">*</span></label>
						<input type="<?php echo $typeData; ?>" class="date block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataDespesa" name="txtDataDespesa" onkeyup="Formatadata(this,event)" />
					</div>

					<div>
						<label for="txtDespesa" class="block text-sm font-medium text-slate-700 mb-1.5">Despesa <span class="text-red-500">*</span></label>
						<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDespesa" name="txtDespesa" />
					</div>

					<div>
						<label for="txtDescricao" class="block text-sm font-medium text-slate-700 mb-1.5">Descrição</label>
						<input type="text" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDescricao" name="txtDescricao" />
					</div>

					<div>
						<label for="txtValorDespesa" class="block text-sm font-medium text-slate-700 mb-1.5">Valor <span class="text-red-500">*</span></label>
						<div class="relative">
							<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">R$</span>
							<input type="text" class="money block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtValorDespesa" name="txtValorDespesa" placeholder="0,00" />
						</div>
					</div>

				</div>

				<div class="flex items-center justify-end gap-2 px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl">
					<button type="button" data-modal-close="modalInserirDespesa" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">Cancelar</button>
					<button type="submit" name="btnInserirDespesa" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg cursor-pointer transition-colors">Inserir Despesa</button>
				</div>
			</form>
		</div>
	</div>
</div>
