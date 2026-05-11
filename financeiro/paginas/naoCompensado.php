<div class="space-y-6">
	<header>
		<h1 class="text-xl font-semibold text-slate-900">Não Compensados</h1>
		<p class="mt-1 text-sm text-slate-500">Cheques que ainda não foram compensados</p>
	</header>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $relatorio->Pendentes('compensado',''); ?>
	</section>
</div>
