<div class="main">	
	<h1 class="page-header">Inserir Serviços</h1>
	
	
    <form class="form-horizontal" role="form" action="index.php" method="post" onsubmit="return validaFormServico();">
        
            <div class="form-group">
            	<label for="txtData" class="col-sm-2 control-label">Data: <span>*</span> </label>
            	<div class="col-sm-2 padInput">
            	<input type="<?php echo $typeData; ?>" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtData" name="txtData" placeholder="">
            	</div>
            </div>
            
            <div class="form-group">
        	<label for="txtCliente" class="col-sm-2 control-label">Cliente (Cirurgião): <span>*</span> </label>
        	<div class="col-sm-2 padInput">
            	<select class="form-control bootstrap-select" id="txtCliente" name="txtCliente" placeholder="">
            		<option value="">Selecione...</option>
            		<?php $cadastro->ListaClientesForm(); ?>
            	</select>
            	</div>
			</div>
        
            <div class="form-group form-inline">
            	<label for="txtAtendimento" class="col-sm-2 control-label">Atendimento: <span>*</span> </label>
            	<div class="col-sm-10 padInput">
            	<select class="form-control bootstrap-select" id="txtAtendimento1" name="txtAtendimento1" placeholder="">
                	<option value="">Selecione...</option>
            		<?php $cadastro->RetornaUsuariosAtendimento(); ?>
            	</select>
				<select class="form-control bootstrap-select" id="txtAtendimento2" name="txtAtendimento2" placeholder="">
                	<option value="">Selecione...</option>
            		<?php $cadastro->RetornaUsuariosAtendimento(); ?>
            	</select>
            	</div>
            </div>         
        
        
        <div class="form-group">
        	<label for="txtPaciente" class="col-sm-2 control-label">Paciente: <span>*</span> </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtPaciente" name="txtPaciente" placeholder="">
        	</div>
        </div> 
        
        <div class="form-group">
        	<label for="txtCirurgia" class="col-sm-2 control-label">Cirurgia: <span>*</span> </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtCirurgia" name="txtCirurgia" placeholder="">
        	</div>
        </div> 
        
        <div class="form-group form-inline" style="z-index: -100;">
        	<label for="txtValorBruto" class="col-sm-2 control-label">Valor Bruto: <span>*</span> </label>
        	<div class="input-group">
        		<!--<input type="text" class="form-control" id="txtValorBruto" name="txtValorBruto" placeholder="Valor Bruto">-->
                <span class="input-group-addon">R$</span>
 				<input type="text" class="form-control money" id="txtValorBruto" name="txtValorBruto" placeholder="000,00" style="z-index: 0;">
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
         
         
         
         
        <!-- 
         <div class="form-group form-inline" style="z-index: 0;">
        	<label for="txtDesconto" class="col-sm-2 control-label">Desconto: </label>
        	<div class="input-group">
                <span class="input-group-addon">R$</span>
                <input type="text" class="form-control money" id="txtDesconto" name="txtDesconto" placeholder="000,00" style="z-index: 0;">
        	</div>
         </div>
        -->
         <div class="form-group form-inline" style="z-index: -100;">
        	<label for="chkRecebido" class="col-sm-2 control-label">Recebido: </label>
        	<div class="input-group col-sm-6">
        		<div class="checkbox">              
                	<input type="checkbox" name="chkRecebido" id="chkRecebido" style="z-index: 0;">
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
        	<label for="txtNota" class="col-sm-2 control-label">Gratuidade: </label>
        	<div class="input-group col-sm-3">
        		<!-- <input type="text" class="form-control" id="txtNota" name="txtNota" placeholder="Nota"> -->
        		<div class="checkbox">              
                	<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="N" />&nbsp;&nbsp;Sim&nbsp;&nbsp;</label>
					<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="S" checked />&nbsp;&nbsp;Não&nbsp;&nbsp;</label>
                </div>
        	</div>
         </div>
         
         <div class="form-group form-inline">
            	<label for="txtPagamento" class="col-sm-2 control-label">Pagamento: <span>*</span> </label>
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
			<div class="col-sm-2 padInput">
			<input type="<?php echo $typeData; ?>" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataPrevisao" name="txtDataPrevisao" placeholder="">
			</div>
		</div>        
        
		
		
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="submit" class="btn btn-primary" value="Inserir" name="btnInserirAnestesia" />
      </div>
      
        </form>
    
	
	
  
</div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
