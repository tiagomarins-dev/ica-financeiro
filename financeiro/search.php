<?php
// Endpoint de autocomplete - recebe termo de busca e tipo de campo via $_GET
// Reescrito para usar prepared statements e validar o tipo contra um allowlist (proteção SQL injection)

$q = $_GET['term'] ?? '';
$p = $_GET['p'] ?? '';

// Padding para termos numéricos de 1 dígito (mantém comportamento legado)
if (is_numeric($q) && strlen($q) == 1) {
	$q = '0' . $q;
}

if ($q === '') return;

include_once "classes/conexao.class.php";

$conn = new conexao();

// Mapa de tipos permitidos para coluna/tabela - bloqueia qualquer valor de $_GET['p'] não previsto
$mapa = [
	'cliente'      => ['coluna' => 'cliente',              'tabela' => 'servicos'],
	'cirurgia'     => ['coluna' => 'cirurgia',             'tabela' => 'servicos'],
	'modelo'       => ['coluna' => 'modelo',               'tabela' => 'info_patrimonio'],
	'placamae'     => ['coluna' => 'placa_mae',            'tabela' => 'info_patrimonio'],
	'processador'  => ['coluna' => 'processador',          'tabela' => 'info_patrimonio'],
	'memoria'      => ['coluna' => 'memoria',              'tabela' => 'info_patrimonio'],
	'hd'           => ['coluna' => 'hd',                   'tabela' => 'info_patrimonio'],
	'drive'        => ['coluna' => 'cd_dvd',               'tabela' => 'info_patrimonio'],
	'sistema'      => ['coluna' => 'sistema_operacional',  'tabela' => 'info_patrimonio'],
];

if (!isset($mapa[$p])) {
	echo json_encode([]);
	return;
}

$coluna = $mapa[$p]['coluna'];
$tabela = $mapa[$p]['tabela'];

// Coluna e tabela vêm do allowlist (não do usuário) - seguro interpolar
$query = "SELECT DISTINCT {$coluna} AS termo FROM {$tabela} WHERE {$coluna} LIKE ? LIMIT 20";

$mysqli = $conn->Conectar();
$stmt = mysqli_prepare($mysqli, $query);
$like = '%' . $q . '%';
mysqli_stmt_bind_param($stmt, 's', $like);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$items = array();
while ($linhas = mysqli_fetch_assoc($resultado)) {
	$items[] = array(
		"label" => $linhas['termo'],
		"id"    => '01',
	);
}

echo json_encode($items);
