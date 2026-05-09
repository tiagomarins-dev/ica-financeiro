<?php
	$pagAtual = (isset($_GET['p'])) ? $_GET['p'] : 1;
?>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php $relatorio->RetornaSomaEquipeChart('10','2014'); ?>
	<?php $relatorio->RetornaSomaClientesChart('10','2014'); ?>
	

<div class="main">

	<h1 class="page-header">Relatório Completo</h1>
          <div class="table-responsive">
            <?php $relatorio->RetornaValoresCompletos($pagAtual); ?>
            <ul class="pager">  
  				<!-- <li class="next"><a href="#">Ver mais &rarr;</a></li> -->
			</ul>
          </div>

	<nav style="text-align: center">
	<ul class="pagination pagination-lg">
	
	<?php

		

		$relatorio->PaginacaoValores($pagAtual);  

	?>
	
	</ul>
	</nav>


  
</div>