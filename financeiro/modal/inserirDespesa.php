 <div class="modal fade bs-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myLargeModalLabel">Inserir Despesa</h4>
        </div>
        <div class="modal-body">
          
         <form class="form-horizontal" role="form" action="index.php" method="post">
        
            <div class="form-group">
            	<label for="txtDataDespesa" class="col-sm-2 control-label">Data: <span>*</span> </label>
            	<div class="col-sm-3 padInput">
            	<input type="date" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataDespesa" name="txtDataDespesa" placeholder="">
            	</div>
            </div>
            
         
         <div class="form-group">
        	<label for="txtDespesa" class="col-sm-2 control-label">Despesa: <span>*</span> </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDespesa" name="txtDespesa" placeholder="">
        	</div>
        </div> 
        
               
        <div class="form-group">
        	<label for="txtDescricao" class="col-sm-2 control-label">Descrição: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtDescricao" name="txtDescricao" placeholder="">
        	</div>
        </div> 
        
        <div class="form-group form-inline">
        	<label for="txtValorDespesa" class="col-sm-2 control-label">Valor: <span>*</span> </label>
        	<div class="input-group">
        		<!--<input type="text" class="form-control" id="txtValorBruto" name="txtValorBruto" placeholder="Valor Bruto">-->
                <span class="input-group-addon">R$</span>
 				<input type="text" class="form-control money" id="txtValorDespesa" name="txtValorDespesa" placeholder="000,00">
        	</div>           
        </div> 
        
        <div class="input-group">
  
</div>       
        
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="submit" class="btn btn-primary" value="Inserir" name="btnInserirDespesa" />
      </div>
      
        </form>
          
          
          
          
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
