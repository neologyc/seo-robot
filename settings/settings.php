<?php

$testsSettings = array(

	'sbazar.cz' =>
		array( 	'robotsTxtFile' => './settings/robots.txt/sbazar.txt',
						'robotsTxtURL' => 'https://www.sbazar.cz/robots.txt',
				'testRules' => './settings/tests/sbazar.txt',
				'curl_useragent' => 'SEO test',
				'email' => 'email-kam-poslat-report@seznam.cz', // středník odděluje více emailů
		 ),

	// same test as previous, but with different useragent for testing "hacks for googlebot mobile"
	'zbozi.cz-googlebot-mobile' =>
		array( 	'robotsTxtFile' => '/testfiles/robots.txt/zbozi.txt',
				'curl_useragent' => 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 ( ompatible; Googlebot/2.1; +http://www.google.com/bot.html)',
		 ),

	// sample configuration for "default.cz" test
	'default.cz' =>
		array( 	'robotsTxtFile' => '/testfiles/robots.txt/default.txt',
				'testRules' => '/testfiles/tests/default.txt',
				'curl_useragent' => 'SEO test',
		 ),

 );



/**
*	default settings
*
*/

$generalSettings = array(
				'curl_useragent' => 'SEO testing robot - made with ♥ by Jaroslav Hlavinka jaroslav@hlavinka.cz',
				'debug' => TRUE,
				'emailHost' => 'smtp.gmail.com',
				'emailPort' => 587,
				'emailSMTPSecure' => 'tls',
				'emailSMTPAuth' => true,
				'emailUsername' => 'email@gmail.com',
				'emailPassword' => 'heslodoemailu',
				'emailFrom' => 'vrazda-smrt-zabiti@seorobot.dev',
				'smtpDebugLevel' => 0, // 2 or 4 when debugging
			);
$log = '';
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ALL);
//error_reporting(E_ERROR);
