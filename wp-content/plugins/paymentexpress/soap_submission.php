<?php

//Live site endpoint for Reports.wsdl
// http://allergenics.xml2.co.nz/reports.svc

//Development site endpoint for Reports.wsdl
// http://allergenics-service.uat.co.nz/Reports.svc

$endpoint = dirname(__FILE__) . '/Reports.wsdl';
$objClient = new SoapClient($endpoint, array(
    'compression' => SOAP_COMPRESSION_ACCEPT,
    'trace' => 1,
    'exceptions' => true,
    'cache_wsdl' => WSDL_CACHE_NONE,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
));

//var_dump($_SESSION['allergenics_form_entry']);
//exit;
//echo "point 1";
if (!empty($_SESSION['allergenics_form_entry'])) {
    $entry = $_SESSION['allergenics_form_entry'];
//    $entry = unserialize('a:179:{s:2:"id";s:3:"137";s:7:"form_id";s:1:"1";s:12:"date_created";s:19:"2015-06-04 21:53:39";s:10:"is_starred";i:0;s:7:"is_read";i:0;s:2:"ip";s:14:"91.215.121.244";s:10:"source_url";s:50:"http://allergenicstesting.com/order-your-test-now/";s:7:"post_id";N;s:8:"currency";s:3:"NZD";s:14:"payment_status";N;s:12:"payment_date";N;s:14:"transaction_id";N;s:14:"payment_amount";N;s:14:"payment_method";N;s:12:"is_fulfilled";N;s:10:"created_by";N;s:16:"transaction_type";N;s:10:"user_agent";s:82:"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:38.0) Gecko/20100101 Firefox/38.0";s:6:"status";s:6:"active";s:3:"6.3";s:3:"Vvv";s:3:"6.6";s:3:"Sss";i:8;s:9:"777-77-77";i:9;s:9:"ka@ri.com";i:10;s:10:"1998-01-01";i:11;s:4:"Male";i:12;s:10:"2013-02-02";i:13;s:8:"An Event";s:5:"16.11";s:17:"None of the above";s:5:"17.12";s:17:"None of the above";s:5:"22.14";s:17:"None of the above";s:4:"25.4";s:17:"None of the above";s:4:"26.5";s:17:"None of the above";s:4:"30.8";s:17:"None of the above";s:4:"32.5";s:17:"None of the above";s:4:"36.8";s:17:"None of the above";i:43;s:2:"No";i:50;s:2:"No";s:4:"52.1";s:2:"No";s:4:"57.1";s:29:"Organ Stress Assesment Hidden";s:4:"57.2";s:6:"$ 0.00";s:4:"57.3";s:1:"1";i:58;s:12:"Standard|119";i:59;s:3:"258";s:4:"60.1";s:52:"Food and Environmental Sensitivity Assessment Hidden";s:4:"60.2";s:6:"$ 0.00";s:4:"60.3";s:1:"1";s:4:"62.1";s:37:"Vitamin and Mineral Assessment Hidden";s:4:"62.2";s:6:"$ 0.00";s:4:"62.3";s:1:"1";i:63;s:10:"Urgent|139";i:64;s:15:"Choose option|0";i:65;s:29:"Saint Petersburg, Russia 1221";s:4:"78.1";s:49:"Heavy Metal and Toxic Elemental Assessment Hidden";s:4:"78.2";s:6:"$ 0.00";s:4:"78.3";s:1:"1";i:79;s:15:"Choose option|0";s:4:"80.1";s:28:"Personal Consultation Hidden";s:4:"80.2";s:6:"$ 0.00";s:4:"80.3";s:1:"1";i:66;s:0:"";s:3:"6.2";s:0:"";s:3:"6.4";s:0:"";s:3:"6.8";s:0:"";i:1;s:0:"";i:67;s:0:"";i:15;s:0:"";s:4:"16.1";s:0:"";s:4:"16.2";s:0:"";s:4:"16.3";s:0:"";s:4:"16.4";s:0:"";s:4:"16.5";s:0:"";s:4:"16.6";s:0:"";s:4:"16.7";s:0:"";s:4:"16.8";s:0:"";s:4:"16.9";s:0:"";i:18;s:0:"";i:68;s:0:"";i:19;s:0:"";s:4:"17.1";s:0:"";s:4:"17.2";s:0:"";s:4:"17.3";s:0:"";s:4:"17.4";s:0:"";s:4:"17.5";s:0:"";s:4:"17.6";s:0:"";s:4:"17.7";s:0:"";s:4:"17.8";s:0:"";s:4:"17.9";s:0:"";s:5:"17.11";s:0:"";i:20;s:0:"";i:69;s:0:"";i:21;s:0:"";s:4:"22.1";s:0:"";s:4:"22.2";s:0:"";s:4:"22.3";s:0:"";s:4:"22.4";s:0:"";s:4:"22.5";s:0:"";s:4:"22.6";s:0:"";s:4:"22.7";s:0:"";s:4:"22.8";s:0:"";s:4:"22.9";s:0:"";s:5:"22.11";s:0:"";s:5:"22.12";s:0:"";s:5:"22.13";s:0:"";i:24;s:0:"";i:70;s:0:"";i:23;s:0:"";s:4:"25.1";s:0:"";s:4:"25.2";s:0:"";s:4:"25.3";s:0:"";s:4:"26.1";s:0:"";s:4:"26.2";s:0:"";s:4:"26.3";s:0:"";s:4:"26.4";s:0:"";s:4:"27.1";s:0:"";s:4:"27.2";s:0:"";s:4:"27.3";s:0:"";s:4:"27.4";s:0:"";s:4:"27.5";s:0:"";s:4:"27.6";s:0:"";s:4:"27.7";s:0:"";s:4:"27.8";s:0:"";i:29;s:0:"";i:71;s:0:"";i:28;s:0:"";s:4:"30.1";s:0:"";s:4:"30.2";s:0:"";s:4:"30.3";s:0:"";s:4:"30.4";s:0:"";s:4:"30.5";s:0:"";s:4:"30.6";s:0:"";s:4:"30.7";s:0:"";i:31;s:0:"";i:72;s:0:"";i:33;s:0:"";s:4:"32.1";s:0:"";s:4:"32.2";s:0:"";s:4:"32.3";s:0:"";s:4:"32.4";s:0:"";i:34;s:0:"";i:73;s:0:"";i:35;s:0:"";s:4:"36.1";s:0:"";s:4:"36.2";s:0:"";s:4:"36.3";s:0:"";s:4:"36.4";s:0:"";s:4:"36.5";s:0:"";s:4:"36.6";s:0:"";s:4:"36.7";s:0:"";i:40;s:0:"";i:74;s:0:"";s:4:"42.1";s:0:"";s:4:"42.2";s:0:"";s:4:"42.3";s:0:"";s:4:"42.4";s:0:"";s:4:"42.5";s:0:"";s:4:"42.6";s:0:"";s:4:"42.7";s:0:"";s:4:"42.8";s:0:"";s:4:"42.9";s:0:"";s:5:"42.11";s:0:"";s:5:"42.12";s:0:"";s:5:"42.13";s:0:"";s:5:"42.14";s:0:"";i:44;s:0:"";i:45;s:0:"";i:75;s:0:"";i:46;s:0:"";i:47;s:0:"";i:48;s:0:"";i:76;s:0:"";i:49;s:0:"";i:51;s:0:"";s:4:"52.2";s:0:"";s:4:"52.3";s:0:"";i:53;s:0:"";i:54;s:0:"";i:77;s:0:"";i:55;s:0:"";s:4:"81.1";s:0:"";}');
    $objClient = new SoapClient($endpoint, array(
        'compression' => SOAP_COMPRESSION_ACCEPT,
        'trace' => 1,
        'exceptions' => false,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
    ));

    /*
     *  ["60.1"]=>
        string(52) "Food and Environmental Sensitivity Assessment Hidden"
        [58]=>
        string(11) "Normal|1.01"

        ["57.1"]=>
        string(29) "Organ Stress Assesment Hidden"
        [64]=>
        string(18) "Urgent (+$20)|0.02"

        ["62.1"]=>
        string(37) "Vitamin and Mineral Assessment Hidden"
        [63]=>
        string(18) "Urgent (+$20)|0.02"

        ["78.1"]=>
        string(49) "Heavy Metal and Toxic Elemental Assessment Hidden"
        [79]=>
        string(15) "Choose option|0"
     */
//    var_dump($entry);
//    exit;
//echo " point 2 ";
// the 4 numbers in the following array relate to the field ids on the test entry form. The order in which they appear in the array relates to the value assigned and pushed through to the backend api. The correct values are Food = 1, Vitamin = 2, Organ = 3, Metal = 4. If this breaks, check that the numbers in the array correctly correspond with the gravity form field ids.
    foreach (array(88, 90, 89, 91) as $k => $v) {
        if (strpos($entry[$v], 'Standard') === false && strpos($entry[$v], 'Urgent') === false)
            continue;
		//var_dump($k + 1);
        $params = array(
            'username' => 'reportcr34tor',
            'password' => 'lv^nzvtA4',
            'report' => array(
				'Category' => $k + 1,
                'City' => '',
                'DateOfBirth' => $entry[10] . 'T00:00:00',
                'DateOfHairSample' => $entry[12] . 'T00:00:00',
                'Email' => $entry[9],
                'FirstName' => empty($entry['6.3']) ? '-' : $entry['6.3'],
                'IsPaid' => true,
                'IsUrgent' => strpos($entry[$v], 'Urgent') !== false,
                'PaymentType' => !empty($_SERVER["HTTP_REFERER"]) && substr($_SERVER['HTTP_REFERER'], -10) == 'ccform.php' ? 0 : 2,
                'Phone' => $entry[8],
				//'Postcode' => preg_replace('/.*(\d{4}).*/', '$1', $entry[93]),
				//postcode is mandatory so pass in placeholder
				'Postcode' => '1001',
                'StreetNameAndNo' => $entry[93],
                'Suburb' => '',
                'Surname' => empty($entry['6.6']) ? '-' : $entry['6.6']
            )
        );
//		print_r($params);
        $objResponse = $objClient->CreateReport($params);
    }
//    unset($_SESSION['allergenics_form_entry']);
}