<?php

/**
 * Handles posting a Ramco API request.
 * @param array $post arguments to be posted to the server
 * @return string JSON string with the API response
 */
function curl_request($post) {
    $curl = curl_init();

    // Set the request url and specify port 443 for SSL.
    curl_setopt($curl, CURLOPT_URL, API_URL);
    curl_setopt($curl, CURLOPT_PORT , 443);
    // Specify that the request should be posted and add the post data.
    curl_setopt($curl, CURLOPT_POST, True);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    // Verbose can be turned on to see LOTS of detail about the request and response.
    curl_setopt($curl, CURLOPT_VERBOSE, False);
    // No custom headers are needed.
    curl_setopt($curl, CURLOPT_HEADER, False);
    // Tell curl how to verify the SSL certificate.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($curl, CURLOPT_CAINFO, PEM_FILE);
    // Tell curl that curl_exec should return the response as a string instead of a direct output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Get the response.
    $resp_data = curl_exec($curl);
    $resp_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Return the response
    return $resp_data;
}


/**
 * Returns Contact ID for a given NRDS Number 
 * @param string $nrds arguments to be posted to the server
 * @return string GUID string 
 */


function getContactIDFromNRDS($nrds){
    $post = array();
    $post['key'] = API_KEY;
    $post['operation'] = 'GetEntities';
    $post['entity'] = 'Contact';
    $post['filter'] = "cobalt_NRDSID<eq>$nrds";
    $post['attributes'] = 'ContactId,FirstName,LastName';
    $json = curl_request($post);
    $data = json_decode($json, true);


   if(isset($data['Data'][0]['ContactId'])){
      //  echo "<p>Hello ".$data['Data'][0]['FirstName'].", Please rank the committee requests in order of preference.</p>";
        $contact_id=$data['Data'][0]['ContactId']; 
        return $contact_id;

    } 
    

        return 0;					

    
}

/**
 * Updates a Committee Nomination Rank 
 * @param string $guid string $rank
 * @return 
 */


function updateCommitteenNominationRank($guid,$rank){
	
	 $post['key'] = API_KEY;
	  $post['operation'] = 'updateEntity';
	  $post['entity'] = 'cobalt_committeenomination';
	  $post['guid']= $guid;
	  $post['AttributeValues'] = "mar_rank=$rank";
	  $json = curl_request($post);
	  $updateRegistrations = json_decode($json,true);
	
	//tbc error checking
	
	
	
}


/**
 * Returns Array of Committee Nominations given a NRDS Number
 * @param string $nrds arguments to be posted to the server
 * @return string GUID string  
 */


function getCommitteenNominationsFromNRDS($nrds){
	
	
	$guid=getContactIDFromNRDS($nrds);
	if ($guid){
		$post = array();
		$post['key'] = API_KEY;
		$post['operation'] = 'GetEntities';
		$post['entity'] = 'cobalt_committeenomination';
		$post['attributes'] = 'cobalt_name,cobalt_committeeid,cobalt_committeenominationid,mar_rank';
		$post['maxresults'] = '50';
		$post['filter'] = "statecode<eq>0 and cobalt_nomineeid<eq>$guid";												
		$json = curl_request($post);
		$data = json_decode($json,true);

		

	    if($data['ResponseCode']==200){
		  
		  return $data['Data'];

		} else {
			
			return 0;
		}
			
    }

 
 
 return 0;					

    
}

 /**
 * Prints out arrays slightly prettier clearing RAMCO API Meta deta a RAMCO API request.
 * @param array string $arr
 * @return null
 */
function pretty_print($arr){
  
				 echo "<pre>";
				 print_r($arr);
				 echo "</a>";

}



/**
 * Handles clearing RAMCO API Meta deta a RAMCO API request.
 * This allows custom variables in RAMCO to be accessed immediately via the API
 */
function clearCache(){
  
				    $post = array();
                    $post['key'] = API_KEY;
                    $post['operation'] = 'clearCache';
					
                    $json = curl_request($post);
                    $data = json_decode($json,true);
					

}
?>
