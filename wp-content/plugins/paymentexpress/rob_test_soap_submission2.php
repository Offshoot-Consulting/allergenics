<?php

//Live site endpoint for Reports.wsdl
// http://allergenics.xml2.co.nz/reports.svc

//Development site endpoint for Reports.wsdl
// http://allergenics-service.uat.co.nz/Reports.svc

echo "Start ;";
date_default_timezone_set('NZ');
echo "Point 1 - " . date("h:i:sa");

$endpoint = dirname(__FILE__) . '/Reports.wsdl';
$objClient = new SoapClient($endpoint, array(
    'compression' => SOAP_COMPRESSION_ACCEPT,
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
));
echo "Point - 1; "; 

//laod the submission variables into an array		
        $params = array(
            'username' => 'reportcr34tor',
            'password' => 'lv^nzvtA4',
            'report' => array(
				'Category' => 1,
                'City' => '',
                'DateOfBirth' => '1998-02-02' . 'T00:00:00',
                'DateOfHairSample' => '2015-04-05' . 'T00:00:00',
                'Email' => 'rf@tt.com',
                'FirstName' => 'bobbie',
                'IsPaid' => true,
                'IsUrgent' => false,
                'PaymentType' => 'credit_card',
                'Phone' => '54636456456',
                'Postcode' => '1001',
                'StreetNameAndNo' => '277 Broadway',
                'Suburb' => '',
                'Surname' => 'ergdgfadfa'
            )
		);	
echo "Point - 2; "; 
print_r($params);
		//submit the variables to backend
		$objResponse = $objClient->CreateReport($params);

echo "Point 2 - " . date("h:i:sa");
echo "End";  
exit;

