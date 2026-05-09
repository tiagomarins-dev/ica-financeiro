<div class="modal fade bs-example-modal-lg3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myLargeModalLabel">Importar Despesas Fixas</h4>
        </div>
        <div class="modal-body">
          
        <form class="form-horizontal" role="form" action="index.php?m=<?php echo $mes; ?>&a=<?php echo $ano; ?>" method="post">
		
		<input type="hidden" name="txtAnoImport" value="<?php echo $ano; ?>" />
		<input type="hidden" name="txtMesImport" value="<?php echo $mes; ?>" />
		
        <?php $cadastro->ListaDespesasFixasFormulario(); ?>
        
        
        
        <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
			<input type="submit" class="btn btn-primary" value="Importar" name="btnImportaDespesa" />
		</div>
      
        </form>
          
          
          
          
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
