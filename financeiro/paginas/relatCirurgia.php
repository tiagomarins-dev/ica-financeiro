<?php
	$dataAtual = date('d/m/Y');
	$dataInicial01 = '01/' . date('m/Y');
	$dataInicio = (isset($_POST['txtDataInicio'])) ? $_POST['txtDataInicio'] : $dataInicial01;
	$dataFim = (isset($_POST['txtDataFim'])) ? $_POST['txtDataFim'] : $dataAtual;
	$cirurgia = (isset($_POST['txtCirurgia'])) ? $_POST['txtCirurgia'] : '';
	include __DIR__ . '/_relatTabs.php';
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Relatório de Cirurgias</h1>
		<p class="mt-1 text-sm text-slate-500">Volume e valor por tipo de cirurgia</p>
	</header>

	<?php renderRelatTabs('relatCirurgia'); ?>

	<form action="?s=relatCirurgia" method="post" class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
			<div>
				<label for="txtDataInicio" class="block text-sm font-medium text-slate-700 mb-1.5">Data inicial</label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataInicio" name="txtDataInicio" onkeyup="Formatadata(this,event)" value="<?php echo $dataInicio; ?>" />
			</div>
			<div>
				<label for="txtDataFim" class="block text-sm font-medium text-slate-700 mb-1.5">Data final</label>
				<input type="<?php echo $typeData; ?>" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" id="txtDataFim" name="txtDataFim" onkeyup="Formatadata(this,event)" value="<?php echo $dataFim; ?>" />
			</div>
			<div>
				<label for="txtCirurgia" class="block text-sm font-medium text-slate-700 mb-1.5">Cirurgia</label>
				<select class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" id="txtCirurgia" name="txtCirurgia">
					<?php $cadastro->ListaCirurgiaForm($cirurgia); ?>
				</select>
			</div>
			<div>
				<button type="submit" name="btnBuscar" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
					Gerar relatório
				</button>
			</div>
		</div>
	</form>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php
			if (!isset($_POST['btnBuscar'])) $cirurgia = '';
			$relatorio->RetornaSomaCirurgiaPeriodo($dataInicio, $dataFim, $cirurgia);
		?>
	</section>

</div>
