<?php
/*
Plugin Beezzer Club - Template para clube
*/
?>
<? $qs = $this->beezzer_clean_querystring(); ?>
<div id="beezzer_plugin">
	<h2 id="beezzer_title"><?=$bz['produto']['name'];?></h2>
	<div id="beezzer_stats">
		<?=$bz['produto']['num_tickets'];?> <? $this->__('tickets'); ?> | <?=$bz['produto']['num_members'];?> <? $this->__('membros'); ?>
	</div>
	<div id="join-club-link">
		<a href="#" onclick="return clickJoinClub(true);"><? $this->__('quero ser um membro'); ?></a>
	</div>
	<div id="join-club-form" style="text-align: left; display: none">
		<form action="?<?= $qs; ?>&join_club" method="POST" style="text-align: left">
		    <input onfocus="javascript:focusInputEmail(true);" onblur="javascript:focusInputEmail(false);" name="user_email" type="text" value="Digite seu e-mail" id="ParticiparUsuarioEmail" />&nbsp;	
			<input type="submit" id="participar-botao" value="Enviar" /> <? $this->__('ou'); ?> <a href="#" onclick="return clickJoinClub(false);"><? $this->__('cancelar'); ?></a>
			<input type="hidden" name="produto_id" value="<?=$bz_club;?>" id="produto_id" />
		</form>
	</div>
	<? if (isset($_GET['join_club'])): ?>
		<div id="join-club-aviso">
			<br />
			<div id="join-club-aviso-txt">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td><? $this->__('Uma confirmação foi enviada ao seu e-mail.<br />Acesse-o e clique no link de confirmação.'); ?></td>
						<td width="30"><a href="#" onclick="javascript:document.getElementById('join-club-aviso').style.display = 'none'; return false;"><img src="http://pt.beezzer.com/img/flash.fechar.gif" /></a></td>
					</tr>
				</table>
			</div>
		</div>
	<? endif; ?>
	<br />
	<strong><? $this->__('Alguns Membros'); ?></strong>
	<br />
	
	<?php 
	if ($bz['users']):
		$count = 0;
		foreach($bz['users'] as $u):
		$count++;
		if($count >= 10) break;
	 ?>
	<a href="<?php echo $u['user']['profile_url']; ?>"><img src="<?php echo $u['user']['profile_image_url'] ?>" style="margin:1px" class="beezzer_participantes_foto" title="<?= $u['user']['name'] ?>" /></a>						
	<?php endforeach; ?>
	<br />
	<a href="<?php echo $bz['produto']['url']; ?>"><? $this->__('Ver todos'); ?></a>
	<br />
	<br />
	<?php endif; ?>
	
	<?php if ($bz['tickets']): ?>
		<table id="beezzer_tickets">
		<tr>
			<th><? $this->__('Ticket'); ?></th>
			<th style="text-align:center"><? $this->__('Respostas'); ?></th>
			<th style="text-align:center"><? $this->__('Alteração'); ?></th>
		</tr>
		<?php foreach($bz['tickets'] as $t): ?>
			<tr>
				<td class="beezzer_ticket">
					<a href="?<?= $qs; ?>&show_ticket=<?=$t['ticket']['url'];?>"><?=$t['ticket']['title'];?></a>
					
					<table class="beezzer_autor_ticket">
						<tr>
							<td>
								<? $this->__('Enviado por'); ?>
							</td>
							<td>
								<?php if ($t['ticket']['author']['url'] != ""): ?>
								 <a href="<?=$t['ticket']['author']['url'];?>">
								<?php endif; ?>
									<img src="<?=$t['ticket']['author']['profile_image_url'];?>" class="beezzer_autor_foto">
								<?php if ($t['ticket']['author']['url'] != ""): ?>
								 </a>
							<?php endif; ?>
							</td>
							<td>
							<?php if ($t['ticket']['author']['url'] != ""): ?>
								<a href="<?=$t['ticket']['author']['url'];?>">
								<?php endif; ?>
									<?=$t['ticket']['author']['name'];?> <? if ($t['ticket']['author']['lastname']): echo substr($t['ticket']['author']['lastname'],0,1), '.'; endif; ?> 
								<?php if ($t['ticket']['author']['url'] != ""): ?>
								</a>
							<?php endif; ?>
							</td>
						</tr>
					</table>
					</div>
				</td>
				<td class="beezzer_visualizacoes"><?=$t['ticket']['num_replies'];?>/<?=$t['ticket']['num_shows'];?></td>
				<td class="beezzer_data"><?= date('d-m-Y',strtotime($t['ticket']['updated']));?></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php if ($bz['paging']['pageCount'] > 1): ?>
			<div id="beezzer_paging">
				<?php for($i = 1; $i <= $bz['paging']['pageCount']; $i++): ?>
					<?php if ($i == $bz['paging']['page']) 
						echo '<span>' . $i . '</span>';
					else 
						echo '<span><a href="?' . $qs . '&page=' . $i . '">'. $i . '</a></span>';
					if ($i != $bz['paging']['pageCount'])
						echo ' | ';
					?> 
					
				<?php endfor; ?>
			</div>
		<?php endif; ?>		
	<?php else: ?>
		<? $this->__('Ainda não há tickets.'); ?>	
	<?php endif; ?>
	<br />
	<input type="button" value="<? $this->__('Abrir novo ticket'); ?>" onclick="javascript:displayBeezzerForm();">
	
	<form action="?<?= $qs; ?>&add_ticket" method="POST" id="beezzer_form" style="display:none">
	<h2 style="margin: 0"><? $this->__('Abra um novo ticket'); ?></h2>
	    <? $this->__('Título:'); ?><br />
	    <div class="input"><input name="title" type="text" value="" id="TicketTitulo" /></div><br />
	    
	    <? $this->__('Descrição:'); ?><br />
		<div class="input"><textarea name="text" cols="30" rows="6" id="ticket-resposta-textarea"></textarea></div>
		<br />
		
		<? $this->__('Seu nome:'); ?><br />
	    <div class="input"><input name="user_name" type="text" value="" id="RespostaUsuarioNome" /></div>
	    <br />        
	    
	    <? $this->__('Seu e-mail:'); ?> <span class="claro"><? $this->__('(opcional)'); ?></span>
	    <div class="input"><input name="user_email" type="text" value="" id="RespostaUsuarioEmail" /></div>
		<br />
		
		<? $this->__('Tipo:'); ?>
		<div class="input">
			<select name="type" size="6" id="TicketTipo">
			<option value="dúvida" selected="selected"><? $this->__('dúvida'); ?></option>
			<option value="dica"><? $this->__('dica'); ?></option>
			<option value="diversão"><? $this->__('diversão'); ?></option>
			<option value="sugestão"><? $this->__('sugestão'); ?></option>
			<option value="discussão"><? $this->__('discussão'); ?></option>
			<option value="reclamação"><? $this->__('reclamação'); ?></option>
			</select>
		</div>    
		<br />
		
		<input type="hidden" name="produto_id" value="<?=$bz_club;?>" id="produto_id" />		
		<input type="submit" id="ticket-add-botao" value="<? $this->__('Abrir'); ?>" />
	
	</form>
</div>