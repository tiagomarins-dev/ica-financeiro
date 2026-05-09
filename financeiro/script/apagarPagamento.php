<?php
/**
 * Created by PhpStorm.
 * User: tiagomarins
 * Date: 09/10/2017
 * Time: 08:49
 */

require_once __DIR__ . '/../classes/autoload.php';
$cadastro = new cadastro();

if(!isset($_POST['id'])) { die(); }

// Garante que $id é numérico antes de repassar (apagarPagamento usa bind_param 'i')
$id = (int) $_POST['id'];

$cadastro->apagarPagamento($id);
