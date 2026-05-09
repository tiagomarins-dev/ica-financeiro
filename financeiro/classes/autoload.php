<?php
// Carrega variaveis de ambiente do arquivo .env (sem dependencia externa)
// .env vive na raiz do docroot (mesmo nivel de index.php), uma pasta acima de classes/
// Parser proprio - parse_ini_file nao tolera caracteres como : e ( em comentarios
$envFile = __DIR__ . '/../.env';
if (is_file($envFile) && is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);
        if ($linha === '' || $linha[0] === '#' || $linha[0] === ';') continue;
        if (strpos($linha, '=') === false) continue;
        [$chave, $valor] = explode('=', $linha, 2);
        $chave = trim($chave);
        $valor = trim($valor);
        // Remove aspas envolventes do valor, se houver
        if (strlen($valor) >= 2 && (($valor[0] === '"' && substr($valor, -1) === '"') || ($valor[0] === "'" && substr($valor, -1) === "'"))) {
            $valor = substr($valor, 1, -1);
        }
        // Nao sobrescreve valores ja definidos pelo ambiente (ex: variaveis do painel da hospedagem)
        if (getenv($chave) === false) {
            putenv("{$chave}={$valor}");
            $_ENV[$chave] = $valor;
        }
    }
}

// Registra autoload das classes do projeto - substitui o __autoload removido no PHP 8.0
spl_autoload_register(function ($classe) {
    $arquivo = __DIR__ . "/{$classe}.class.php";
    if (is_file($arquivo)) {
        include_once $arquivo;
    }
});
