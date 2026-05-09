<?php 

	if(isset($_POST['btnEditarServico'])) {
		$cadastro->EditarServico();	
	}
	
	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
	
	$cadastro->DetalhesServico($id,$typeData);
	
	/*
	echo '<pre>';
	print_r($cadastro->dadosServico);
	echo '</pre>';
	*/
	
	if($cadastro->dadosServico['recebido'] == 'S') {
		$recebidoCheck = 'checked';	
	}
	else {
		$recebidoCheck = '';	
	}
	
?>
<div class="main">	
	<h1 class="page-header">Inserir Serviços</h1>
	
    <form class="form-horizontal" role="form" action="index.php?s=editarServico&id=<?php echo $id; ?>" method="post">
	
	<input type="hidden" name="txtIdServico" value="<?php echo $cadastro->dadosServico['id']; ?>" />
        
            <div class="form-group">
            	<label for="txtData" class="col-sm-2 control-label">Data: </label>
            	<div class="col-sm-2 padInput">
            	<input type="text" data-provide="datepicker" class="form-control" id="txtData" name="txtData" value="<?php echo $cadastro->dadosServico['dataServico'] ?>">
            	</div>
            </div>
            
            <div class="form-group">
        	<label for="txtCliente" class="col-sm-2 control-label">Cliente (Cirurgião): </label>
        	<div class="col-sm-2 padInput">
            	<select class="form-control bootstrap-select" id="txtCliente" name="txtCliente">
            		<option value="<?php echo $cadastro->dadosServico['idCliente'] ?>"><?php echo $cadastro->dadosServico['nome_cliente']; ?></option>
					
					<?php $cadastro->ListaClientesFormEdit($cadastro->dadosServico['idCliente']); ?>
            		
            	</select>
            	</div>
			</div>
        
            <div class="form-group form-inline">
            	<label for="txtAtendimento1" class="col-sm-2 control-label">Atendimento: </label>
            	<div class="col-sm-10 padInput">
            	<select class="form-control bootstrap-select" id="txtAtendimento1" name="txtAtendimento1" placeholder="">
                	<option value="<?php echo $cadastro->dadosServico['atendimento1'] ?>"><?php  echo $cadastro->dadosServico['nomeAtendimento1'] ?></option>
            		<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosServico['atendimento1']); ?>
					<option value="0"> --- </option>
            	</select>
				
				<select class="form-control bootstrap-select" id="txtAtendimento2" name="txtAtendimento2" placeholder="">
                	<option value="<?php echo $cadastro->dadosServico['atendimento2'] ?>"><?php  echo $cadastro->dadosServico['nomeAtendimento2'] ?></option>
            		<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosServico['atendimento2']); ?>
					<option value="0"> --- </option>
            	</select>
            	</div>
            </div>         
        
        
        <div class="form-group">
        	<label for="txtPaciente" class="col-sm-2 control-label">Paciente: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtPaciente" name="txtPaciente" value="<?php echo $cadastro->dadosServico['nome_paciente'] ?>">
        	</div>
        </div> 
        
        <div class="form-group">
        	<label for="txtCirurgia" class="col-sm-2 control-label">Cirurgia: </label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtCirurgia" name="txtCirurgia" value="<?php echo $cadastro->dadosServico['cirurgia'] ?>">
        	</div>
        </div> 
        
        <div class="form-group form-inline">
        	<label for="txtValorBruto" class="col-sm-2 control-label">Valor Bruto: </label>
        	<div class="input-group">
        		<!--<input type="text" class="form-control" id="txtValorBruto" name="txtValorBruto" placeholder="Valor Bruto">-->
                <span class="input-group-addon">R$</span>
 				<input type="text" class="form-control money" id="txtValorBruto" name="txtValorBruto" value="<?php echo $cadastro->dadosServico['valor_bruto'] ?>">
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
                <input type="text" class="form-control money" id="txtDesconto" name="txtDesconto" value="<?php echo $cadastro->dadosServico['valor_desconto'] ?>">
        	</div>
         </div>
         
         <div class="form-group form-inline">
        	<label for="chkRecebido" class="col-sm-2 control-label">Recebido: </label>
        	<div class="input-group col-sm-6">
        		<div class="checkbox">              
                	<input type="checkbox" name="chkRecebido" id="chkRecebido" <?php echo $recebidoCheck; ?>>
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
                	<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="N" <?php echo $cadastro->dadosServico['notaSim']; ?> />&nbsp;&nbsp;Sim&nbsp;&nbsp;</label>
					<label for="chkNota"></label><input type="radio" name="chkNota" id="chkNota" value="S" <?php echo $cadastro->dadosServico['notaNao']; ?> />&nbsp;&nbsp;Não&nbsp;&nbsp;</label>
                </div>
        	</div>
         </div>
         
         <div class="form-group form-inline">
            	<label for="txtPagamento" class="col-sm-2 control-label">Pagamento: </label>
            	<div class="col-sm-4 padInput">
            	<select class="form-control bootstrap-select" id="txtPagamento" name="txtPagamento" placeholder="">
					<option value="<?php echo $cadastro->dadosServico['idPagamento'] ?>"><?php echo $cadastro->dadosServico['tipoPagamento']; ?></option>
            		<?php $cadastro->ListaFormaPagamentoFormEdit($cadastro->dadosServico['idPagamento']); ?>
            	</select>
            	</div>
            </div>
            
        <div class="form-group">
        	<label for="txtObs" class="col-sm-2 control-label">Observações:&nbsp;&nbsp;</label>
        	<div class="col-sm-8 padInput">
        		<input type="text" class="form-control" id="txtObs" name="txtObs" value="<?php echo $cadastro->dadosServico['observacoes'] ?>">
        	</div>           
        </div>
		
		<div class="form-group">
			<label for="txtDataPrevisao" class="col-sm-2 control-label">Previsão Depósito: </label>
			<div class="col-sm-2 padInput">
			<input type="text" data-provide="datepicker" class="form-control" id="txtDataPrevisao" name="txtDataPrevisao" value="<?php echo $cadastro->dadosServico['dataPrevisao'] ?>">
			</div>
		</div>  
		
        <div class="form-group">
			<label for="txtDataDeposito" class="col-sm-2 control-label">Data de Depósito: </label>
			<div class="col-sm-2 padInput">
			<input type="text" data-provide="datepicker" class="form-control" id="txtDataDeposito" name="txtDataDeposito" value="<?php echo $cadastro->dadosServico['dataDeposito'] ?>">
			</div>
		</div>        
        
        <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="Editar" name="btnEditarServico" />
      </div>
      
        </form>
    
	
	
  
</div>
