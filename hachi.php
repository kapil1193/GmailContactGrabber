
<html>
<head>
<meta name="robots" content="noindex" />
<title>Import Gmail or Google contacts using Google Contacts Data API and OAuth 2.0</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="well" >
		<h3> GMAIL Contacts. </h3>  <p> Adjust $max_results to get more results </p>
		<p> Contacts who have not provided entire details will be replaced by N/A </p>
	</div>
	
<?php
$client_id='679038716016-p1ul78oh1jo72rf72um1kshrb5tp8159.apps.googleusercontent.com';
$client_secret='hagqxqD7yqB2ejxP8z3iu4Ka';
$redirect_uri='http://localhost/hachi/hachi.php';
$max_results = 40;
 
$auth_code = $_GET["code"];
 
function curl_file_get_contents($url)
{
 $curl = curl_init();
 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
 
 curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	
 
 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
 curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.
 
 $contents = curl_exec($curl);
 curl_close($curl);
 return $contents;
}
 
$fields=array(
    'code'=>  urlencode($auth_code),
    'client_id'=>  urlencode($client_id),
    'client_secret'=>  urlencode($client_secret),
    'redirect_uri'=>  urlencode($redirect_uri),
    'grant_type'=>  urlencode('authorization_code')
);
$post = '';
foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
$post = rtrim($post,'&');
 
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl,CURLOPT_POST,5);
curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,FALSE);
$result = curl_exec($curl);
curl_close($curl);
 
$response =  json_decode($result);
$accesstoken = $response->access_token;
 
$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
$xmlresponse =  curl_file_get_contents($url);
 
$temp = json_decode($xmlresponse,true);
 $count=0;
echo "<table class='table'>
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone No.</th>
            <th>Street</th>
            <th>Neighbourhood</th>
            <th>City</th>
            <th>Region</th>
            <th>Country</th>
            <th>Postal Code</th>
            
          </tr>
        </thead>
        <tbody>";
        foreach($temp['feed']['entry'] as $cnt) {	
        	$count++;
         echo "<tr>";
			echo "<td>";echo $count;echo "</td>";
			echo "<td>";if ($cnt['title']['$t']=="") echo "N/A"; else echo $cnt['title']['$t'];  echo "</td>";
			echo "<td>";if(isset($cnt['gd$email'])) echo $cnt['gd$email']['0']['address'];else echo "N/A"; echo "</td>";
			echo "<td>";if(isset($cnt['gd$phoneNumber'])) echo $cnt['gd$phoneNumber'][0]['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$street'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$street']['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$neighborhood'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$neighborhood']['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$postcode'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$postcode']['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$city'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$city']['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$region'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$region']['$t']; else echo "N/A";echo "</td>";
			echo "<td>";if(isset($cnt['gd$structuredPostalAddress'][0]['gd$country'])) echo $cnt['gd$structuredPostalAddress'][0]['gd$country']['$t']; else echo "N/A";echo "</td>";
          echo "</tr>";
      }
          echo "</tbody></table>";

?>

</body></html>

