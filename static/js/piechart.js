/****************************************************
 *													*
 *			A Pie Chart	of my favourite bars		*
 *													*
 ***************************************************/

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
	var data = new google.visualization.DataTable();
	
	var data = google.visualization.arrayToDataTable([
	          ['Polaridad',		'Resultado'],
	          ['Positivos',		positivos],
	          ['Negativos',		negativos],
	          ['Neutrales',  	neutrales]
	        ]);

	var options = {
		width: 220, height: 200,
		chartArea: {left:10,top:20,width:'80%',height:'80%'},
		legend:{position:"none"},
		colors:['#1d8b0f','#b0142a','#AAAAAA']
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}
