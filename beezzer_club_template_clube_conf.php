<?php
/*
Plugin Beezzer Club - Configuração
*/
?>
<div class="wrap">
	<h2><?php $this->__('Configurações do Beezzer Club'); ?></h2>
	
	<?php if ($errorDataNotComplete): ?>
		<div class="beezzer_div beezzer_div_error">
			<h3><?php $this->__('Erro: Você deve preencher os 3 campos abaixo'); ?></h3>
		</div>
	<?php elseif ($errorAuth): ?>
		<div class="beezzer_div beezzer_div_error">
			<h3><?php $this->__('Erro: Não foi possível realizar a autenticação. Por favor confira o e-mail e senha inseridos'); ?></h3>
		</div>
	<?php elseif ($errorClubNotExist): ?>
		<div class="beezzer_div beezzer_div_error">
			<h3><?php $this->__('Erro: Clube não encontrado. Verifique se a URL inserida está correta'); ?></h3>
		</div>
	<?php else: ?>
		<div class="beezzer_div beezzer_div_ok">
			<h3><?php $this->__('Plugin configurado corretamente'); ?></h3>
		</div>
	<?php endif; ?>
	
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php $this->__('URL Clube'); ?></th>
				<td>http://pt.beezzer.com/<input type="text" name="BeezzerClub_options[beezzer_club]" value="<?php echo $this->adminOptions['beezzer_club']; ?>" /></td>
			</tr>
	
			<tr valign="top">
				<th scope="row"><?php $this->__('E-mail'); ?></th>
				<td><input type="text" name="BeezzerClub_options[beezzer_email]" value="<?php echo $this->adminOptions['beezzer_email']; ?>" /></td>
			</tr>
		
			<tr valign="top">
				<th scope="row"><?php $this->__('Senha'); ?></th>
				<td><input type="password" name="BeezzerClub_options[beezzer_pass]" value="<?php echo $this->adminOptions['beezzer_pass']; ?>" /></td>
			</tr>
		</table>
		
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="BeezzerClub_options" />
		
		<p class="submit">
			<input type="submit" name="Submit" value="<?php $this->__('Salvar'); ?>" />
		</p>
	</form>
</div>
