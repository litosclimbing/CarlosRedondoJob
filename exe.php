<?php
include('libs/functions.php');

echo 'Insert your desired hashtag:';
$hashtag = trim(fgets(STDIN)); 

$TwitterData = load_tweets($hashtag);
save_tweets($TwitterData,$hashtag);
getTweetsByHashtag($hashtag);
?>
