<?php
	$usuarioPendente = (isset($_GET['u'])) ? $_GET['u'] : '';
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Depósitos Pendentes</h1>
		<p class="mt-1 text-sm text-slate-500">
			Total: <span class="font-semibold text-slate-900 tabular-nums"><?php echo $relatorio->RetornaSomaDepositoPendente(''); ?></span>
		</p>
	</header>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 p-6">
		<h2 class="text-base font-semibold text-slate-900 mb-4">Por Anestesistas</h2>
		<?php $relatorio->TotalPendentePorUsuario(); ?>
	</section>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<div class="px-6 py-4 border-b border-slate-100">
			<h2 class="text-base font-semibold text-slate-900">Lista de pendentes</h2>
		</div>
		<?php $relatorio->Pendentes('deposito',$usuarioPendente); ?>
	</section>

</div>
