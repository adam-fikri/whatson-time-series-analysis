function plotSentimentPie(){
	let freqFile = 'data/sentiment/sentiment_freq.csv';

	let freq = [];
	let sum = 0;

	d3.csv(freqFile).then(function(response){
		for (var i = 0; i < response.length; i++){
			freq.push(response[i].freq);
			sum += parseInt(response[i].freq);
			//console.log(sum);
		}
	});

	let percent = document.getElementById("percent").getContext('2d');

	let option2 ={
		type: 'pie',
		data: {
			labels: ['positive', 'negative', 'neutral'],
			datasets: [
				{
					data: freq,
					backgroundColor: ['blue', 'red', 'grey'],
					borderColor: ['blue', 'red', 'grey']
				}
			]
		},
		options: {
			tooltips: {
				enabled: false
			},
			plugins: {
				datalabels: {
					formatter: function(value, ctx){
						return (value*100/sum).toFixed(2) + '%';
					},
					color: '#fff'
				}
			}
		}
	};

	return new Chart(percent,option2);
}

function plotSentimentTS(){
	
	let fileName = 'data/sentiment/sentiments.csv';

	let labels = [];
	let dataPos = [];
	let dataNeg = [];
	let dataNeu = [];

	d3.csv(fileName).then(function(response){
		for (var i = 0; i < response.length; i++){
			labels.push(response[i].dateTime);
			dataPos.push(response[i].pos);
			dataNeg.push(response[i].neg);
			dataNeu.push(response[i].neu);
		}
	});

	let timeSeries = document.getElementById("timeSeries").getContext('2d');
	let option1 = {
		type: 'line',
		data: {
			labels: labels,
			datasets: [
				{
            		data: dataPos,
            		label: 'positive',
            		borderColor: 'blue',
            		backgroundColor: 'blue',
            		lineTension: 0,
            		fill: false
        		},
        		{
            		data: dataNeg,
            		label: 'negative',
            		borderColor: 'red',
            		backgroundColor: 'red',
            		lineTension: 0,
            		fill: false
        		},
        		{
            		data: dataNeu,
            		label: 'neutral',
            		borderColor: 'grey',
            		backgroundColor: 'grey',
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
	return new Chart(timeSeries,option1);
}

function showAll(){
	lineChart = plotSentimentTS();
	pieChart = plotSentimentPie();

	$('.grid-items:eq(0)').load('tools/wordCloud.php',{
		filePath: '../data/sentiment/pos_tweets.csv',
		title: 'Topics of Positive Sentiment',
		tag: 'Pos',
		var: 'visPos',
		needSaveBtn: 'n'
	});
	$('.grid-items:eq(1)').load('tools/wordCloud.php',{
		filePath: '../data/sentiment/neu_tweets.csv',
		title: 'Topics of Neutral Sentiment',
		tag: 'Neu',
		var: 'visNeu',
		needSaveBtn: 'n'
	});

	$('.grid-items:eq(2)').load('tools/wordCloud.php',{
		filePath: '../data/sentiment/neg_tweets.csv',
		title: 'Topics of Negative Sentiment',
		tag: 'Neg',
		var: 'visNeg',
		needSaveBtn: 'n'
	});

	$('.top-tweets').load('tools/topTweets.php',{
		needSector: 'f',
		num: 10
	});
}

function destroyAll(){
	lineChart.destroy();
	pieChart.destroy();

	$('.grid-items:eq(0)').empty();
	$('.grid-items:eq(0)').append('<p class="loading">Loading...</p>');

	$('.grid-items:eq(1)').empty();
	$('.grid-items:eq(1)').append('<p class="loading">Loading...</p>');

	$('.grid-items:eq(2)').empty();
	$('.grid-items:eq(2)').append('<p class="loading">Loading...</p>');

	$('.top-tweets').empty();
	$('.top-tweets').append('<p class="loading">Loading...</p>');
}

let lineChart;
let pieChart;

$.post('loadFunction.php', {
	init: 't',
	source: 'sentiment'
}, function(){
	showAll();
});

$('#submitDate').on('click', function(e){
	e.preventDefault();
	let from = $('#from').val();
	//$('#from').val('');
	let to = $('#to').val();
	//$('#to').val('');
	$('#submitLoad').show();

	$.post('loadFunction.php',{
		source: 'sentiment',
		from: from,
		to: to
	},function(){
		alert('Complete');
		destroyAll();
		showAll();
		$('#submitLoad').hide();
	});
});
