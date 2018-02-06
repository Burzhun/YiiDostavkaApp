<?php 
if(isset($_FILES['html_file'])){
	$data=file_get_contents($_FILES['html_file']['tmp_name']);
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8">' . $data);
	$table=$doc->getElementsByTagName('table')->item(1);
	$values=array();
	$t=$table->getElementsByTagName('tr');
	$n=$t->length;
	$tr=$t->item(0);
	while($tr!==null){
		$tr = $t->item(0);

		if ($tr === NULL) {
			break;
		}
		$td=$tr->getElementsByTagName('td');
		if($td->item($td->length-1)->hasAttribute('class')){
			$values[]=array($td->item(1)->textContent,$td->item($td->length-1)->textContent);
		}
		$first = $t->item(0);
		$first->parentNode->removeChild($first);

		//
		//  Get the next table to parse
		//

		$tr = $t->item(0);
	}
}?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <div id="chart_div"></div>
<script>
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBackgroundColor);

function drawBackgroundColor() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'X');
      data.addColumn('number', 'Баланс');

      data.addRows([
		<?php foreach($values as $value){?>
			[new Date('<?=$value[0];?>'),<?=$value[1]?>],
		<?php }?>
		
      ]);

      var options = {
        hAxis: {
          title: 'Time'
        },
        vAxis: {
          title: 'Popularity'
        },
        backgroundColor: '#f1f8e9'
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>