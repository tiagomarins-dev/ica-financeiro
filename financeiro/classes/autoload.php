<?php
// Registra autoload das classes do projeto - substitui o __autoload removido no PHP 8.0
spl_autoload_register(function ($classe) {
    $arquivo = __DIR__ . "/{$classe}.class.php";
    if (is_file($arquivo)) {
        include_once $arquivo;
    }
});
