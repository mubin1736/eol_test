<?php
include("../web/conn/connection.php") ;
$c_id = $_GET['c_id'];

$sql3 = mysql_query("SELECT DATE_FORMAT(date_time,'%h:%m:%s') AS dat2ss, rx, tx FROM realtime_speed 
WHERE date_time BETWEEN DATE_SUB( CURTIME() , INTERVAL 1 MINUTE ) AND CURTIME() AND c_id = '$c_id' GROUP BY date_time ORDER BY id DESC");

$myurl3[]="['Date','Upload (kb)','Download (kb)']";
while($r3=mysql_fetch_assoc($sql3)){
	
	$paydate = $r3['dat2ss'];
	$collections = $r3['rx'];
	$discountss = $r3['tx'];
	$myurl3[]="['".$paydate."',".$collections.",".$discountss."]";
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			<?php echo(implode(",",$myurl3));?>
        ]);

        var options = {
		  title: '..::Realtime Uses Graph::..',
		  fontSize: 9,
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div3'));

        chart.draw(data, options);
      }
 </script>
 <div id="chart_div3" style="text-align: center; height: 150px; width: 98%;float: left;margin-bottom: 5px;margin-top: 2px;"></div>