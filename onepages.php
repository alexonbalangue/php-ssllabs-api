<?php

/**
 * PHP-SSLLabs-API
 * 
 * This PHP library provides basic access to the SSL Labs API
 * and is build upon the official API documentation at
 * https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
 * 
 * @author Björn Roland <https://github.com/bjoernr-de>
 * @license GNU GENERAL PUBLIC LICENSE v3
 */

class sslLabsApi
{
	CONST API_URL = "https://api.ssllabs.com/api/v2";
	#CONST API_URL = "https://api.ssllabs.com/api/v2/info";
	
	private $returnJsonObjects;
	
	/**
	 * sslLabsApi::__construct()
	 */
	public function __construct($returnJsonObjects = false)
	{
		$this->returnJsonObjects = (boolean) $returnJsonObjects;
	}
	
	/**
	 * sslLabsApi::fetchApiInfo()
	 * 
	 * API Call: info
	 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
	 */
	public function fetchApiInfo()
	{
		return ($this->sendApiRequest('info'));
	}
	
	/**
	 * sslLabsApi::fetchHostInformation()
	 * 
	 * API Call: analyze
	 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
	 * 
	 * @param string $host Hostname to analyze
	 * @param boolean $publish
	 * @param boolean $startNew
	 * @param boolean $fromCache
	 * @param int $maxAge
	 * @param string $all 
	 * @param boolean $ignoreMismatch
	 */
	public function fetchHostInformation($host, $publish = false, $startNew = false, $fromCache = false, $maxAge = NULL, $all = NULL, $ignoreMismatch = false)
	{
		$apiRequest = $this->sendApiRequest
		(
			'analyze',
			array
			(
				'host'				=> $host,
				'publish'			=> $publish,
				'startNew'			=> $startNew,
				'fromCache'			=> $fromCache,
				'maxAge'			=> $maxAge,
				'all'				=> $all,
				'ignoreMismatch'	=> $ignoreMismatch
			)
		);
		
		return ($apiRequest);
	}
	
	/**
	 * sslLabsApi::fetchHostInformationCached()
	 *
	 * API Call: analyze
	 * Same as fetchHostInformation() but prefer to receive cached information
	 *
	 * @param string $host
	 * @param int $maxAge
	 * @param string $publish
	 * @param string $ignoreMismatch
	 */
	public function fetchHostInformationCached($host, $maxAge, $publish = false, $ignoreMismatch = false)
	{
		return($this->fetchHostInformation($host, $publish, false, true, $maxAge, 'done', $ignoreMismatch));
	}
	
	/**
	 * sslLabsApi::fetchEndpointData()
	 * 
	 * API Call: getEndpointData
	 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
	 * 
	 * @param string $host
	 * @param string $s
	 * @param string $fromCache
	 * @return string 
	 */
	public function fetchEndpointData($host, $s, $fromCache = false)
	{
		$apiRequest = $this->sendApiRequest
		(
			'getEndpointData',
			array
			(
				'host'		=> $host,
				's'			=> $s,
				'fromCache'	=> $fromCache
			)
		);
		
		return ($apiRequest);
	}
	
	/**
	 * sslLabsApi::fetchStatusCodes()
	 * 
	 * API Call: getStatusCodes 
	 */
	public function fetchStatusCodes()
	{
		return ($this->sendApiRequest('getStatusCodes'));
	}
	
	/**
	 * sslLabsApi::sendApiRequest()
	 * 
	 * Send API request
	 * 
	 * @param string $apiCall
	 * @param array $parameters
	 * @return string JSON from API
	 */
	public function sendApiRequest($apiCall, $parameters = array())
	{
		//we also want content from failed api responses
		$context = stream_context_create
		(
			array
			(
				'http' => array
				(
					'ignore_errors' => true
				)
			)
		);
		$url = self::API_URL . '/' . $apiCall . $this->buildGetParameterString($parameters);
		$apiResponse = file_get_contents($url, false, $context);
		
		if($this->returnJsonObjects)
		{
			return (json_decode($apiResponse));
		}		
		
		return ($apiResponse);	
	}
	
	/**
	 * sslLabsApi::setReturnJsonObjects()
	 * 
	 * Setter for returnJsonObjects
	 * Set true to return all API responses as JSON object, false returns it as simple JSON strings (default)
	 *  
	 * @param boolean $returnJsonObjects
	 */
	public function setReturnJsonObjects($returnJsonObjects)
	{
		$this->returnJsonObjects = (boolean) $returnJsonObjects;
	}
	
	/**
	 * sslLabsApi::getReturnJsonObjects()
	 * 
	 * Getter for returnJsonObjects
	 * 
	 * @return boolean true returns all API responses as JSON object, false returns it as simple JSON string
	 */
	public function getReturnJsonObjects()
	{
		return ($this->returnJsonObjects);
	}
	
	/**
	 * sslLabsApi::buildGetParameterString()
	 * 
	 * Helper function to build get parameter string for URL
	 * 
	 * @param array $parameters
	 * @return string
	 */
	private function buildGetParameterString($parameters)
	{
		$string = '';
			
		$counter = 0;
		foreach($parameters as $name => $value)
		{	
			if(!is_string($name) || (!is_string($value) && !is_bool($value) && !is_int($value)))
			{
				continue;
			}
			
			if(is_bool($value))
			{
				$value = ($value) ? 'on' : 'off';
			}
			
			$string .= ($counter == 0) ? '?' : '&';
			$string .= urlencode($name) . '=' . urlencode($value);
			
			$counter++;
		}
	
		return ($string);
	}
}
/**
 * Created by PhpStorm.
 * User: shanedejager
 * Date: 02/07/15
 * Time: 10:00
 */

#require_once 'sslLabsApi.php';#not need one pages files PHP

class SSL_Labs_Admin_API_Client {

    protected $api;
    protected $host;
    protected $response;
    protected $grades = array('A+','A-','A','B','C','D','E','F','T','M');

    public function __construct($host){
        //Return API response as JSON object
        $this->host = $host;
        $this->api = new sslLabsApi(true);
    }

    public function analyse($startnew = false){
        $response = $this->api->fetchHostInformation($this->host, false, $startnew, false, null, true, true);
        $this->response = $response;
        return $response;
    }

    public function check_grade($grade){
        $invalid_endpoints = array();
        $level = array_search($grade,$this->grades);
        foreach($this->response->endpoints as $endpoint){
            $endpoint_level = array_search($endpoint->grade,$this->grades);
            if($endpoint_level > $level){
                $invalid_endpoints[] = $endpoint;
            }
        }
        return (count($invalid_endpoints) <= 0);
    }

}

//Return API response as JSON string
$site_url = 'alexonbalangue.me';
$client = new SSL_Labs_Admin_API_Client($site_url);
$response = $client->analyse();
//echo json_encode($response);
?>
<html>
<head>
<title>This code support to PHP7/+</title>
<style>/**
 * All of the CSS for your admin-specific functionality should be
 * included in this file.
 */
.ssl-status-summary{
    margin-top: 20px;
    border-spacing: 0px;
    border-collapse: separate;
}
.ssl-status-summary tr.first th, .ssl-status-summary tr.first td{
    border-bottom:1px solid #D7D7D7;
}
.ssl-status-summary th{
    text-transform: uppercase;
    background-color: #ECECEC;
    text-align: left;
    font-size:14px;
    padding:12px 20px;
    margin:0;

}

.ssl-status-summary td{
    background-color: #FFFFFF;
    text-align: left;
    font-size:14px;
    padding:12px 20px;
    margin:0;


}
.ssl-status-summary tr td:first-child{
    border-bottom:1px solid #D7D7D7;
}
.ssl-status-details{
    margin-top: 20px;
    width:100%;
    border-spacing: 0px;
    border-collapse: separate;
}
.ssl-status-details th{
    text-transform: uppercase;
    background-color: #ECECEC;
    text-align: left;
    font-size:14px;
    padding:12px 20px;
}
.ssl-status-details .grade_col{
    width:76px;
}
.ssl-status-details .ipaddress_col{
    width:76px;
}
.ssl-status-details td{
    padding-left:22px;
    padding-right:22px;
    font-weight: bold;
    font-size: 14px;
    border-bottom:1px solid #F1F1F1;
}
.ssl-status-details tr{
    background-color: #fff;
}
.ssl-status-details a{
    text-decoration: none;
    color:inherit;
    text-decoration: underline;
    font-size: 14px;
}
.grade{
    text-align: center;
    margin: 15px auto;
    width: 72px;
    height: 72px;
    font-size: 50px;
    line-height: 72px;
    font-weight: normal;
    color:#fff;

}
.grade-a{
    background-color: #00A500;
}
.grade-b{
    background-color: #68D035;
}
.grade-c{
    background-color: #F8CF00;
}
.grade-d{
    background-color: #FFA901;
}
.grade-e{
    background-color: #FF7701;
}
.grade-f,.grade-m,.grade-t, .grade-unknown{
    background-color: #FF4D41;
}</style>
</head>
<body>
<div class="wrap">
    <h2>SSL Quality Test Results</h2>
    <table class="ssl-status-summary">
        <tr class="first">
            <th>Time Run:</th>
            <td>
                <?php echo date("d / m / Y @ H:i" , $response->startTime / 1000); ?>
            </td>
        </tr>
        <tr>
            <th><label for="textbox">Endpoints:</label></th>
            <td>
                <?php echo count( $response->endpoints) ; ?>
            </td>
        </tr>
    </table>

    <table class="ssl-status-details">
        <tr>
            <th class="grade_col">Grade</th>
            <th class="ipaddress_col">IP Address</th>
            <th></th>
        </tr>
        <?php foreach($response->endpoints as $endpoint){ ?>
            <tr>
				
                <td><div class="grade grade-<?php echo strtolower(substr($endpoint->grade,0,1)); ?>"><?php echo $endpoint->grade; ?></div></td>
                <td><?php echo $endpoint->ipAddress; ?></td>
                <td><a target="_blank" href="https://www.ssllabs.com/ssltest/analyze.html?d=<?php echo str_replace("https://","",$site_url); ?>&s=<?php echo $endpoint->ipAddress; ?>">View detailed report on Qualys SSL Labs</a></td>
            </tr>
			
        <?php } ?>
    </table>

    <div>
        
        <p>Scan provided by <a target="_blank" href="https://www.ssllabs.com/index.html">Qualys SSL Labs</a> <a target="_blank" href="https://www.ssllabs.com/downloads/Qualys_SSL_Labs_Terms_of_Use.pdf">Terms and Conditions</a></p>

    </div>

</div>
</body></html>