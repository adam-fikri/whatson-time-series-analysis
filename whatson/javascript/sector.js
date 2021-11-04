function plotSectorTS(){
    let fileName = 'data/sector/sectors.csv';

    let labels = [];
    let dataNews = [];
    let dataSports = [];
    let dataScitech = [];
    let dataPolitics = [];
    let dataEntertainment = [];
    let dataBusiness = [];
    let dataTravel = [];
    let dataHealthcare = [];

    d3.csv(fileName).then(function(response){
        for (var i = 0; i < response.length; i++) {
            labels.push(response[i].dateTime);
            dataNews.push(parseInt(response[i].news));
            dataSports.push(parseInt(response[i].sports));
            dataScitech.push(parseInt(response[i].scitech));
            dataPolitics.push(parseInt(response[i].politics));
            dataEntertainment.push(parseInt(response[i].entertainment));
            dataBusiness.push(parseInt(response[i].business));
            dataTravel.push(parseInt(response[i].travel));
            dataHealthcare.push(parseInt(response[i].healthcare));
        }
    });

    let timeSeries = document.getElementById("timeSeries").getContext('2d');
    let option1 = {
        type: 'line',
        data:{
            labels: labels,
            datasets: [
                {
                    data: dataNews,
                    label: 'news',
                    borderColor: 'red',
                    backgroundColor: 'red',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataSports,
                    label: 'sports',
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataScitech,
                    label: 'science and tech',
                    borderColor: 'pink',
                    backgroundColor: 'pink',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataPolitics,
                    label: 'politics',
                    borderColor: 'green',
                    backgroundColor: 'green',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataEntertainment,
                    label: 'entertainment',
                    borderColor: 'yellow',
                    backgroundColor: 'yellow',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataBusiness,
                    label: 'business and economics',
                    borderColor: 'purple',
                    backgroundColor: 'purple',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataTravel,
                    label: 'travel',
                    borderColor: 'black',
                    backgroundColor: 'black',
                    lineTension: 0,
                    fill: false
                },
                {
                    data: dataHealthcare,
                    label: 'healthcare',
                    borderColor: 'brown',
                    backgroundColor: 'brown',
                    lineTension: 0,
                    fill: false
                }
            ]
        },
        options:{
            scales:{
                xAxes:[
                    {
                        type: 'time',
                        time:{
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

function plotSectorPie(){
    let freqFile = 'data/sector/sector_freq.csv';
    let freq =[];
    let sum = 0;

    d3.csv(freqFile).then(function(response){
        //freq = response;
        for (var i = 0; i < response.length; i++){
            freq.push(response[i].freq);
            sum += parseInt(response[i].freq);
            //console.log(sum);
        }
    });

    let percent = document.getElementById("percent").getContext('2d');
    let option2 = {
        type: 'pie',
        data:{
            labels: ['news', 'sports', 'science and tech', 'politics', 'entertainment', 'business and economics', 'travel', 'healthcare'],
            datasets: [
                {
                    data: freq,
                    backgroundColor: ['red','blue','pink','green','yellow','purple','black','brown'],
                    borderColor: ['red','blue','pink','green','yellow','purple','black','brown']
                }
            ]
        },
        options: {
            tooltips: {
                enabled: false
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) =>{
                        return (value*100/sum).toFixed(2) + '%';
                    },
                    color: '#fff'
                }
            }
        }
    };

    return new Chart(percent,option2);
}

function plotSectorMonthly(){
    let freqJanFile = 'data/monthly_tweets/sector_freq/Jan.csv';
    let freqFebFile = 'data/monthly_tweets/sector_freq/Feb.csv';
    let freqMarFile = 'data/monthly_tweets/sector_freq/Mar.csv';
    let freqAprFile = 'data/monthly_tweets/sector_freq/Apr.csv';
    let freqMayFile = 'data/monthly_tweets/sector_freq/May.csv';
    let freqJuneFile = 'data/monthly_tweets/sector_freq/Jun.csv';

    let monthlyNews = [];
    let monthlySports = [];
    let monthlyScitech = [];
    let monthlyPolitics = [];
    let monthlyEntertainment = [];
    let monthlyBusiness = [];
    let monthlyTravel = [];
    let monthlyHealthcare = [];

    d3.csv(freqJanFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    d3.csv(freqFebFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    d3.csv(freqMarFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    d3.csv(freqAprFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    d3.csv(freqMayFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    d3.csv(freqJuneFile).then(function(response){
        monthlyNews.push(response[0].news);
        monthlySports.push(response[0].sports);
        monthlyScitech.push(response[0].scitech);
        monthlyPolitics.push(response[0].politics);
        monthlyEntertainment.push(response[0].entertainment);
        monthlyTravel.push(response[0].travel);
        monthlyBusiness.push(response[0].business);
        monthlyHealthcare.push(response[0].healthcare);
    });

    let monthlyTimeSeries = document.getElementById("monthlyTimeSeries").getContext('2d');
    let option3 = {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [
                {
                    data: monthlyNews,
                    label: 'news',
                    borderColor: 'red',
                    backgroundColor: 'red',
                },
                {
                    data: monthlySports,
                    label: 'sports',
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                },
                {
                    data: monthlyScitech,
                    label: 'science & tech',
                    borderColor: 'pink',
                    backgroundColor: 'pink',
                },
                {
                    data: monthlyPolitics,
                    label: 'politics',
                    borderColor: 'green',
                    backgroundColor: 'green',
                },
                {
                    data: monthlyEntertainment,
                    label: 'entertainment',
                    borderColor: 'yellow',
                    backgroundColor: 'yellow',
                },
                {
                    data: monthlyBusiness,
                    label: 'business and economics',
                    borderColor: 'purple',
                    backgroundColor: 'purple',
                },
                {
                    data: monthlyTravel,
                    label: 'travel',
                    borderColor: 'black',
                    backgroundColor: 'black',
                },
                {
                    data: monthlyHealthcare,
                    label: 'healthcare',
                    borderColor: 'brown',
                    backgroundColor: 'brown',
                }
            ]
        },
        options: {
            plugins: {
                datalabels: {
                    display: false
                }
            }
        }
    };

    let monthlyLineChart = new Chart(monthlyTimeSeries, option3);
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

    $('.grid-items:eq(3)').empty();
    $('.grid-items:eq(3)').append('<p class="loading">Loading...</p>');

    $('.grid-items:eq(4)').empty();
    $('.grid-items:eq(4)').append('<p class="loading">Loading...</p>');

    $('.grid-items:eq(5)').empty();
    $('.grid-items:eq(5)').append('<p class="loading">Loading...</p>');

    $('.grid-items:eq(6)').empty();
    $('.grid-items:eq(6)').append('<p class="loading">Loading...</p>');

    $('.grid-items:eq(7)').empty();
    $('.grid-items:eq(7)').append('<p class="loading">Loading...</p>');
}

function showAll(){
    lineChart = plotSectorTS();
    pieChart = plotSectorPie();

    $('.grid-items:eq(0)').load('tools/wordCloud.php',{
        filePath: '../data/sector/news_tweets.csv',
        title: 'Topics in News Sector',
        tag: 'News',
        var: 'visNews',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(1)').load('tools/wordCloud.php',{
        filePath: '../data/sector/sports_tweets.csv',
        title: 'Topics in Sports Sector',
        tag: 'Sports',
        var: 'visSports',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(2)').load('tools/wordCloud.php',{
        filePath: '../data/sector/scitech_tweets.csv',
        title: 'Topics in Science and Technology Sector',
        tag: 'Scitech',
        var: 'visScitech',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(3)').load('tools/wordCloud.php',{
        filePath: '../data/sector/politics_tweets.csv',
        title: 'Topics in Politics Sector',
        tag: 'Politics',
        var: 'visPolitics',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(4)').load('tools/wordCloud.php',{
        filePath: '../data/sector/entertainment_tweets.csv',
        title: 'Topics in Entertainment Sector',
        tag: 'Entertainment',
        var: 'visEntertainment',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(5)').load('tools/wordCloud.php',{
        filePath: '../data/sector/travel_tweets.csv',
        title: 'Topics in Travel Sector',
        tag: 'Travel',
        var: 'visTravel',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(6)').load('tools/wordCloud.php',{
        filePath: '../data/sector/business_tweets.csv',
        title: 'Topics in Business Sector',
        tag: 'Business',
        var: 'visBusiness',
        needSaveBtn: 'n'
    });

    $('.grid-items:eq(7)').load('tools/wordCloud.php',{
        filePath: '../data/sector/healthcare_tweets.csv',
        title: 'Topics in Healthcare Sector',
        tag: 'Healthcare',
        var: 'visHealthcare',
        needSaveBtn: 'n'
    });
}

let lineChart;
let pieChart;
//plotSectorMonthly();

$.post('loadFunction.php',{
    init: 't',
    source: 'sector'
},function(){
    showAll();
});

$('#show-monthly').on('click', function(e){
    e.preventDefault();
    $.post('loadFunction.php', {
        init: 't',
        source: 'sector_month'
    }, function(){
        plotSectorMonthly();
    });
});

/*$.post('loadFunction.php', {
    init: 't',
    source: 'sector_month'
},function(){
    plotSectorMonthly();
});*/

$('#submitDate').on('click', function(e){
    e.preventDefault();
    let from = $('#from').val();
    //$('#from').val('');
    let to = $('#to').val();
    //$('#to').val('');
    $('#submitLoad').show();

    $.post('loadFunction.php',{
        source: 'sector',
        from: from,
        to: to
    },function(){
        alert('Complete');
        destroyAll();
        showAll();
        $('#submitLoad').hide();
    });
});

