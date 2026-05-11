<?php
	// Iframe carregado dentro do modal "Cirurgias por Cliente" do relatorio de clientes
	session_name('login');
	session_start();

	require_once __DIR__ . '/classes/autoload.php';
	$relatorio = new relatorios();

	$cliente     = $_GET['c'] ?? '';
	$dataInicio  = $_GET['i'] ?? '';
	$dataFim     = $_GET['f'] ?? '';
	$totalValor  = $_GET['t'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ICA — Cirurgias por Cliente</title>
<link rel="shortcut icon" type="image/x-icon" href="Imagens/favicon.ico">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',system-ui,sans-serif;}</style>
</head>
<body class="bg-slate-50 text-slate-900 p-6">

<div class="space-y-4">
	<h2 class="text-lg font-semibold text-slate-900"><?php echo htmlspecialchars($cliente); ?></h2>
	<div class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $relatorio->RetornaCirurgiaPorCliente($dataInicio, $dataFim, $cliente, $totalValor); ?>
	</div>
</div>

</body>
</html>
