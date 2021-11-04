<?php

	function displayContent($content){
		//print_r($contents);
		$news = json_decode($content,true);
		foreach ($news as $new) {
			echo '<div class="content">';
			echo '<img alt="news pic" width="10%" height="10%" src="'.$new['imageUrl'].'">';
			echo '<a href="'.$new['url'].'" target="_blank"><p><b>'.$new['title'].'</b></p></a>';
			echo '<p>'.$new['description'].'</p>';
			echo '<p><span class="grey">by '.$new['source'].' '.date('h:i A M d, Y', strtotime($new['date'])).'</span></p>';
			echo '</div>';
		}
	}
	echo '<p class="loading">Loading....</p>';
	date_default_timezone_set('Asia/Kuala_Lumpur');

	$generalNews = exec('python ../python/getHeadlines.py general');
	$healthNews = exec('python ../python/getHeadlines.py health');
	$techNews = exec('python ../python/getHeadlines.py technology');
	$sportNews = exec('python ../python/getHeadlines.py sports');
	$entertainmentNews = exec('python ../python/getHeadlines.py entertainment');
	$businessNews = exec('python ../python/getHeadlines.py business');
	$scienceNews = exec('python ../python/getHeadlines.py science');

	echo '<h2>News</h2>';

	echo '<div class="news-header">';

	echo '<button class="newsTab active" data-input="categories" data-content="headlines">Top Headlines</button>';
	echo '<button class="newsTab" data-input="query" data-content="searchResults">Search News</button>';

	echo '<div class="newsTabInput" id="categories">';
	echo '<button class="news-category active" value="general">News</button>';
	echo '<button class="news-category" value="health">Health</button>';
	echo '<button class="news-category" value="technologies">Technologies</button>';
	echo '<button class="news-category" value="sports">Sports</button>';
	echo '<button class="news-category" value="entertainment">Entertainment</button>';
	echo '<button class="news-category" value="business">Business</button>';
	echo '<button class="news-category" value="science">Science</button>';
	echo '</div>';

	echo '<div class="newsTabInput" id="query">';
	echo '<input id="searchInput" type="text" placeholder="Search Here" autocomplete="off">';
	echo '<button class="newsSearch">Search</button>';
	echo '</div>';

	echo '</div>';

	echo '<div class="newsContent" id="headlines">';
	echo '<div class="newsHeadlines" id="general">';
	displayContent($generalNews);
	echo '</div>';
	echo '<div class="newsHeadlines" id="health">';
	displayContent($healthNews);
	echo'</div>';
	echo '<div class="newsHeadlines" id="technologies">';
	displayContent($techNews);
	echo '</div>';
	echo '<div class="newsHeadlines" id="sports">';
	displayContent($sportNews);
	echo '</div>';
	echo '<div class="newsHeadlines" id="entertainment">';
	displayContent($entertainmentNews);
	echo '</div>';
	echo '<div class="newsHeadlines" id="business">';
	displayContent($businessNews);
	echo '</div>';
	echo '<div class="newsHeadlines" id="science">';
	displayContent($scienceNews);
	echo '</div>';

	echo'</div>';

	echo '<div class="newsContent" id="searchResults">News Search</div>';

	echo '<div class="news-footer"></div>';



	echo "<script> $('.loading').remove(); </script>";
?>

<script type="text/javascript">

	showTab('categories', 'headlines');
	showContent('general');

	$('.newsTab').on('click',function(e){
		e.preventDefault();
		var input = $(this).attr('data-input');
		var content = $(this).attr('data-content');
		$('.newsTab').removeClass('active');
		$(this).addClass('active');
		showTab(input, content);
	});

	function showTab(input, content){
		$('.newsTabInput').hide();
		$('.newsContent').hide();
		$('#'+input).show();
		$('#'+content).show();
	}

	$('.news-category').on('click',function(e){
		e.preventDefault();
		var content = $(this).val();
		$('.news-category').removeClass('active');
		$(this).addClass('active');
		showContent(content);
	});

	function showContent(content){
		$('.newsHeadlines').hide();
		$('#'+content).show();
	}

	$('.newsSearch').on('click', function(e){
		e.preventDefault();
		var searchInput = $('#searchInput').val();

		$('#searchResults').load('tools/searchNews.php',{
			searchInput: searchInput
		});
	});
</script>