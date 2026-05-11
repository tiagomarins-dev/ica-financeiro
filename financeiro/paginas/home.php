<?php

	$mes = (isset($_GET['m'])) ? $_GET['m'] : date('m');
	$ano = (isset($_GET['a'])) ? $_GET['a'] : date('Y');

	if(($mes >= 1) && ($mes <= 12)) { $mes = $mes; } else { $mes = date('m'); }
	if(($ano > 1980) && ($mes < 2114)) { $ano = $ano; } else { $ano = date('Y'); }

?>

<div class="space-y-6">

	<!-- Banner: total deposito pendente -->
	<a href="?s=depositoPendente" class="flex items-center gap-4 px-5 py-4 rounded-xl bg-amber-50 border border-amber-200 hover:bg-amber-100 hover:border-amber-300 cursor-pointer transition-colors duration-150 group">
		<div class="flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-lg bg-amber-100 text-amber-700 ring-1 ring-amber-200">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
			</svg>
		</div>
		<div class="flex-1 min-w-0">
			<div class="text-xs font-medium text-amber-800 uppercase tracking-wide">Depósitos pendentes</div>
			<div class="text-lg font-semibold text-amber-900 mt-0.5"><?php echo $relatorio->RetornaSomaDepositoPendente(''); ?></div>
		</div>
		<svg class="w-5 h-5 text-amber-700 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
			<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
		</svg>
	</a>

	<!-- Resumo -->
	<section>
		<div class="flex items-end justify-between mb-3">
			<div>
				<h1 class="text-xl font-semibold text-slate-900">Resumo</h1>
				<p class="text-sm text-slate-500 mt-0.5">Faturamento mensal consolidado</p>
			</div>
			<a href="index.php?s=historico" class="text-sm font-medium text-brand-600 hover:text-brand-700 cursor-pointer inline-flex items-center gap-1">
				Ver tudo
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
				</svg>
			</a>
		</div>

		<div class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
			<?php $relatorio->RetornaResumo(); ?>
		</div>
	</section>

<?php
	if(isset($_POST['btnInserirAnestesia']))   $cadastro->Cadastrar();
	elseif(isset($_POST['btnInserirDespesa'])) $cadastro->CadastrarDespesa();
	elseif(isset($_POST['btnImportaDespesa'])) $cadastro->ImportacaoDespesas();
	elseif(isset($_POST['btnDelServico']))     $cadastro->ApagarServico();
	elseif(isset($_POST['btnDelDespesa']))     $cadastro->ApagarDespesaMensal();
?>

	<!-- Action bar: dropdown de mes + acoes -->
	<section class="flex flex-wrap items-center gap-2">
		<div class="relative" id="dropdownMes">
			<button type="button" id="dropdownMesBtn" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-900 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 hover:border-slate-400 cursor-pointer transition-colors duration-150">
				<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
				</svg>
				<?php echo $cadastro->RenomeiaMeses($mes,$ano); ?>
				<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
				</svg>
			</button>
			<ul id="dropdownMesList" class="hidden absolute z-20 mt-1 w-56 max-h-72 overflow-y-auto bg-white rounded-lg shadow-lg ring-1 ring-slate-200 py-1 text-sm">
				<?php $cadastro->ListaMeses($mes,$ano); ?>
			</ul>
		</div>
		<a name="inicio"></a>

		<a href="?s=inserirServico" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm cursor-pointer transition-colors duration-150">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
			</svg>
			Inserir Serviço
		</a>

		<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg2"
			onclick="document.getElementById('modalInserirDespesa')?.classList.remove('hidden')"
			class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 ring-1 ring-amber-200 rounded-lg cursor-pointer transition-colors duration-150">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.549 2.799a2.121 2.121 0 1 1 3 3L5.232 22.117a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
			</svg>
			Inserir Despesa
		</button>

		<button type="button" data-toggle="modal" data-target=".bs-example-modal-lg3"
			onclick="document.getElementById('modalImportaDespesa')?.classList.remove('hidden')"
			class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors duration-150">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25"/>
			</svg>
			Importar Despesas Fixas
		</button>

		<a href="#final" class="ml-auto inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors duration-150">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
			</svg>
			Final
		</a>
	</section>

	<!-- Accordions: Despesas + Anestesias -->
	<section class="space-y-3">

		<!-- Despesas -->
		<div class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
			<button type="button" class="w-full flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-slate-50 transition-colors" data-accordion-toggle="painelDespesas">
				<div class="flex items-center gap-3">
					<svg class="w-5 h-5 text-slate-400 transition-transform" data-accordion-arrow viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
					</svg>
					<span class="text-base font-semibold text-slate-900">Despesas</span>
				</div>
				<div class="text-sm text-slate-600">
					Total: <span class="font-semibold text-slate-900">R$ <?php echo $cadastro->SomaDespesaMes($mes,$ano); ?></span>
				</div>
			</button>
			<div id="painelDespesas" class="hidden border-t border-slate-100">
				<?php $cadastro->ListaDespesas($mes,$ano); ?>
			</div>
		</div>

		<!-- Anestesias -->
		<div class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
			<button type="button" class="w-full flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-slate-50 transition-colors" data-accordion-toggle="painelAnestesias">
				<div class="flex items-center gap-3">
					<svg class="w-5 h-5 text-slate-400 transition-transform rotate-90" data-accordion-arrow viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
					</svg>
					<span class="text-base font-semibold text-slate-900">Anestesias</span>
				</div>
			</button>
			<div id="painelAnestesias" class="border-t border-slate-100">
				<?php $cadastro->ListaServico($mes,$ano); ?>
			</div>
		</div>

	</section>

	<!-- Voltar ao topo -->
	<div class="flex justify-end">
		<a href="#inicio" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors duration-150">
			<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/>
			</svg>
			Voltar ao topo
		</a>
	</div>

</div>

<script>
	// Dropdown de mes
	(function() {
		const btn = document.getElementById('dropdownMesBtn');
		const list = document.getElementById('dropdownMesList');
		if (!btn) return;
		btn.addEventListener('click', e => { e.stopPropagation(); list.classList.toggle('hidden'); });
		document.addEventListener('click', e => { if (!btn.parentElement.contains(e.target)) list.classList.add('hidden'); });
	})();

	// Accordions
	document.querySelectorAll('[data-accordion-toggle]').forEach(btn => {
		btn.addEventListener('click', () => {
			const id = btn.getAttribute('data-accordion-toggle');
			const panel = document.getElementById(id);
			const arrow = btn.querySelector('[data-accordion-arrow]');
			if (panel.classList.contains('hidden')) { panel.classList.remove('hidden'); arrow?.classList.add('rotate-90'); }
			else { panel.classList.add('hidden'); arrow?.classList.remove('rotate-90'); }
		});
	});
</script>
