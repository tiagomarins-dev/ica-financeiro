 <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myLargeModalLabel">Inserir Serviço</h4>
        </div>
        <div class="modal-body">
          
        <form class="form-horizontal" role="form" action="index.php" method="post">
        
            <div class="form-group">
            	<label for="txtData" class="col-sm-2 control-label">Data: </label>
            	<div class="col-sm-3 padInput">
            	<input type="date" data-provide="datepicker" class="form-control" id="txtData" name="txtData" placeholder="">
            	</div>
            </div>
            
            <div class="form-group">
        	<label for="txtCliente" class="col-sm-2 control-label">Cliente (Cirurgião): </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtCliente" name="txtCliente" placeholder="">
        	</div>
			</div>
        
            <div class="form-group form-inline">
            	<label for="txtAtendimento" class="col-sm-2 control-label">Atendimento: </label>
            	<div class="col-sm-10 padInput">
            	<select class="form-control bootstrap-select" id="txtAtendimento" name="txtAtendimento" placeholder="">
                	<option value="">Selecione...</option>
            		<option value="01">Edu e Pietro</option>
            		<option value="02">Jaime</option>
            		<option value="03">Antônio</option>
            		<option value="04">Sérgio</option>
            		<option value="05">Edu e Jaime</option>
            		<option value="06">Eduardo</option>
            	</select>
				<select class="form-control bootstrap-select" id="txtAtendimento" name="txtAtendimento" placeholder="">
                	<option value="">Selecione...</option>
            		<option value="01">Edu e Pietro</option>
            		<option value="02">Jaime</option>
            		<option value="03">Antônio</option>
            		<option value="04">Sérgio</option>
            		<option value="05">Edu e Jaime</option>
            		<option value="06">Eduardo</option>
            	</select>
            	</div>
            </div>         
        
        
        <div class="form-group">
        	<label for="txtPaciente" class="col-sm-2 control-label">Paciente: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtPaciente" name="txtPaciente" placeholder="">
        	</div>
        </div> 
        
        <div class="form-group">
        	<label for="txtCirurgia" class="col-sm-2 control-label">Cirurgia: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtCirurgia" name="txtCirurgia" placeholder="">
        	</div>
        </div> 
        
        <div class="form-group form-inline">
        	<label for="txtValorBruto" class="col-sm-2 control-label">Valor Bruto: </label>
        	<div class="input-group">
        		<!--<input type="text" class="form-control" id="txtValorBruto" name="txtValorBruto" placeholder="Valor Bruto">-->
                <span class="input-group-addon">R$</span>
 				<input type="text" class="form-control money" id="txtValorBruto" name="txtValorBruto" placeholder="000,00">
        	</div>           
        </div> 
        
        <div class="input-group">
  
</div>
       <!--  
        <div class="form-group form-inline">
        	<label for="txtValorReal" class="col-sm-2 control-label">Valor Real: </label>
        	<div class="input-group">
        		<span class="input-group-addon">R$</span>
                <input type="text" class="form-control" id="txtValorReal" name="txtValorReal" placeholder="000,00">
        	</div>
         </div>
        -->
         <div class="form-group form-inline">
        	<label for="txtDesconto" class="col-sm-2 control-label">Desconto: </label>
        	<div class="input-group">
        		<!-- <input type="text" class="form-control" id="txtDesconto" name="txtDesconto" placeholder="Desconto"> -->
                <span class="input-group-addon">R$</span>
                <input type="text" class="form-control money" id="txtDesconto" name="txtDesconto" placeholder="000,00">
        	</div>
         </div>
         
         <div class="form-group form-inline">
        	<label for="chkRecebido" class="col-sm-2 control-label">Recebido: </label>
        	<div class="input-group col-sm-6">
        		<div class="checkbox">              
                	<input type="checkbox" name="chkRecebido" id="chkRecebido">
                </div>
        	</div>
         </div>
         
		 <!--
         <div class="form-group form-inline">
        	<label for="chkDepositado" class="col-sm-2 control-label">Depositado: </label>
        	<div class="input-group col-sm-6">
        		<div class="checkbox">              
                	<input type="checkbox" name="chkDepositado" id="chkDepositado">
                </div>
        	</div>
         </div>
         -->
		 
         <div class="form-group form-inline">
        	<label for="txtNota" class="col-sm-2 control-label">Nota: </label>
        	<div class="input-group col-sm-3">
        		<!-- <input type="text" class="form-control" id="txtNota" name="txtNota" placeholder="Nota"> -->
        		<div class="checkbox">              
                	<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="S" checked /> Sim </label>
                	<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="N" /> Não </label>
                </div>
        	</div>
         </div>
         
         <div class="form-group form-inline">
            	<label for="txtPagamento" class="col-sm-2 control-label">Pagamento: </label>
            	<div class="col-sm-4 padInput">
            	<select class="form-control bootstrap-select" id="txtPagamento" name="txtPagamento" placeholder="">
            		<option value="">Selecione...</option>
            		<?php $cadastro->ListaFormaPagamentoForm(); ?>
            	</select>
            	</div>
            </div>
            
        <div class="form-group">
        	<label for="txtObs" class="col-sm-2 control-label">Observações:&nbsp;&nbsp;</label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtObs" name="txtObs" placeholder="">
        	</div>           
        </div>
		
		<div class="form-group">
			<label for="txtDataPrevisao" class="col-sm-2 control-label">Previsão Depósito: </label>
			<div class="col-sm-3 padInput">
			<input type="date" data-provide="datepicker" class="form-control" id="txtDataPrevisao" name="txtDataPrevisao" placeholder="">
			</div>
		</div>
        
        
        
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="submit" class="btn btn-primary" value="Inserir" name="btnInserirAnestesia" />
      </div>
      
        </form>
          
          
          
          
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
