<?php

	if (isset($_POST['btnSalvarCartao'])) {
		$cadastro->EditarDepositoCartao();
	}

	if (isset($_GET['i'])) {
		$data_inicial = $_GET['i'];
		$data_inicial_br = $_GET['i'];
	} elseif (isset($_POST['data_inicio'])) {
		$data_inicial = $_POST['data_inicio'];
		$data_inicial_br = $_POST['data_inicio'];
	} else {
		$data_inicial = "01/" . date('m/Y');
		$data_inicial_br = "01/" . date('m/Y');
	}

	if (isset($_GET['f'])) {
		$data_final = $_GET['f'];
		$data_final_br = $_GET['f'];
	} elseif (isset($_POST['data_fim'])) {
		$data_final = $_POST['data_fim'];
		$data_final_br = $_POST['data_fim'];
	} else {
		$mes = date('m');
		$ano = date("Y");
		$dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$data_final = "$dia/$mes/$ano";
		$data_final_br = "$dia/$mes/$ano";
	}

	$id_cartao = isset($_POST['filtro']) ? $_POST['filtro'] : 0;

	$mes = (isset($_GET['m'])) ? $_GET['m'] : date('m');
	$ano = (isset($_GET['a'])) ? $_GET['a'] : date('Y');
	if (!(($mes >= 1) && ($mes <= 12))) $mes = date('m');
	if (!(($ano > 1980) && ($mes < 2114))) $ano = date('Y');

	$totalReceber  = $cadastro->RetornaTotalReceber($data_inicial, $data_final, $id_cartao);
	$totalRecebido = $cadastro->RetornaTotalRecebido($data_inicial, $data_final, $id_cartao);
	$lista_cartoes = $cadastro->listaCartoesSelect();
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Conferência de Cartões</h1>
		<p class="mt-1 text-sm text-slate-500">Compare valores a receber x recebidos por máquina</p>
	</header>

	<!-- Filtros + KPIs -->
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

		<form action="?s=checkCartao" method="post" class="lg:col-span-2 bg-white rounded-xl ring-1 ring-slate-200 p-6">
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
				<div>
					<label for="data_inicio" class="block text-sm font-medium text-slate-700 mb-1.5">Data inicial</label>
					<input type="text" name="data_inicio" id="data_inicio" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" value="<?php echo $data_inicial_br; ?>" />
				</div>
				<div>
					<label for="data_fim" class="block text-sm font-medium text-slate-700 mb-1.5">Data final</label>
					<input type="text" name="data_fim" id="data_fim" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500" value="<?php echo $data_final_br; ?>" />
				</div>
				<div>
					<label for="filtro" class="block text-sm font-medium text-slate-700 mb-1.5">Filtro</label>
					<select name="filtro" id="filtro" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white">
						<option value="<?php echo $id_cartao; ?>"><?php echo $cadastro->getNomeCartao($id_cartao); ?></option>
						<?php foreach ($lista_cartoes as $cartao) { ?>
							<option value="<?php echo $cartao['id']; ?>"><?php echo $cartao['tipo']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<button type="submit" name="btnFiltrar" id="btnFiltrar" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg cursor-pointer transition-colors">
						Filtrar
					</button>
				</div>
			</div>
		</form>

		<div class="space-y-3">
			<div class="bg-white rounded-xl ring-1 ring-slate-200 px-5 py-4">
				<div class="text-xs font-medium text-slate-500 uppercase tracking-wide">A receber</div>
				<div class="text-xl font-semibold text-slate-900 mt-1 tabular-nums"><?php echo $totalReceber; ?></div>
			</div>
			<div class="bg-white rounded-xl ring-1 ring-emerald-200 bg-emerald-50/40 px-5 py-4">
				<div class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Recebido</div>
				<div class="text-xl font-semibold text-emerald-700 mt-1 tabular-nums"><?php echo $totalRecebido; ?></div>
			</div>
		</div>

	</div>

	<link href="estilos/switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $cadastro->ListaServicoCartao($data_inicial, $data_final, $id_cartao); ?>
	</section>

</div>

<script src="estilos/datepicker/js/bootstrap-datepicker.js"></script>
<script>
	$(function(){
		$('#data_inicio, #data_fim').datepicker({ format: 'dd/mm/yyyy', language: 'pt-BR' });
	});
</script>
<script src="estilos/switch/dist/js/bootstrap-switch.js"></script>
