<!-- Modal: Importar Despesas Fixas -->
<div id="modalImportaDespesa" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-importa-titulo" role="dialog" aria-modal="true">
	<div class="flex min-h-full items-end sm:items-center justify-center p-4">
		<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" data-modal-close="modalImportaDespesa"></div>

		<div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200">
			<div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
				<h2 id="modal-importa-titulo" class="text-lg font-semibold text-slate-900">Importar Despesas Fixas</h2>
				<button type="button" data-modal-close="modalImportaDespesa" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
				</button>
			</div>

			<form action="index.php?m=<?php echo $mes ?? date('m'); ?>&a=<?php echo $ano ?? date('Y'); ?>" method="post">

				<input type="hidden" name="txtAnoImport" value="<?php echo $ano ?? date('Y'); ?>" />
				<input type="hidden" name="txtMesImport" value="<?php echo $mes ?? date('m'); ?>" />

				<div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
					<p class="text-sm text-slate-500 mb-4">Selecione as despesas fixas que deseja importar para o mês atual.</p>
					<?php $cadastro->ListaDespesasFixasFormulario(); ?>
				</div>

				<div class="flex items-center justify-end gap-2 px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl">
					<button type="button" data-modal-close="modalImportaDespesa" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">Cancelar</button>
					<button type="submit" name="btnImportaDespesa" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">Importar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	// Sistema global de modais (delegacao de eventos)
	document.addEventListener('click', function(e) {
		const closer = e.target.closest('[data-modal-close]');
		if (closer) {
			const id = closer.getAttribute('data-modal-close');
			document.getElementById(id)?.classList.add('hidden');
		}
	});
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			document.querySelectorAll('[role="dialog"]:not(.hidden)').forEach(m => m.classList.add('hidden'));
		}
	});
</script>
