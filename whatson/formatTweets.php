<?php

	function cloneSearchTweets($searchWord='*', $count=1, $extend=''){//for testing
		require_once 'credential/cred.php';
		require_once 'packages/TwitterAPIExchange.php';

		$settings = array(//access/oauth tokens and api/consumer keys 
							'oauth_access_token' => Access_Token,
							'oauth_access_token_secret' => Access_Token_Secret,
							'consumer_key' => API_Key,
							'consumer_secret' => API_Secret_Key
						);

		$url = 'https://api.twitter.com/1.1/search/tweets.json';//url for search tweets

		$requestMethod = 'GET';

		$lang = 'en';//language of tweets
		$tweet_mode = '&tweet_mode=extended';//to get full text

		$tweets = array();

		$counter = 0;//count tweets fetched

		$getField = '?q='.$searchWord.'&count='.strval($count).'&lang='.$lang.$tweet_mode.$extend;

		$twitter = new TwitterAPIExchange($settings);

		while($counter < $count){
			$results = $twitter->setGetField($getField)
						   ->buildOauth($url, $requestMethod)
						   ->performRequest();

			//echo $results;//testing. results in json

			$statuses = json_decode($results, true);//convert from json to associative array so can use foreach

			//print_r($statuses);//testing
			foreach ($statuses['statuses'] as $status) {
				/*$urls = array();
				foreach ($status['entities']['urls'] as $url) {
					array_push($urls, $url['expanded_url']);
				}
				$tweet = array(
									'created_at' => $status['created_at'], 
									'full_text' => $status['full_text'],
									'name'=>$status['user']['name'],
									'screen_name'=>$status['user']['screen_name'],
									'profile_img'=>$status['user']['profile_image_url'],
									'urls'=>$urls
								);
				array_push($tweets, $tweet);*/
				print_r($status);
				$counter++;
			}

			$next_results = array_key_exists('next_results', $statuses['search_metadata']);//this allow search pagination

			if(!$next_results){
				break;
			}

			$getField = $statuses['search_metadata']['next_results'].$tweet_mode;
		}

		return $tweets;
	}

	function searchTweets($searchWord='*', $count=10, $extend=''){
		require_once 'credential/cred.php';
		require_once 'packages/TwitterAPIExchange.php';

		$settings = array(//access/oauth tokens and api/consumer keys 
							'oauth_access_token' => Access_Token,
							'oauth_access_token_secret' => Access_Token_Secret,
							'consumer_key' => API_Key,
							'consumer_secret' => API_Secret_Key
						);

		$url = 'https://api.twitter.com/1.1/search/tweets.json';//url for search tweets

		$requestMethod = 'GET';

		$lang = 'en';//language of tweets
		$tweet_mode = '&tweet_mode=extended';//to get full text

		$tweets = array();

		$counter = 0;//count tweets fetched

		$getField = '?q='.$searchWord.'&count='.strval($count).'&lang='.$lang.$tweet_mode.$extend;

		$twitter = new TwitterAPIExchange($settings);

		while($counter < $count){
			$results = $twitter->setGetField($getField)
						   ->buildOauth($url, $requestMethod)
						   ->performRequest();

			//echo $results;//testing. results in json

			$statuses = json_decode($results, true);//convert from json to associative array so can use foreach

			//print_r($statuses);//testing
			foreach ($statuses['statuses'] as $status) {
				$tweet = array(
									'created_at' => $status['created_at'], 
									'full_text' => $status['full_text'],
									'name'=>$status['user']['name'],
									'screen_name'=>$status['user']['screen_name'],
									'profile_img'=>$status['user']['profile_image_url']
								);
				array_push($tweets, $tweet);
				$counter++;
			}

			$next_results = array_key_exists('next_results', $statuses['search_metadata']);//this allow search pagination

			if(!$next_results){
				break;
			}

			$getField = $statuses['search_metadata']['next_results'].$tweet_mode;
		}

		return $tweets;
	}

	function getFromUsers(array $screenNames, string $since, string $until, string $dayBefore){//get tweets form users within time range
		//get credential and API package
		require_once 'credential/cred.php';
		require_once 'packages/TwitterAPIExchange.php';

		$settings = array(//access/oauth tokens and api/consumer keys 
							'oauth_access_token' => Access_Token,
							'oauth_access_token_secret' => Access_Token_Secret,
							'consumer_key' => API_Key,
							'consumer_secret' => API_Secret_Key
						);

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';//url for fetch from user timeline
		$requestMethod = 'GET';
		$count = 200;//number of tweets to fetch. max 200
		$tweet_mode = '&tweet_mode=extended';//to get full text
		$tweets = array();//array of tweets fetched

		$since = strtotime($since);
		$until = strtotime($until);

		$twitter = new TwitterAPIExchange($settings);

		for ($i = 0; $i < count($screenNames) ; $i++) { 
			$getField = '?screen_name='.$screenNames[$i].'&count='.strval($count).$tweet_mode;

			$results = $twitter->setGetField($getField)
							   ->buildOauth($url, $requestMethod)
							   ->performRequest();

			//echo $results;//testing. results in json

			$statuses = json_decode($results, true);//convert from json to associative array so can use foreach

			//print_r($statuses);//testing

			foreach ($statuses as $status) {
				$date = strtotime($status['created_at']);
				if(($date >= $since) && ($date <= $until)){//check in between time range
					if(!strpos($status['created_at'], $dayBefore)){//there is error in above if the time post at 11pm
						$tweet = array(
										'created_at' => $status['created_at'], 
										'full_text' => $status['full_text']
									);
						array_push($tweets, $tweet);
					}
				}
			}
		}

		return $tweets;
	}

	function getFromUsers2(array $screenNames, string $date){//trying another way which is more simple. No date comparison
		//get credential and API package
		require_once 'credential/cred.php';
		require_once 'packages/TwitterAPIExchange.php';

		$settings = array(//access/oauth tokens and api/consumer keys 
							'oauth_access_token' => Access_Token,
							'oauth_access_token_secret' => Access_Token_Secret,
							'consumer_key' => API_Key,
							'consumer_secret' => API_Secret_Key
						);

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';//url for fetch from user timeline
		$requestMethod = 'GET';
		$count = 200;//number of tweets to fetch. max 200
		$tweet_mode = '&tweet_mode=extended';//to get full text
		$tweets = array();//array of tweets fetched

		$twitter = new TwitterAPIExchange($settings);

		for ($i = 0; $i < count($screenNames) ; $i++) { 
			$getField = '?screen_name='.$screenNames[$i].'&count='.strval($count).$tweet_mode;

			$results = $twitter->setGetField($getField)
							   ->buildOauth($url, $requestMethod)
							   ->performRequest();

			//echo $results;//testing. results in json

			$statuses = json_decode($results, true);//convert from json to associative array so can use foreach

			//print_r($statuses);//testing

			foreach ($statuses as $status) {
				if(strpos($status['created_at'], $date)){
					$tweet = array(
									'created_at' => $status['created_at'], 
									'full_text' => $status['full_text'],
									'screen_name' => $screenNames[$i]
								);
					array_push($tweets, $tweet);
				}
			}
		}

		return $tweets;
	}
?>