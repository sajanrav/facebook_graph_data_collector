<!--This file makes a SQL query to the AWS RDS instance 
holding the Facebook likes count for Dunkin Donuts 
and displays the result in a chart. -->

<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<?php
$row=array();	
$num_results = 0;
$result_date=array();
$result_likes=array();
?>
</head>

<body>
<?php
//Setting the values for the SQL connection, the user-name, password and the database
$link = mysqli_connect("#####","#####","##########","fb_scrap_data");

//Initializing the variable holding the SQL query
$sql = "select date(max(a.time)) as date, a.likes_count as likes_count from (select * from dunkin_data where hour(time) = 23 and minute(time) = 59) as a group by day(a.time);";

$result = mysqli_query($link,$sql);

if ($result == NULL) {
    echo 'Problem with query!';
}

mysqli_close($link);
?>

<!--Java script code for creating graphs. The 
The graph library could be found on www.highcharts.com -->
<script>
$(document).ready(function() { 
    var chart1 = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
        },
        title: {
            text: 'Daily Like Counts for Dunkin Donuts'
        },
	subtitle: {
            text: 'Source: Facebook'
        },
        xAxis: {
	    type: 'datetime',
	    labels: {
  	          formatter: function(){
			return Highcharts.dateFormat('%m-%d-%y', this.value);
			}
		}
        },
        yAxis: {
            title: {
                text: 'Likes'
            }
        },
        //The series data is populated from the results of the SQL 
        //query. In this case, the data is the number of likes. 
        //The date for the beginning of display of chart data 
        //is 03-01-2013 (mm-dd-yyyy). The point interval value
        //has been set to one day which implies that one data-
        //point corresponds to one day. 
        series: [{
            name: 'Facebook Daily Likes',
            data: [<?php $num_results=mysqli_num_rows($result);
			 for($i=0;$i<$num_results;$i++)
				{$row = mysqli_fetch_array($result); 
				 echo $row['likes_count'].","; 
				} 
	          ?>],
            pointStart: Date.UTC(2013,2,1),
	    pointInterval: 24*3600*1000
        }]
    });
});

</script>
<div id="container" style="margin-left:20%; width:60%; height:500px;"> 
</div>
</body>
</html>
