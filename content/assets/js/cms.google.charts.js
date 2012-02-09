CMS['charts'] = {}

CMS['charts']['initialise'] = function()
{
		google.load
		(
			'visualization',
			'1',
			{
				packages: ['geochart', 'gauge']
			}
		);
	
		google.setOnLoadCallback(drawVisualization);
		//google.setOnLoadCallback(drawGuage);
}

function drawVisualization()
{
	var data = new google.visualization.DataTable();
	data.addRows(6);
	data.addColumn('string', 'Country');
	data.addColumn('number', 'Popularity');
	data.setValue(0, 0, 'Germany');
	data.setValue(0, 1, 200);
	data.setValue(1, 0, 'United States');
	data.setValue(1, 1, 300);
	data.setValue(2, 0, 'Brazil');
	data.setValue(2, 1, 400);
	data.setValue(3, 0, 'Canada');
	data.setValue(3, 1, 500);
	data.setValue(4, 0, 'France');
	data.setValue(4, 1, 600);
	data.setValue(5, 0, 'RU');
	data.setValue(5, 1, 700);

	var geochart = new google.visualization.GeoChart
	(
		document.getElementById('visualization'));
		geochart.draw(data, {width: 940, height: 640}
	);
}

function drawGuage()
{
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Label');
	data.addColumn('number', 'Value');
	data.addRows(3);
	data.setValue(0, 0, 'Memory');
	data.setValue(0, 1, 80);
	data.setValue(1, 0, 'CPU');
	data.setValue(1, 1, 55);
	data.setValue(2, 0, 'Network');
	data.setValue(2, 1, 68);
	
	var chart = new google.visualization.Gauge(document.getElementById('guage'));
	var options = {width: 400, height: 120, redFrom: 90, redTo: 100, yellowFrom:75, yellowTo: 90, minorTicks: 5};
	chart.draw(data, options);
}

if($('html.page-dashboard').length)
{

}

CMS.charts.initialise();