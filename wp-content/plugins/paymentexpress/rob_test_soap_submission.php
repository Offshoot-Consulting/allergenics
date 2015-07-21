<?php

//Live site endpoint for Reports.wsdl
// http://allergenics.xml2.co.nz/reports.svc

//Development site endpoint for Reports.wsdl
// http://allergenics-service.uat.co.nz/Reports.svc

echo "Start ;";
$endpoint = dirname(__FILE__) . '/Reports.wsdl';
$objClient = new SoapClient($endpoint, array(
    'compression' => SOAP_COMPRESSION_ACCEPT,
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
));
echo 'Point - 1; '; 

//load the submission variables into an array		
        $params = array(
            'username' => 'reportcr34tor',
            'password' => 'lv^nzvtA4',
            'report' => array(
				'Category' => 1,
                'City' => '',
                'DateOfBirth' => '2000-06-05' . 'T00:00:00',
                'DateOfHairSample' => '2000-06-05' . 'T00:00:00',
                'Email' => 'test@test2.com',
                'FirstName' => 'testqq',
                'IsPaid' => true,
                'IsUrgent' => false,
                'PaymentType' => 'credit_card',
                'Phone' => '098745682',
                'Postcode' => '1001',
                'StreetNameAndNo' => '25 Brown St, Ponsonby',
                'Suburb' => '',
                'Surname' => 'test'
            )
		);	
echo 'Point - 2; '; 
print_r($params);
		
		$objResponse = $objClient->CreateReport($params);
		//echo $objResponse;

echo 'End';  


exit;
?>