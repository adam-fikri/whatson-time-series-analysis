<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>
<script src="https://d3js.org/d3.v6.min.js"></script>
<!--script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.bundle.js"></script-->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script type="text/javascript">
	$('body').click(function(e){
		if($(e.target).is('.modal')){
			$('.modal-content').hide();
			$('.modal').hide();
			$('.modal-content').empty();
		}
	});
</script>