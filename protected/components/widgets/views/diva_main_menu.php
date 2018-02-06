<ol class="main-menu">
	
	<li class="site-logo">
		<a class="link" href="/"><span><?=htmlspecialchars($this->config['site_logo']['title'])?></span></a>
	</li>
	
	<? foreach($this->config['items'] as $itemId => $item){ ?>
		
		<? if ($item == 'separator'){ ?>
			<li class="separator"></li>
		<? } else { 
			$isCurrent = $this->controller->currentPart == $itemId;
		?>
			<li class="
					<? if ($isCurrent){ ?>current<? } ?>
			">
				<? if (@$item['href'] and !$isCurrent){ ?><a class="link" href="<?=htmlspecialchars($item['href'])?>"><? } ?>
					<?=htmlspecialchars($item['title'])?>
					<? if ($isCurrent){ ?>→<? } ?>
				<? if (@$item['href']){ ?></a><? } ?>
			</li>
		<? } ?>
	
	<? } ?>
	
</ol>