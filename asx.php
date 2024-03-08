<?php
//Set maximum age of cache file before refreshing it
$cacheLife = 86403; // in seconds

//Set remote URL
$remoteURL = 'https://asx.api.markitdigital.com/asx-research/1.0/markets/announcements?entityXids[]=204284091&dateStart=2021-01-01&dateEnd=2021-12-31&summaryCountsDate=2021-12-31&itemsPerPage=200';

//Set cacheName (We're assuming it's going to reside in the same directory as the caller script).

$cacheName = '_eventCache-announce-202021.php';

//Build the path to the cache
$cacheFileName = str_replace('/','\\', dirname(__FILE__)) . '\\'.$cacheName;


//Create Remote Retrieval / Cache Function - Reuse is good.
function FN_CacheEventPage($sourceURL, $destinationFile){
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $sourceURL);
 $fp = fopen($destinationFile, 'w');
 curl_setopt($ch, CURLOPT_FILE, $fp);
 curl_exec ($ch);
 curl_close ($ch);
 fclose($fp);
 $ReturnFileContent = file_get_contents($destinationFile, FILE_USE_INCLUDE_PATH);
 return $ReturnFileContent;
}

//Check to see if the file exists and ut still has Life

if (!file_exists($cacheFileName) or (time() - filemtime($cacheFileName) >= $cacheLife)){
    $cacheFileContent = FN_CacheEventPage($remoteURL, $cacheFileName);
}
else{
// If the cache file isn't there, or is too old, build a new one!   
  $cacheFileContent = file_get_contents($cacheFileName, FILE_USE_INCLUDE_PATH);
}
include($cacheFileContent);
$obj = json_decode( $cacheFileContent); 
//print_r($obj);
//echo $obj->data->items[0]->headline;
//echo $obj->data->items[1]->headline;

$the_array =  $obj
                ->data
                ->items;
                
echo '<div id="announce">';
foreach($the_array as $field ) {
    echo '<div class="announce-i"><div class="announce-ia">';
    $linka = $field
            ->documentKey;
    echo '<a href="markitdigital_url'.$linka.'?access_token='.$linka.'" target="_blank"?>';
    $date = $field
            ->date;
    //$datex = parse_str($date);
    echo $field
            ->headline;
    echo '</a></div><div class="announce-ib">';
    //echo $date;
    echo explode('T',$date)[0];
    echo '</div></div>';
}
echo '</div>';

//print_r(date_parse("2020-10-11 12:30:45.5")); - show array of date in string
// Return date/time info of a timestamp; then format the output
//$mydate=getdate(date("U"));
//echo "$mydate[year]";
//$d1=strtotime("December 14");
//$d2=ceil(($d1-time())/60/60/24);
//echo "There are " . $d2 ." days until 4th of July.";

echo '<div id="announce">';
foreach($the_array as $field ) {
    if ($mydate[year] == "2019") {
        echo $field
            ->headline;
       echo "$mydate[year]";
    }
}
echo '</div>';    
?>
<style>
    .announce-i {
        display: block;
    }
    .announce-ia {
        display: inline-block;
        padding: 10px;
       border: 1px solid #ddd;
       width: 60%;
    }
    .announce-ib {
        display: inline-block;
        padding: 10px;
        border: 1px solid #ddd;
        width: 30%;
    }
</style>
