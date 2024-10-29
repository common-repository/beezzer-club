<?php
/*
Plugin Beezzer Club - Template para ticket
*/
?>
<script>
</script>
<? $qs = $this->beezzer_clean_querystring(); ?>
<div id="beezzer_plugin">
	<h2 id="beezzer_title"><?=$bz['ticket']['title'];?></h2>
	<div id="beezzer_stats"><?=$bz['ticket']['num_replies'];?> <? $this->__('respostas'); ?> | <?=$bz['ticket']['num_shows'];?> <? $this->__('acessos'); ?></div>
	<br />
	<?php foreach($bz['replies'] as $r): ?>
	<table class="beezzer_resposta">
		<td class="beezzer_autor" valign="top">
			<center>
				<div class="beezzer_autor_dentro">
					<?php if ($r['reply']['user']['url'] != ''): ?>
						<a href="<?=$r['reply']['user']['url'];?>">
						<?php endif; ?>
							<img src="<?=$r['reply']['user']['profile_image_url'];?>" class="beezzer_autor_foto">
							<br/>
							<?=$r['reply']['user']['name'];?> <? if ($r['reply']['user']['lastname']): echo substr($r['reply']['user']['lastname'],0,1), '.'; endif; ?> 
						<?php if ($r['reply']['user']['url'] != ''): ?>
						</a>
					<?php endif; ?>
				</div>
			</center>
		</td>
		<td class="beezzer_texto" valign="top">
			<?=nl2br($r['reply']['text']);?>
		</td>
	</table>
	<?php endforeach; ?>
	<?php if ($bz['paging']['pageCount'] > 1): ?>
		<div id="beezzer_paging">
			<?php for($i = 1; $i <= $bz['paging']['pageCount']; $i++): ?>
				<?php if ($i == $bz['paging']['page']) 
					echo '<span>' . $i . '</span>';
				else 
					echo '<span><a href="?' . $qs . '&show_ticket=' . $bz['ticket']['url']. '&page=' . $i . '">'. $i . '</a></span>';
				if ($i != $bz['paging']['pageCount'])
					echo ' | ';
				?> 
				
			<?php endfor; ?>
		</div>
	<?php endif; ?>
	<br />
	<input type="button" value="<? $this->__('Enviar nova resposta'); ?>" onclick="javascript:displayBeezzerForm();">
	
	<form class="beezzer_form" action="?<?= $qs; ?>&add_reply" method="POST" id="beezzer_form" style="display:none;">
		<h2 style="margin: 0"><? $this->__('Responda'); ?></h2>
	
		<br />
		<? $this->__('Mensagem:'); ?><br />
		<div class="input required">
			<textarea name="text" cols="30" rows="6" id="resposta-textarea" ></textarea>
		</div>
		<input type="hidden" name="ticket_id" value="<?=$bz['ticket']['id'];?>" id="RespostaTicketId" />            	
		
	    <br />
	    <? $this->__('Nome:'); ?><br />
	    <div class="input required">
	    	<input name="user_name" type="text" maxlength="50" value="" id="RespostaUsuarioNome" />
	    </div>                
	    <br />
	    <? $this->__('E-mail:'); ?> <span class="claro"><? $this->__('(opcional)'); ?></span><br />       
	    <div class="input required">
	    	<input name="user_email" type="text" maxlength="100" value="" id="RespostaUsuarioEmail" />
	    </div>
	    <br />                					                
		<div class="submit">
			<input type="submit" id="botao-resposta-add" value="<? $this->__('Responder'); ?>" />
		</div>
	</form>	
</div>