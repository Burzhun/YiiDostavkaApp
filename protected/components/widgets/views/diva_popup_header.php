<header>
	<ol>
		<li class="main">
			<i style="background-image: url(<?=htmlspecialchars($icons[$this->icon]['url'])?>)"></i><?=htmlspecialchars($this->title)?>
		</li>
		
		<? foreach($this->params as $param){ ?>
			<li>
				<dl>
					<dt><?=htmlspecialchars($param['label'])?></dt>
					<dd><?=htmlspecialchars($param['value'])?></dd>
				</dl>
			</li>
		<? } ?>
	</ol>
	
</header>