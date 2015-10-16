<?php

//Live site endpoint for Reports.wsdl
// http://allergenics.xml2.co.nz/reports.svc

//Development site endpoint for Reports.wsdl
// http://allergenics-service.uat.co.nz/Reports.svc

//echo "Start ;";
date_default_timezone_set('NZ');

$endpoint = dirname(__FILE__) . '/Reports.wsdl';
$objClient = new SoapClient($endpoint, array(
    'compression' => SOAP_COMPRESSION_ACCEPT,
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
));



// use this section to do a simple submission test 
$params = array(
            'username' => 'reportcr34tor',
            'password' => 'lv^nzvtA4',
            'report' => array(
				'Category' => $product_id,
                'City' => $city,
                'DateOfBirth' => '1901-01-01' . 'T00:00:00',
                'DateOfHairSample' => $dateofhairsample . 'T00:00:00',
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
//echo '<pre>'; print_r($params);
/*$params = array(
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
                'IsUrgent' => true,
                'PaymentType' => 'credit_card',
                'Phone' => '54636456456',
                'Postcode' => '1001',
                'StreetNameAndNo' => 'H. No 11, Radha Swami Nagar',
                'Suburb' => '',
                'Surname' => 'for woo'
            )
        );*/
//echo '<pre>'; print_r($params);

        //echo '<pre>'; print_r($params1);print_r($params);
	$objResponse = $objClient->CreateReport($params);

//end loop

//echo "End";  
//exit;

