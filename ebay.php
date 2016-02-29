<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET , POST");
header('Content-type: application/json'); 

$Keywords=$priceRangeMin=$priceRangeMax=$Condition=$ReturnsAcceptedOnly=$FreeShippingOnly=$ExpeditedShippingType=$MaxHandlingTime=$sortOrder=$ItemsPerRange=$condition="";
$galleryURL=$title=$viewURL=$condition=$topRated=$listval=$returnaccept=$shipCost=$categoryName=$handlingtime=$shippingCost=$price=$location=$sellername=$feedback=$posfeed=$rating=$topRatedSeller=$storeName=$storeURL=$shiptype=$expedship=$oneday=$handtime=$shiploc="";
 $seller=array();
 $shipping=array();
	$Keywords="";
    $Keywords      = $_GET["Keywords"];
	$MinPrice      = $_GET["MinPrice"];
	$MaxPrice      = $_GET["MaxPrice"];
	$data=json_decode(stripslashes($_GET['Condition']));
   $Buy=json_decode(stripslashes($_GET['buy']));
   $ReturnsAcceptedOnly=$_GET["sell"];
   $FreeShippingOnly=$_GET["FreeShippingOnly"];
   $ExpeditedShippingType=$_GET["ExpeditedShippingType"];
   $MaxHandlingTime=$_GET["days"];
   $sortOrder=$_GET["sort"];
   $ItemsPerRange=$_GET["result"];
	
	

	
$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
  $responseEncoding = 'XML';   // Format of the response

  $safeQuery = urlencode (utf8_encode($_GET['Keywords']));
$priceRangeMin = urlencode( trim( $_GET["MinPrice"] ) );
  $priceRangeMax = urlencode( trim( $_GET["MaxPrice"] ) );

		
  (isset($_GET['ReturnsAcceptedOnly'])) ? $returnsAcceptedOnly='true':$returnsAcceptedOnly='';
  
  (!empty($FreeShippingOnly)) ? $FreeShippingOnly='true':$FreeShippingOnly='';
  
  $conditionString='';
  $ListingTypeString='';
  
	
	
  
	$i=-1;
    
    // Construct the FindItems call
    $apicall = "$endpoint?OPERATION-NAME=findItemsAdvanced"
         . "&SERVICE-VERSION=1.0.0"
         . "&SECURITY-APPNAME=USC77bc0f-dd8c-4dbb-a998-96794897871" //replace with your app id
         . "&keywords=$safeQuery"
         . "&paginationInput.entriesPerPage=$ItemsPerRange"
         . "&sortOrder=$sortOrder";
		 
		 
		 if(!empty($MaxHandlingTime)) {
		 $apicall =$apicall . "&itemFilter(".++$i.").name=MaxHandlingTime"
         . "&itemFilter(".$i.").value=$MaxHandlingTime";
		 }
		 
		 

		 if(!empty($priceRangeMin) || ($priceRangeMin=="0")) {
		 $apicall =$apicall . "&itemFilter(".++$i.").name=MinPrice"
         . "&itemFilter(".$i.").value=$priceRangeMin";
		 }
		 
		 if(!empty($priceRangeMax)|| ($priceRangeMax=="0")) {
		 $apicall =$apicall . "&itemFilter(".++$i.").name=MaxPrice"
         . "&itemFilter(".$i.").value=$priceRangeMax";
		 }
         
		
		if($returnsAcceptedOnly=="true") 
		 {
		 $apicall =$apicall ."&itemFilter(".++$i.").name=ReturnsAcceptedOnly"
         . "&itemFilter(".$i.").value=$returnsAcceptedOnly";
		 }
		
		 
		  if($ExpeditedShippingType=="Expedited") 
		 {
		 $apicall =$apicall . "&itemFilter(".++$i.").name=ExpeditedShippingType"
         . "&itemFilter(".$i.").value=$ExpeditedShippingType";
		 }
		 
		 if($FreeShippingOnly=="true") 
		 {
		 $apicall =$apicall . "&itemFilter(".++$i.").name=FreeShippingOnly"
         . "&itemFilter(".$i.").value=$FreeShippingOnly";
		 }
		 
		 
		 
		 $j=0;
		if(!empty($data)) {
		++$i;
		foreach($data as $check) {
            
			$conditionString=$conditionString.'&'.'itemFilter['.$i.'].value['.$j.']='.$check;
			$j++;
		}
		$apicall =$apicall . "&itemFilter(".$i.").name=Condition"
         . "$conditionString";
		}
	
		$j=0;
		if(!empty($Buy)) {
		++$i;
		foreach($Buy as $check) {
            
			$ListingTypeString=$ListingTypeString.'&'.'itemFilter['.$i.'].value['.$j.']='.$check;
			$j++;
		}
		$apicall =$apicall . "&itemFilter(".$i.").name=ListingType"
         . "$ListingTypeString";
	}
	
		 
		 
		 
		 
		 
		 
         $apicall =$apicall . "&affiliate.networkId=9"  // fill in your information in next 3 lines
         . "&affiliate.trackingId=1234567890"
         . "&affiliate.customId=456"
         . "&RESPONSE-DATA-FORMAT=$responseEncoding";
  
 
  
		   //echo $apicall;
		   
		   $resp = simplexml_load_file($apicall) or die("Error: Cannot create object");
		   
		   $totalEntries=$resp->paginationOutput->totalEntries;
		   $items=array();
		   
		   
		   $shipCost="";
	 $ack=$resp->ack;
	 $totalEntries=$resp->paginationOutput->totalEntries;
	 $pageNumber=$resp->paginationOutput->pageNumber;
	 $itemCount=$resp->paginationOutput->entriesPerPage;
	 $data['ack']=$ack;
	 $data['resultCount']=$totalEntries;
	 $data['pageNumber']=$pageNumber;
	 $data['itemCount']=$itemCount;
		   
		   
		   
		   
		   $j=0;
		   foreach($resp->searchResult->item as $item) {

		   
		   
		$priceInfoString='';
		$shipInfoString='';
        $link  = $item->viewItemURL;
        $title = $item->title;
		$conditions = $item->condition->conditionDisplayName;
		$sellerAcceptsReturn=$item->returnsAccepted;
		$location = $item->location;
		$topRatedItem=$item->topRatedListing;
		$listType=$item->listingInfo->listingType;
		$freeShipping=$item->shippingInfo->shippingServiceCost;
		$ShippingType=$item->shippingInfo->shippingType;
		$ExpeditedShipping=$item->shippingInfo->expeditedShipping;
		$handlingTime=$item->shippingInfo->handlingTime;
		$condtionDisplayName=$item->condition->conditionDisplayName;
		
        $price = sprintf("%01.2f", $item->sellingStatus->convertedCurrentPrice);
        $ship  = sprintf("%01.2f", $item->shippingInfo->shippingServiceCost);
        $total = sprintf("%01.2f", ((float)$item->sellingStatus->convertedCurrentPrice
                      + (float)$item->shippingInfo->shippingServiceCost));
					  
					  
		
				   
		
		
		if($title)
		{
		$items['title']=$title;
		}
		
		
		if ($item->galleryURL) {
          $picURL = $item->galleryURL;
        } else {
          $picURL = "http://pics.ebaystatic.com/aw/pics/express/icons/iconPlaceholder_96x96.gif";
        }
		
		$items['galleryURL']=$picURL;

		
		
		
		
		if($item->condition->conditionDisplayName)
		{
		  $condition=$item->condition->conditionDisplayName;
		  $items['conditionDisplayName']=$condition;
		}
		
		
		if($item->topRatedListing)
		{
		  $topRated=$item->topRatedListing;
		  $items['topRatedListing']=$topRated;
		}
		if($item->listingInfo->listingType)
		{
		  $listval=$item->listingInfo->listingType;	
         $items['listingType']=$listType;		   
		}
		
		
		if($item->returnsAccepted)
		{
		  $returnaccept=$item->returnsAccepted;
		  $shipping['returnsAccepted']=$returnaccept;
		}
		
		if($item->shippingInfo->shippingServiceCost)
		{  
		  $shippingCost=$item->shippingInfo->shippingServiceCost;
		  $items['shippingServiceCost']=$shippingCost;   		
		  }
		 
		if($item->shippingInfo->handlingTime)
		{
		 $handlingtime=$item->shippingInfo->handlingTime;
		 $shipping['handlingtime']=$handlingtime;
		}
		
		
		if($item->sellingStatus->convertedCurrentPrice)
		{
		 $price=$item->sellingStatus->convertedCurrentPrice;
		 $shippingCost="Price:".$price;
		 if($shippingCost>0)
		 {
		  $shippingCost.="(+".$shippingCost.")";
		 }
		 $items['convertedCurrentPrice']=$price;
		}
		
		
		if($item->location)
		{
		 $location=$item->location;
		 $items['location']=$location;
		}
		
		if($item->sellerInfo->sellerUserName)
		{
		 $sellername=$item->sellerInfo->sellerUserName;
		 $seller['sellerUserName']=$sellername;
		}
		
		
		if($item->sellerInfo->feedbackScore)
		{
		 $feedback=$item->sellerInfo->feedbackScore;
		 $seller['feedbackScore']=$feedback;
		}
		
		if($item->sellerInfo->positiveFeedbackPercent)
		{
		 $posfeed=$item->sellerInfo->positiveFeedbackPercent;
		 $seller['positiveFeedbackPercent']=$posfeed;
		}
		if($item->sellerInfo->feedbackRatingStar)
		{
		 $rating=$item->sellerInfo->feedbackRatingStar;
		 $seller['feedbackRatingStar']=$rating;
		}
		if($item->sellerInfo->topRatedSeller)
		{
		 $topRated=$item->sellerInfo->topRatedSeller;
		 $seller['topRatedSeller']=$topRated;
		}
		if($item->storeInfo->storeName)
		{
		 $storeName=$item->storeInfo-> storeName;
		 $seller['sellerStoreName']=$storeName;
		}
		
		if($item->storeInfo-> storeURL)
		{
		 $storeURL=$item->storeInfo-> storeURL;
		 $seller['sellerStoreURL']=$storeURL;
		}
		if($item->shippingInfo->shippingType)
		{
		  $shiptype=$item->shippingInfo->shippingType;
		  $shipping['shippingType']=$shiptype;
		}
		if($item->shippingInfo->expeditedShipping)
		{
		   $expedship=$item->shippingInfo->expeditedShipping;
		 $shipping['expeditedShipping']=$expedship;
		}
		if($item->shippingInfo->oneDayShippingAvailable)
		{
		 $oneday=$item->shippingInfo->oneDayShippingAvailable;
		 $shipping['oneDayShippingAvailable']=$oneday;
		}
		if($item->shippingInfo->handlingTime)
		{
		 $handtime=$item->shippingInfo->handlingTime;
		 $shipping['handtime']=$handtime;
		}
		if($item->shippingInfo->shipToLocations)
		{
		 $shiploc=$item->shippingInfo->shipToLocations;
		 $shipping['shipToLocations']=$shiploc;
		}
		
		
		$itemstr="item".$j;
		$data[$itemstr]['basicinfo']=$items;
		$data[$itemstr]['sellinginfo']=$seller;
		$data[$itemstr]['shippinginfo']=$shipping;
		$j++;
		
		 }
		 echo json_encode($data);
		

	
?>

