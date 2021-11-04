let enableBack = false;
let colors = ['blue', 'black', 'pink', 'red', 'yellow', 'gold', 'green', 'purple', 'brown', 'grey'];
function plotTopic(fileName){

	//let fileName = 'data/topic/topics.csv';
	let labels = [];
	let data0 = [];
	let data1 = [];
	let data2 = [];
	let data3 = [];
	let data4 = [];
	let data5 = [];
	let data6 = [];
	let data7 = [];
	let data8 = [];
	let data9 = [];

	d3.csv(fileName).then(function(response){
		for (var i = 0; i < response.length; i++){
			labels.push(response[i].dateTime);
			data0.push(response[i].top0);
			data1.push(response[i].top1);
			data2.push(response[i].top2);
			data3.push(response[i].top3);
			data4.push(response[i].top4);
			data5.push(response[i].top5);
			data6.push(response[i].top6);
			data7.push(response[i].top7);
			data8.push(response[i].top8);
			data9.push(response[i].top9);
		}
	});

	let timeSeries = document.getElementById("timeSeries").getContext('2d');
	let option = {
		type: 'line',
		data: {
			labels: labels,
			datasets: [
				{
	            	data: data0,
	            	label: 'topic 1',
	            	borderColor: 'blue',
	            	lineTension: 0,
	            	fill: false
	        	},
	        	{
	            	data: data1,
	            	label: 'topic 2',
	            	borderColor: 'black',
	            	lineTension: 0,
	            	fill: false
	        	},
	        	{
	            	data: data2,
	            	label: 'topic 3',
	            	borderColor: 'pink',
	            	lineTension: 0,
	            	fill: false
 		       	},
    	    	{
    	        	data: data3,
    	        	label: 'topic 4',
    	        	borderColor: 'red',
    	        	lineTension: 0,
    	        	fill: false
    	    	},
    	    	{
    	        	data: data4,
    	        	label: 'topic 5',
    	        	borderColor: 'yellow',
    	        	lineTension: 0,
    	        	fill: false
    	    	},
        		{
        	    	data: data5,
        	    	label: 'topic 6',
        	    	borderColor: 'orange',
        	    	lineTension: 0,
        	    	fill: false
        		},
        		{
        	    	data: data6,
        	    	label: 'topic 7',
        	    	borderColor: 'green',
        	    	lineTension: 0,
        	    	fill: false
        		},
        		{
        	    	data: data7,
        	    	label: 'topic 8',
        	    	borderColor: 'purple',
        	    	lineTension: 0,
        	    	fill: false
        		},
        		{
        	    	data: data8,
        	    	label: 'topic 9',
        	    	borderColor: 'brown',
        	    	lineTension: 0,
        	    	fill: false
        		},
        		{
        	    	data: data9,
        	    	label: 'topic 10',
        	    	borderColor: 'grey',
        	    	lineTension: 0,
        	    	fill: false
        		}
			]
		},
		options: {
			scales: {
				xAxes: [
					{
						type: 'time',
						time: {
							unit: 'hour',
							stepSize: 3,
							tooltipFormat: 'hh A'
						},
						ticks: {
							major: {
								enabled: true,
								fontStyle: 'bold',
								fontSize: 15,
								fontColor: 'rgb(255,255,255)'
							}
						}
					}
				]
			},
			plugins: {
				datalabels: {
					display: false
				}
			}
		}
	};

	return new Chart(timeSeries,option);
}

function plotPredict(topic){
	let dfFile = 'data/forecast/df.csv';
	let fcFile = 'data/forecast/fc.csv';

	let labels = [];
	let nullLen;
	let df = [];
	let fc =[];

	d3.csv(dfFile).then(function(response){
		//nullLen = response.length - 1;
		for (var i = 0; i < response.length; i++){
			labels.push(response[i].index);
			df.push(response[i].topic);
			fc.push(response[i].topic);
		}
	});

	d3.csv(fcFile).then(function(response){
		for (var i = 1; i < response.length; i++){
			labels.push(response[i].index);
			fc.push(response[i].forecast);
		}
	});

	let timeSeries = document.getElementById("timeSeries").getContext('2d');
	let option = {
		type: 'line',
		data: {
			labels: labels,
			datasets: [
				{
	            	data: df,
	            	label: 'Topic chosen: '+topic,
	            	borderColor: colors[topic-1],
	            	lineTension: 0,
	            	fill: false
	        	},
	        	{
	            	data: fc,
	            	label: 'forecast',
	            	borderColor: 'white',
	            	lineTension: 0,
	            	fill: false
	        	}
			]
		},
		options: {
			scales: {
				xAxes: [
					{
						type: 'time',
						time: {
							unit: 'hour',
							stepSize: 3,
							tooltipFormat: 'hh A'
						},
						ticks: {
							major: {
								enabled: true,
								fontStyle: 'bold',
								fontSize: 15,
								fontColor: 'rgb(255,255,255)'
							}
						}
					}
				]
			},
			plugins: {
				datalabels: {
					display: false
				}
			}
		}
	};
	return new Chart(timeSeries,option);
}

let lineChart;

function showTopic(setAlert, wordCloudPath, fileName, title, seriesPath, topicsPath){
	$('.grid-items:eq(0)').empty();
	$('.grid-items:eq(0)').append('<p class="loading">Loading...</p>');

	$('.grid-items:eq(0)').load(wordCloudPath,{
		topicsPath: topicsPath,
		title: title,
		filePath: fileName,
		tag: 'Current',
		var: 'visCurrent',
		needSaveBtn: 'y'
	}, function(){
		if (setAlert==true) {
			alert('Complete');
		}
		lineChart = plotTopic(seriesPath);
	});	
}

$.post('loadFunction.php', {
	init: 't',
	source: 'topic'
},function(){
	showTopic(false, 'tools/wordCloud.php', '../data/tweets.csv', 'Current Topics', 'data/topic/topics.csv',null);
});

/*if($('#isLogin').val() == 'yes'){
	let topicspath = $('#topicspath').val();
	$('.saved-topics').load('readSavedTopics.php', {
		topicspath: topicspath
	});

	$('.refresh-topics').on('click', function(e){
		e.preventDefault();
		$('.saved-topics').empty();
		$('.saved-topics').load('readSavedTopics.php', {
			topicspath: topicspath
		});
	});
}*/


$('#backBtn').on('click', function(e){
	e.preventDefault();
	if(enableBack==true){
		lineChart.destroy();
		lineChart = plotTopic('data/topic/topics.csv');
		enableBack = false;
	}
});

$('.topics-directory').on('click', function(e){
	e.preventDefault();
	let topicsPath = $(this).val();
	let title = $(this).attr('data-title');
	let graphPath = topicsPath+'/topics_freq.csv';

	lineChart.destroy();
	showTopic(true, 'tools/wordCloud2.php', null,title, graphPath, topicsPath);
});

$('.news').load('tools/news.php');

$('#predictBtn').on('click', function(e){
	e.preventDefault();
	let topic = parseInt($('#topic-select').val());
	let periods = $('#periods').val()*parseInt($('#periods-extent').val());
	$('#submitLoad').show();
	lineChart.destroy();

	$.post('forecast.php', {
		topic: topic,
		periods: periods
	}, function(){
		alert('Done');
		lineChart = plotPredict(topic+1);
		enableBack = true;
		$('#submitLoad').hide();
	});
});

$('#submitDate').on('click', function(e){
    e.preventDefault();
    let from = $('#from').val();
    //$('#from').val('');
    let to = $('#to').val();
    //$('#to').val('');
    $('#submitLoad').show();

    $.post('loadFunction.php',{
        source: 'topic',
        from: from,
        to: to
    },function(){
        lineChart.destroy();
        showTopic(true, 'tools/wordCloud.php', '../data/tweets.csv', 'Current Topics', 'data/topic/topics.csv',null);
		$('#submitLoad').hide();
    });
});