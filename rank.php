<?php
  /**
  * rank.php
  * This file takes GET variable NRDS from URL, looks up active or pending committee nominations.
  * The committee order is then sent to post.php via POST command.
  * Written by Dave Conroy
  * dconroy@marealtor.com April 2015
  * API Doc URL : https://api.ramcoams.com/api/v2/ramco_api_v2_doc.pdf
  */



require_once "config.php";
require_once "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Committee Choice</title>

  <link href="style.css" rel="stylesheet" type="text/css" />
  <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
   <script src="jquery.ui.touch-punch.js"></script>
  <script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
 $(function() {
        $( "#sortable" ).sortable({
            placeholder: "ui-state-highlight",
            cursor: 'crosshair',
            update: function(event, ui) {
                var order = $("#sortable").sortable("toArray");
                $('#committee_order').val(order.join(","));
              //  alert($('#committee_order').val());
            }
    });
        $( "#sortable" ).disableSelection();
});
});//]]>  

</script>


</head>
<body>
<img src="Logo.png">
<h2>Please Rank Your Committee Choices</h2>
<p>
Click the Committee Name to Drag and Drop in order of preference, the top being the committee in which you would most like to serve.
</p>
  
  

<?php
	//make sure the NRDS value was set via get (in the url rank.php?NRDS=xxxxxxxxx)
	if (isset($_GET["NRDS"])){
		$nrds=$_GET["NRDS"];
   
	    //return all committee nominations for a given nrds number
		$committee_nominations=getCommitteenNominationsFromNRDS($nrds);
		
		//make sure we have committee nominations 
		if ($committee_nominations){
			
			
			// function to display them by rank, if rank exists
			usort($committee_nominations, function($a, $b) {
				return $a['MAR_Rank'] - $b['MAR_Rank'];
			});
			
			
			
			//pretty_print($committee_nominations);
			
			
			//build the form to submit the new order
			echo "<form action=\"post.php\" method=\"post\">
						<input type=\"hidden\" id=\"committee_order\" name=\"committee_order\" value=\"\" />
						<input type=\"hidden\" id=\"nrds\" name=\"nrds\" value=\"$nrds\" />
						<ul id=\"sortable\" style=\"width: 524px;\">";
				   
				   
			for ($i=0;$i<sizeof($committee_nominations);$i++){
				$nomination_id=$committee_nominations[$i]["cobalt_committeenominationId"];
				$committee_name=trim($committee_nominations[$i]["cobalt_CommitteeId"]["Display"]);
				
				
				echo "<li id=\"$nomination_id\" class=\"ui-state-default\">$committee_name</li>";
			}
			
			echo "</ul>
				  <div style=\"clear:both;\"></div>
				  
				  <input  align=right type=\"submit\" />
				  </form>";
			
		} else {
			
			echo "Error: No Committee Nominations found for NRDS ID:$nrds";
		
		}

		
	} else {
		echo "Error: No GET Value for NRDS<br>";
		
			//tbc add textbox for manual nrds entry
		  
		
	};  
					
					
echo "</body></html>";
?>
