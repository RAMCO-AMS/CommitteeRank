<?php

  /**
  * post.php
  * This file takes post variable committee_order and applies them to Custom RAMCO Committee Nomination Attribute mar_rank
  * Written by Dave Conroy
  * dconroy@marealtor.com April 2015
  * API Doc URL : https://api.ramcoams.com/api/v2/ramco_api_v2_doc.pdf
  */

require_once "config.php";
require_once "functions.php";
?>

<html>
<body>


<?php 

if (isset($_POST["committee_order"])){
	
  $committees = explode(",", $_POST["committee_order"]);
  
  if ($committees[0]==""){
	  //no changes were submitted so we have to resubmit just in case the committee ranks were NULL
	  
		$committee_nominations=getCommitteenNominationsFromNRDS($_POST["nrds"]);
		
		//make sure we have committee nominations 
		if ($committee_nominations){
			
			
			// use same logic to rank as rank.php did
			usort($committee_nominations, function($a, $b) {
				return $a['MAR_Rank'] - $b['MAR_Rank'];
			});
			
			
			 for ($i=1;$i<=sizeof($committee_nominations);$i++){
			 
			    $guid=$committee_nominations[$i-1]["cobalt_committeenominationId"];
				$rank=$i;
			
				updateCommitteenNominationRank($guid, $rank);
						 
			 
			 }
		
			
		}
		
	  echo "No Changes to Rank";
  
  } else {
	  
	    //pretty_print($committees);
	    for ($i=1;$i<=sizeof($committees);$i++){
				$guid=$committees[$i-1];
				$rank=$i;
				
				updateCommitteenNominationRank($guid, $rank);
		
			}
	  
		echo "Successfully updated your preferences!";
  		
  }
  
  
  
			
} else {
	

		
echo "No Changes made: Error Processing NRDS ID or Committees";
	
}
		
	

//echo "EOF";
?>


</body>
</html>




