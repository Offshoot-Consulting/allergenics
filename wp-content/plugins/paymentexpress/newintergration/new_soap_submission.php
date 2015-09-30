<?php

//Live site endpoint for Reports.wsdl
// http://allergenics.xml2.co.nz/reports.svc

//Development site endpoint for Reports.wsdl
// http://allergenics-service.uat.co.nz/Reports.svc

echo "Start ;";
date_default_timezone_set('NZ');

$endpoint = dirname(__FILE__) . '/Reports.wsdl';
$objClient = new SoapClient($endpoint, array(
    'compression' => SOAP_COMPRESSION_ACCEPT,
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
));

/*
//get all the variables that are the same for every test in the order
 $IsUrgent = [does this order have the urgent processing product?]
 $first_name = [customer First name]
 $last_name = [customer Last name]
 $address_line = [first line of billing address] + ', ' + [2nd line of billing address]
 $suburb = [town/city line of billing address]
 $city = [state line of billing address]
 $postcode = [postcode line of billing address]
 $phone = [phone number]
 $email = [email]
 $dateofhairsample = [current timestamp (should be this format '2015-04-05' . 'T00:00:00')]
 $dateofbirth  = [get from custom order field (should be this format '2015-04-05' . 'T00:00:00')]
/*


// use this section to do a simple submission test 
$params = array(
            'username' => 'reportcr34tor',
            'password' => 'lv^nzvtA4',
            'report' => array(
				'Category' => 1,
                'City' => '',
                'DateOfBirth' => '1998-02-02' . 'T00:00:00',
                'DateOfHairSample' => '2015-04-05' . 'T00:00:00',
                'Email' => 'iamnew@tt.com',
                'FirstName' => 'new test',
                'IsPaid' => true,
                'IsUrgent' => false,
                'PaymentType' => 'credit_card',
                'Phone' => '54636456456',
                'Postcode' => '1001',
                'StreetNameAndNo' => '277 Broadway',
                'Suburb' => '',
                'Surname' => 'for woo'
            )
		);	
*/

//loop through each order item and do one submission  for each test item
/*foreach test do
set $category = 1 for Food test, 2 for Vitamin test, 3 for Organ test, 4 for metal test
*/
	//load the submission variables into an array	
	$params = array(
		'username' => 'reportcr34tor',
		'password' => 'lv^nzvtA4',
		'report' => array(
		'Category' => $category,
		'City' => $city,
		'DateOfBirth' => $dateofbirth,
		'DateOfHairSample' => $dateofhairsample,
		'Email' => $email,
		'FirstName' => $first_name,
		'IsPaid' => true,
		'IsUrgent' => $IsUrgent,
		'PaymentType' => 'credit_card',
		'Phone' => $phone,
		'Postcode' => $postcode,
		'StreetNameAndNo' => $address_line,
		'Suburb' => $suburb,
		'Surname' => $last_name
		)
	);			
	print_r($params);
	//submit the variables to backend
	$objResponse = $objClient->CreateReport($params);
//end loop

echo "End";  
exit;

