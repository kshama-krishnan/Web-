<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET , POST");
header('Content-type: application/json'); 

    $Keywords="";
	$MinPrice=$Maxprice="";
	$Sort=$Days=$Result=$Sell="";
	$sortvals="";
	$galleryURL=$title=$viewURL=$condition=$topRated=$listval=$returnaccept=$shipCost=$categoryName=$handlingtime=$shippingCost=$price=$location=$sellername=$feedback=$posfeed=$rating=$topRatedSeller=$storeName=$storeURL=$shiptype=$expedship=$oneday=$handtime=$shiploc="";
	$array=array();
	$Ship=array();
   $Buy=array();
   $Condition=array();
	   $url="http://svcs.eBay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=USC77bc0f-dd8c-4dbb-a998-96794897871&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML";
   $Keywords      = $_GET["keywords"];
   $Minprice = $_GET["minprice"];
   $Maxprice =$_GET["maxprice"];
   $Condition=json_decode(stripslashes($_GET['condition']));
   $Buy=json_decode(stripslashes($_GET['buy']));
   $Sell=$_GET["sell"];
   $Ship=json_decode(stripslashes($_GET['ship']));
   $Days=$_GET["days"];
   $Sort=$_GET["sort"];
   $Result=$_GET["result"];
   $page=$_GET["page"];
   $index=0;
   $ind=0;
   if(!empty($Keywords)){
      $keyurl=urlencode($Keywords);
      $url.="&keywords=$keyurl";   
   }
   
	$url.="&paginationInput.pageNumber=$page"; 
    if(!empty($Sort)){
		if($Sort=="Best Match")
          $sortvals="BestMatch";
        else if($Sort=="Price:highest first")
        $sortvals="CurrentPriceHighest";
        else if($Sort=="Price+Shipping:highest first")
        $sortvals="PricePlusShippingHighest";
        else if($Sort=="Price+Shipping:lowest first")
        $sortvals="PricePlusShippingLowest";
    $url.="&sortOrder=$sortvals";
   }
   if(!empty($Result)){
   $url.="&paginationInput.entriesPerPage=$Result";
   }
   if(!empty($Minprice))
   {
   $url.="&itemFilter($index).name=MinPrice&itemFilter($index).value=$Minprice";
   $index++;
   }
   if(!empty($Maxprice))
   {
    $url.="&itemFilter($index).name=MaxPrice&itemFilter($index).value=$Maxprice";
    $index++;
   }
   if(!empty($Days))
   {
    $url.="&itemFilter($index).name=MaxHandlingTime&itemFilter($index).value=$Days";
	$index++;
   }
   if(!empty($Sell))
   {
    $url.="&itemFilter($index).name=ReturnsAcceptedOnly&itemFilter($index).value=true";
	$index++;
   }
   if(!empty($Condition))
   {
     foreach ($Condition as $val) {
	  if($val=="New")	{
		 $array[$ind]=1000;
		 $ind++;
	    }
	  if($val=="Used"){
		$array[$ind]=3000;
		 $ind++;
	    }
		if($val=="Very Good"){
		$array[$ind]=4000;
		 $ind++;
	    }
		if($val=="Good"){
		$array[$ind]=5000;
		 $ind++;
	    }
		if($val=="Acceptable"){
		$array[$ind]=6000;
		 $ind++;
		}	
	 }
	$arraylen=count($array);
	if($arraylen>0)
    {
		$url.="&itemFilter($index).name=Condition";
		$m=0;
		foreach($array as $val)
		{
			$conval="&itemFilter[$index].value[$m]=$val";
			$url.=$conval;
			$m++;
		}
		$index++;
   }
   }
   if(!empty($Ship)) {
     foreach ($Ship as $val) {       
		if($val=="Free Shipping")
		{ $free="true";
			$freeval="&itemFilter($index).name=FreeShippingOnly&itemFilter($index).value=$free";
			$url.=$freeval;
			$index++;
		}
	   if($val=="Expedited Shipping Available"){   
			$expedited="Expedited"; 
			$expedval="&itemFilter($index).name=ExpeditedShippingType&itemFilter($index).value=$expedited";
			$url.=$expedval;
			$index++;
	  }
	}
   }
   if(!empty($Buy)){
		$buyval="&itemFilter($index).name=ListingType";
		$url.=$buyval;
		$buyind=0;
		foreach ($Buy as $val) { 
			$buystring="";
			if($val=="Buy It Now") {
			$buystring="FixedPrice";	
			}
			if($val=="Auction") {
			$buystring="Auction";
		    }
			if($val=="Classified Ads") {
			$buystring="Classified";
		    }
		    $buyval="&itemFilter[$index].value[$buyind]=$buystring";
		    $url.=$buyval;
		    $buyind++;
		}
		$index++;
   }
   $url.="&outputSelector[0]=SellerInfo&outputSelector[1]=PictureURLSuperSize&outputSelector[2]=StoreInfo";
   $xml=simplexml_load_file($url);
   if($xml->message->code!=0)
   {
   }
   else{
     $shipCost="";
	 $ack=$xml->ack;
	 $totalEntries=$xml->paginationOutput->totalEntries;
	 $pageNumber=$xml->paginationOutput->pageNumber;
	 $itemCount=$xml->paginationOutput->entriesPerPage;
	 $data['ack']=$ack;
	 $data['resultCount']=$totalEntries;
	 $data['pageNumber']=$pageNumber;
	 $data['itemCount']=$itemCount;
	   $j=0;
      foreach($xml->searchResult->item as $item)
	  {
	   $items=array();
	    if($item->galleryURL) 
		{
		  $galleryURL=$item->galleryURL;
		  $items['galleryURL']=$galleryURL;
		}
		if($item->title)
		{
		  $title=$item->title;
		  $items['title']=$title;
		}
        if($item->viewItemURL)
		{
		 $viewURL=$item->viewItemURL;
		 $items['viewItemURL']=$viewURL;
		}
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
         $items['listingType']=$listval;		   
		}
		if($item->pictureURLSuperSize)
		{
			$picturesupersize=$item->pictureURLSuperSize;
			$items['pictureURLSuperSize']=$picturesupersize;
		}
		if($item->returnsAccepted)
		{
		  $returnaccept=$item->returnsAccepted;
		  $shipping['returnsAccepted']=$returnaccept;
		}
		if($item->shippingInfo->shippingServiceCost)
		{  
		  $shipCost=$item->shippingInfo->shippingServiceCost;
		  $items['shippingServiceCost']=$shipCost;   		
		  }
		  if($item->primaryCategory->categoryName)
		  {
		   $categoryName=$item->primaryCategory->categoryName;
		   $items['categoryName']=$categoryName;
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
		 if($shipCost>0)
		 {
		  $shippingCost.="(+".$shipCost.")";
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
		 $topRatedSeller=$item->sellerInfo->topRatedSeller;
		 $seller['topRatedSeller']=$topRatedSeller;
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
		 $shipping['handlingtime']=$handtime;
		}
		if($item->shippingInfo->shipToLocations)
		{
		 $shiploc=$item->shippingInfo->shipToLocations;
		 $shipping['shipToLocations']=$shiploc;
		}
		$itemstr="item".$j;
		$data[$itemstr]['basicinfo']=$items;
		$data[$itemstr]['sellerinfo']=$seller;
		$data[$itemstr]['shippinginfo']=$shipping;
		$j++;
	  }
	  echo json_encode($data);
	 // echo $url;
   }          
?>
