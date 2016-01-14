<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 14.01.2016
 * Time: 11:40
 */
require_once(__DIR__.'/../functions.php');
require_once(__DIR__.'/../user_manage_class.php');
$user_manage = new user_manage($connection);
$temp_array = $user_manage->gettemp1();

?>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Aeg', 'Temp1'],
                <?php
    for($i = 0; $i < count($temp_array); $i++) {
$my_sql_date = $temp_array[$i]->time;;
$date_time = new DateTime($my_sql_date);

        echo "[new Date(";
        echo $date_time->format('Y');
        echo ",";
        echo intval($date_time->format('m'))-1;
        echo ",";
        echo $date_time->format('d');
        echo ",";
        echo $date_time->format('H');
        echo ",";
        echo $date_time->format('i');
        echo ",";
        echo $date_time->format('s');

        echo "),";
        echo $temp_array[$i]->temp;
        echo"],".PHP_EOL;
    } ?>

            ]);
            var options = {
                title: 'Temeperatuur1',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>
</html>
