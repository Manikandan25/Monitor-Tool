<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Grafana extends CI_Controller {
	public function createDashboard($dashboardName, $template) {
		$this->createDatasource ( $dashboardName );
		
		$filename = "masterFiles/" . $template . "-master.json";
		$clientFile = "clientConfig/" . $dashboardName . "-" . $template . ".json";
		if (! file_exists ( $clientFile )) {
			$contents = file_get_contents ( $filename );
			$contents = str_replace ( "DS_SYS-TMP", "DS_" . $dashboardName, $contents );
			$contents = str_replace ( "SYS-TMP", $dashboardName . "-" . $template, $contents );
			$contents = str_replace ( "TMP", $dashboardName, $contents );
			$fp1 = fopen ( $clientFile, 'a+' );
			fwrite ( $fp1, $contents );
		}
		$configContents = file_get_contents ( $clientFile );
		$data_string = ($configContents);
		// echo $data_string;
		
		$curl = curl_init ( 'http://172.104.45.18:3000/api/dashboards/import' );
		
		curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, "POST" );
		
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json',
				'Content-Length: ' . strlen ( $data_string ),
				'Authorization : Basic YWRtaW46YWRtaW4=' 
		) );
		
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true ); // Make it so the data coming back is put into a string
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data_string ); // Insert the data
		                                                         
		// Send the request
		$result = curl_exec ( $curl );
		
		// Free up the resources $curl is using
		curl_close ( $curl );
		
		echo $result;
		
		return $filename;
	}
	public function createDatasource($datasourceName) {
		$curl = curl_init();
		//$datasourceName = "d219af79b45e5891507fda4c4c2139a0";
		curl_setopt_array($curl, array(
  		CURLOPT_PORT => "3000",
 		CURLOPT_URL => "http://172.104.45.18:3000/api/datasources/id/".$datasourceName."",
  		CURLOPT_RETURNTRANSFER => true,
  		CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
  		CURLOPT_TIMEOUT => 30,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  		CURLOPT_CUSTOMREQUEST => "GET",
  		CURLOPT_HTTPHEADER => array(
    		"accept: application/json",
    		"authorization: Bearer eyJrIjoiZEJZQU1XcllhaUd2TDZMYWRaeUdlNFg1VzRrYkxVeFgiLCJuIjoiZ293dGhhbSIsImlkIjoxfQ==",
    		"cache-control: no-cache",
    		"content-type: application/json"
  				),
			));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$response = json_decode($response,true);
		if (array_key_exists('id', $response)) {
  			$id= $response['id'];
  			$curl = curl_init();
  			curl_setopt_array($curl, array(
  			CURLOPT_PORT => "3000",
  			CURLOPT_URL => "http://172.104.45.18:3000/api/datasources/".$id,
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_ENCODING => "",
  			CURLOPT_MAXREDIRS => 10,
  			CURLOPT_TIMEOUT => 30,
  			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			CURLOPT_CUSTOMREQUEST => "PUT",
  			CURLOPT_POSTFIELDS => '{ "id": '.$id.',"orgId": 1,"name":"'.$datasourceName.'","type": "influxdb","typeLogoUrl": "","access": "proxy","url": "http://172.104.45.18:8086","password": "","user": "","database": "'.$datasourceName.'","basicAuth": false,"basicAuthUser": "","basicAuthPassword": "","withCredentials": false,"isDefault": false,"jsonData": {},"secureJsonFields": {}}',
  			CURLOPT_HTTPHEADER => array(
    				"accept: application/json",
    				"authorization: Bearer eyJrIjoiZEJZQU1XcllhaUd2TDZMYWRaeUdlNFg1VzRrYkxVeFgiLCJuIjoiZ293dGhhbSIsImlkIjoxfQ==",
    				"cache-control: no-cache",
    				"content-type: application/json"
  				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);


			if ($err) {
  				echo "cURL Error #:" . $err;
			} else {
  				echo $response;
			}
			}
			else
			{

				$data = array (
					"name" => $datasourceName,
					"type" => "influxdb",
					"url" => "http://172.104.45.18:8086",
					"access" => "proxy",
					"database" => $datasourceName,
					"basicAuth" => false 
				);
				$data_string = json_encode ( $data );
				$curl = curl_init ( 'http://172.104.45.18:3000/api/datasources' );
		
				curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, "POST" );
		
				curl_setopt ( $curl, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json',
				'Content-Length: ' . strlen ( $data_string ),
				'Authorization : Basic YWRtaW46YWRtaW4=' 
				) );
		
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true ); // Make it so the data coming back is put into a string
				curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data_string ); // Insert the data
		                                                         
		// Send the request
				$result = curl_exec ( $curl );
		
		// Free up the resources $curl is using
				curl_close ( $curl );
		
				echo $result;
			}
	}
}
	
