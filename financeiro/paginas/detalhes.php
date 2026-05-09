<?php 

	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

	$cadastro->DetalhesServico($id,$typeData);
	$cadastro->RetornaDadosCheque($id);

	

	$nameButtonSolucao = ($cadastro->dadosCheque['count'] > 0) ? 'btnSolucaoCompensadoEdit' : 'btnSolucaoCompensadoInsert';
	//$idUsuarioAtual = (strlen($cadastro->dadosCheque['']))

	if(isset($_POST['btnDepositado']))
	{
		$cadastro->MarcarDepositado($id);
	}
	elseif(isset($_POST['btnRecebido']))
	{
		$cadastro->MarcarRecebido($id);
	}
	elseif(isset($_POST['btnNCompensado']))
	{
		$cadastro->MarcarNaoCompensado($id);
	}
	elseif(isset($_POST['btnSolucaoCompensadoInsert']))
	{
		$cadastro->CadastrarSolucao($id);
	}
	elseif(isset($_POST['btnSolucaoCompensadoEdit']))
	{
		$cadastro->EditarSolucao($id);
	}
	
	#echo '<pre>';
	#print_r($cadastro->dadosCheque);
	#echo '</pre>';
	

?>
<div class="main">
<div style="width:100%; text-align:right; border:0px solid #000;">
<ul class="nav nav-pills" style="float:right; margin:auto;  text-align:right;">
<li role="presentation"><a href="javascript:window.history.go(-1)"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Voltar</a></li>
<li role="presentation"><a href="?s=editarServico&id=<?php echo $id; ?>"><span class="glyphicon glyphicon-edit"></span>&nbsp;Editar</a></li>
</ul>
</div>
	
		<h1 class="page-header">Detalhes do Serviço</h1>
		<br />
		<h4 class="page-header" style="border:0px;">
			<span class="glyphicon glyphicon-chevron-right"></span> <span style="color:#f00;"><?php echo $cadastro->dadosServico['dataServico']; ?></span> - <?php echo $cadastro->dadosServico['nome_paciente']; ?>
		</h4>
	
	
	
	<div class="detalhes" style="min-width:800px;">
	
		
		
		<!-- Cliente -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Cliente: </span> 
			</div>			
			<div class="infoDetalhe">
				<?php echo $cadastro->dadosServico['nome_cliente']; ?>	
			</div>
		</div>			
			
		<!-- Atendimento -->
		<div style="width:100%; float:left;">			
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Atendimento: </span> 
			</div>
			<div class="infoDetalhe">
				<?php echo $cadastro->dadosServico['nome_usuario']; ?>	
			</div>
		</div>
			
		<!-- Cirurgia -->	
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Cirurgia: </span> 
			</div>	
			<div class="infoDetalhe">
				<?php echo $cadastro->dadosServico['cirurgia']; ?>	
			</div>
		</div>
			
		<!-- Valor Bruto -->	
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Valor Bruto: </span> 
			</div>

			<div class="infoDetalhe">
				<?php echo $cadastro->converteValorSite($cadastro->dadosServico['valor_bruto']); ?>	
			</div>
		</div>
		
		<!-- Desconto -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Desconto: </span> 
			</div>

			<div class="infoDetalhe">
				<?php echo $cadastro->converteValorSite($cadastro->dadosServico['valor_desconto']); ?>
			</div>
		</div>
		
		<!-- Desconto - Forma de Pagamento -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe">Forma de Pagamento: </span> 
			</div>

			<div class="infoDetalhe" style="width:15%;">
				<?php echo $cadastro->dadosServico['tipoPagamento']; ?>
			</div>
			
			<div class="tituloDetalhe" style="width:15%; margin-left:50px;">
				<span class="labelDetalhe">Descontos: </span> 
			</div>
			
			<div class="infoDetalhe" style="width:37%">
				<?php echo $cadastro->converteValorSite($cadastro->dadosServico['valor_desconto_pagamento']); ?> (<?php echo $cadastro->dadosServico['p_desconto'] . '%'; ?>)
			</div>
		</div>

		<!-- Valor Final -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Valor Final: </span> 
			</div>

			<div class="infoDetalhe" style="background:#f1f1f1; font-weight:bold;">
				<?php echo $cadastro->converteValorSite($cadastro->dadosServico['valor_final']); ?>	 
				<!-- <span class="label label-success" style="font-size:12px; margin-left:30px;" title="Pagamento Recebido"><span class="glyphicon glyphicon-ok"></span></span> -->
				<!-- <span class="label label-danger" style="font-size:12px; margin-left:30px;" title="Pagamento Pendente"><span class="glyphicon glyphicon-remove"></span></span> -->
			</div>
		</div>
		
		<!-- Recebido -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Recebido: </span> 
			</div>

			<div class="infoDetalhe" style="width:15%;">
				<?php echo $cadastro->dadosServico['recebidoCheck']; ?>	
			</div>
			
			<div class="tituloDetalhe" style="width:15%; margin-left:50px;">
				<span class="labelDetalhe">Recebido Dia: </span> 
			</div>
			
			<div class="infoDetalhe" style="width:auto;">
				<?php echo $cadastro->dadosServico['data_recebido']; ?>
			</div>
		</div>
		
		<!-- Nota Fiscal -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe"> Gratuidade: </span> 
			</div>

			<div class="infoDetalhe">
				<?php echo $cadastro->dadosServico['nota']; ?>
			</div>
		</div>
		
		<!-- Observações -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe">Observações: </span> 
			</div>

			<div class="infoDetalhe">
				<?php echo $cadastro->dadosServico['observacoes']; ?>
			</div>
		</div>
		
		<?php
			if(
				($cadastro->dadosServico['tipoPagamento'] == 'DINHEIRO') || 
				($cadastro->dadosServico['tipoPagamento'] == 'CHEQUE') || 
				($cadastro->dadosServico['tipoPagamento'] == 'TRANSFERÊNCIA BANCÁRIA')
				)
			{			
		?>
		
		<!-- Previsão de Depósito -->
		<div style="width:100%; float:left;">
			<div class="tituloDetalhe">
				<span class="labelDetalhe">Previsão de Depósito: </span> 
			</div>

			<div class="infoDetalhe" style="width:15%;">
				até <?php echo $cadastro->dadosServico['dataPrevisao']; ?>
			</div>
			
			<div class="tituloDetalhe" style="width:15%; margin-left:50px;">
				<span class="labelDetalhe">Depósito Dia: </span> 
			</div>
			
			<div class="infoDetalhe" style="width:auto;">
				<?php echo $cadastro->dadosServico['dataDeposito']; ?>
			</div>
			
		</div>
		
		<?php } ?>
		
		<form name="frmEdit" action="?s=detalhes&id=<?php echo $id; ?>" method="post">
		
		<?php
			if($cadastro->dadosServico['recebido'] == 'N')
			{
				echo '<div style="width:100%; float:left; margin-top:20px;">';
				echo '<div class="tituloDetalhe">Data Recebimento:</div>';
				echo '<div class="infoDetalhe" style="padding:0;width:10%;">';				
				
				echo '<div class="col-sm-2 padInput" style="margin:0; width:100%;">
								<input type="'.$typeData.'" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataRecebimento" name="txtDataRecebimento" placeholder="" />
								</div>';
				echo '</div>';
				echo '<div class="tituloDetalhe" style="width:10%;">Previsão Depósito:</div>';
				echo '<div class="infoDetalhe" style="padding:0;width:10%;">';
				
				echo '<div class="col-sm-2 padInput" style="margin:0; width:100%;">
								<input type="'.$typeData.'" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataPrevisao" name="txtDataPrevisao" placeholder="" />
								</div>
								<button type="submit" name="btnRecebido" class="btn btn-default ">
									<span class="glyphicon glyphicon-ok"></span>
									&nbsp;Marcar como recebido
								</button>';
			
				echo '</div>';
				echo '</div>';
			}		
		
		if(
			($cadastro->dadosServico['recebido'] == 'S') &&
			($cadastro->dadosServico['depositado'] == 'N') &&
			(($cadastro->dadosServico['tipoPagamento'] == 'DINHEIRO') || 
				($cadastro->dadosServico['tipoPagamento'] == 'CHEQUE') || 
				($cadastro->dadosServico['tipoPagamento'] == 'TRANSFERÊNCIA BANCÁRIA')))
		{
			echo '<div style="width:100%; float:left; margin-top:20px;">';
			echo '<div class="tituloDetalhe">Depósito:</div>';
			echo '<div class="infoDetalhe" style="padding:0;">';
			
			if(
				($cadastro->dadosServico['tipoPagamento'] == 'DINHEIRO') || 
				($cadastro->dadosServico['tipoPagamento'] == 'CHEQUE') || 
				($cadastro->dadosServico['tipoPagamento'] == 'TRANSFERÊNCIA BANCÁRIA')
			)
			{
				echo $cadastro->dadosServico['depositadoButton'];
			}	 
				
			echo '</div>';
			echo '</div>';
		}
	?>
		
		<div style="width:100%; float:left; margin-top:20px;">
			<div class="tituloDetalhe" style="background:#fff;">
					
			</div>
			
			<div class="infoDetalhe" style="padding:0;">
				<?php 
					if(($cadastro->dadosServico['depositado'] == 'S') && (strlen($cadastro->dadosCheque['data_solucao']) < 3))
					{  
						echo $cadastro->dadosServico['compensadoButton'];		
					} 
				?>
			
			</div>
		</div>
		
		</form>
		
		<?php if($cadastro->dadosServico['compensado'] != 'S') { ?>
		
		<div style="width:100%; float:left; margin-top:20px;">
		<!-- Formulário de Não Compensados -->
			
			<form class="form-horizontal" role="form" action="?s=detalhes&id=<?php echo $id; ?>" method="post">
        
				<div class="form-group">
					<label for="txtMotivo" class="col-sm-3 control-label">Motivo: </label>
					<div class="col-sm-8 padInput">
					<input type="text" class="form-control" id="txtMotivo" name="txtMotivo" placeholder="" value="<?php echo $cadastro->dadosCheque['motivo']; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="txtBanco" class="col-sm-3 control-label">Banco: </label>
					<div class="col-sm-3 padInput">
					<input type="text" class="form-control" id="txtBanco" name="txtBanco" placeholder="" value="<?php echo $cadastro->dadosCheque['banco']; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="txtCheque" class="col-sm-3 control-label">Info Cheque / Conta: </label>
					<div class="col-sm-3 padInput">
					<input type="text" class="form-control" id="txtCheque" name="txtCheque" placeholder="" value="<?php echo $cadastro->dadosCheque['n_cheque']; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="txtSolucao" class="col-sm-3 control-label">Solução: </label>
					<div class="col-sm-8 padInput">
					<input type="text" class="form-control" id="txtSolucao" name="txtSolucao" placeholder="" value="<?php echo $cadastro->dadosCheque['solucao']; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="txtSolucao" class="col-sm-3 control-label">Data da Solução: </label>
					<div class="col-sm-2 padInput">
					<input type="<?php echo $typeData; ?>" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataSolucao" name="txtDataSolucao" value="<?php echo $cadastro->dadosCheque['data_solucao']; ?>">
					</div>
				</div>
				
				<div class="form-group form-inline">
            	<label for="txtRespSolucao" class="col-sm-3 control-label">Resp. Solução: </label>
            	<div class="col-sm-8 padInput">
            	<select class="form-control bootstrap-select" id="txtRespSolucao" name="txtRespSolucao" placeholder="">
                	<?php if($cadastro->dadosCheque['resp_usuario'] < 1) { ?>
					<option value="">Selecione...</option>
					<?php
						$cadastro->RetornaUsuariosEdit('0');
					}else{ ?>
					<option value="<?php echo $cadastro->dadosCheque['resp_usuario']; ?>"><?php echo $cadastro->dadosCheque['nome_usuario']; ?></option>
					<?php } ?>
            		<?php $cadastro->RetornaUsuariosEdit($cadastro->dadosCheque['resp_usuario']); ?>
            	</select>
            	</div>
            </div>
				
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Inserir" name="<?php echo $nameButtonSolucao; ?>" />
				</div>
			
			</form>
			
		
		</div>
		<?php } ?>
		
		
	</div>  
</div>
