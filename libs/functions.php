<?php
function load_tweets($hashtag)
{
	session_start();
	require_once ("vendors/twitter-api-php-master/TwitterAPIExchange.php");
	require ('config.php');

	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$requestMethod = 'GET';
	$getfield = '?q=%23' . $hashtag;

	$twitter = new TwitterAPIExchange($settings);
	$twitter->setGetfield($getfield);
	$twitter->buildOauth($url, $requestMethod);
	$tweets = $twitter->performRequest();
	return json_decode($tweets);
}

function save_tweets($tweets, $hashtag)
{
	print_r($tweets);
	include ('config.php');
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	foreach ($tweets->results as $value)
	{
		$sql = 'INSERT INTO tweets (tweet, username, time, hashtag) VALUES (\'' . $value->text . '\', \'' . $value->from_user_name . '\', \'' . $value->created_at . '\', \'' . $hashtag . '\')';
		mysqli_query($conn, $sql);
	}
	mysqli_close($conn);
}

function getTweetsByHashtag($hashtag)
{
	include ('config.php');
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql = 'SELECT * FROM tweets WHERE hashtag=\'' . $hashtag . '\'';
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		echo 'TWEETS FOR ' . $hashtag . ':<br>';
		echo "<table><tr><th>USERNAME</th><th>TWEET</th><th>TIME</th></tr>";
		while ($row = $result->fetch_assoc())
		{
			echo "<tr><td>" . $row["username"] . "</td><td>" . $row["tweet"] . " " . $row["time"] . "</td></tr>";
		}
		echo "</table>";
	}
	else
	{
		echo "0 results";
	}
	$conn->close();
}
?>
