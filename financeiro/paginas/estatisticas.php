<?php
	$mes = (isset($_GET['m'])) ? $_GET['m'] : date('m');
	$ano = (isset($_GET['a'])) ? $_GET['a'] : date('Y');
	if (!(($mes >= 1) && ($mes <= 12))) $mes = date('m');
	if (!(($ano > 1980) && ($mes < 2114))) $ano = date('Y');
	include __DIR__ . '/_relatTabs.php';
?>

<!-- Google Charts API -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php $relatorio->RetornaSomaEquipeChart($mes, $ano); ?>
<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Anestesistas</h1>
		<p class="mt-1 text-sm text-slate-500">Resumo mensal por equipe</p>
	</header>

	<?php renderRelatTabs('estatisticas'); ?>

	<div class="relative inline-block" id="dropdownMesStats">
		<button type="button" id="dropdownMesStatsBtn" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-900 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
			<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
			<?php echo $cadastro->RenomeiaMeses($mes, $ano); ?>
			<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
		</button>
		<ul id="dropdownMesStatsList" class="hidden absolute z-20 mt-1 w-56 max-h-72 overflow-y-auto bg-white rounded-lg shadow-lg ring-1 ring-slate-200 py-1 text-sm">
			<?php $cadastro->ListaMesesStats($mes, $ano); ?>
		</ul>
	</div>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $relatorio->RelatorioPorAnestesista($mes, $ano); ?>
	</section>

	<div id="chart_div" class="bg-white rounded-xl ring-1 ring-slate-200 p-6" style="height: 500px;"></div>

</div>

<script>
	(function() {
		const btn = document.getElementById('dropdownMesStatsBtn');
		const list = document.getElementById('dropdownMesStatsList');
		btn?.addEventListener('click', e => { e.stopPropagation(); list.classList.toggle('hidden'); });
		document.addEventListener('click', e => { if (!btn?.parentElement.contains(e.target)) list?.classList.add('hidden'); });
	})();
</script>
