<?php
	// Relatorio de impostos - filtros padrao + tabela RetornaSomaImpostos
	$dataAtual = date('d/m/Y');
	$dataInicial01 = '01/' . date('m/Y', strtotime('-5 months'));
	$dataInicio = (isset($_POST['txtDataInicio'])) ? $_POST['txtDataInicio'] : $dataInicial01;
	$dataFim = (isset($_POST['txtDataFim'])) ? $_POST['txtDataFim'] : $dataAtual;
	include __DIR__ . '/_relatTabs.php';
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Relatório de Impostos</h1>
		<p class="mt-1 text-sm text-slate-500">Apuração mensal de PIS, COFINS, ISS, IR e CS</p>
	</header>

	<?php renderRelatTabs('relatImpostos'); ?>

	<form action="?s=relatImpostos" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
			<div>
				<label for="txtDataInicio" class="block text-sm font-medium text-slate-700 mb-1.5">Data inicial</label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataInicio" name="txtDataInicio" onkeyup="Formatadata(this,event)" value="<?php echo $dataInicio; ?>" />
			</div>
			<div>
				<label for="txtDataFim" class="block text-sm font-medium text-slate-700 mb-1.5">Data final</label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataFim" name="txtDataFim" onkeyup="Formatadata(this,event)" value="<?php echo $dataFim; ?>" />
			</div>
			<div>
				<button type="submit" name="btnBuscar" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
					Gerar Relatório
				</button>
			</div>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $relatorio->RetornaSomaImpostos($dataInicio,$dataFim); ?>
	</section>

</div>
