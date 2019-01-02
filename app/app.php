<?php

require './vendor/autoload.php';
use Sunra\PhpSimple\HtmlDomParser;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$log .= '';
$logTemp = '';
$logTimes = '';
$logErrors = '';
$logCurrentURL = '';

// test if the testid is defined in settings file - settings.php
if ( !array_key_exists( $testid, $testsSettings ) ) {
	die('V tabulce $testsSettings v souboru settings.php není definován test pro toto testid: "'. $testid .'".');
} else {
	$settings = $testsSettings[ $testid ];
}

// set user agent
if ( array_key_exists( 'curl_useragent', $settings ) ) {
	define('DEFAULT_USER_AGENT', $settings['curl_useragent']);
} else if ( array_key_exists( 'curl_useragent', $generalSettings ) ) {
	define('DEFAULT_USER_AGENT', $generalSettings['curl_useragent']);
} else {
	define('DEFAULT_USER_AGENT', '');
}

$logTimes = logger('Služba: ' . $testid, 'info' );
$logTimes .= logger('Začátek testu: ' . date("H:i:s Y-m-d"), 'info' );


// start robots.txt test
if ( array_key_exists( 'robotsTxtURL', $settings ) && array_key_exists( 'robotsTxtFile', $settings ) &&
	$settings['robotsTxtURL'] != '' && $settings['robotsTxtFile'] != '' ) {
		$logTemp .= logger('Testy robots.txt', 'boldInfo');
		$response = downloadURL( $settings['robotsTxtURL'] );

		// TEST if chyba 50X, tak stahnout znovu
		if ( preg_match('/^50[0-9]$/', $response['statusCode'])) {
				$response = downloadURL( $settings['robotsTxtURL'] );
		}

		// TEST if chyba 50X, tak stahnout znovu round 2
		if ( preg_match('/^50[0-9]$/', $response['statusCode'])) {
				$response = downloadURL( $settings['robotsTxtURL'] );
		}

		if ( $response['statusCode'] != '200' ) {
				$logTemp .= logger('Robots.txt na adrese ' .$settings['robotsTxtURL'].
									 ' vrátil chybu ' . $response['status'], 'error' );
		} else {
				$logTemp .= logger('Robots.txt je dostupný.');
		}

		// test if robots.txt is the same as in the file
			$robotsFileString = file_get_contents( $settings['robotsTxtFile'] );
			if ( $robotsFileString === FALSE ) {
					$logTemp .= logger('Chyba při čtení souboru "' .$settings['robotsTxtFile'].
					'". Například protože neexistuje, nebo nemáte práva jej číst.', 'error');
			} else {
        if ( $response['html'] === $robotsFileString) {
            $logTemp .= logger('Robots.txt je stejný jako testovací.');
        } else {
            $logTemp .= logger('Robots.txt na '. $settings['robotsTxtURL']
            .' je jiný, než testovací.', "error");
        }
      }

} else {
    $logTemp .= logger('Testy robots.txt nejsou definovány, takže neproběhly.', "notice");
}

// start SEOtests
if ( array_key_exists( 'testRules', $settings ) && $settings['testRules'] != '' ) {
		$logTemp .= logger('Testy typových stránek', 'boldInfo');

    $testsFileArray = file( $settings['testRules'], FILE_SKIP_EMPTY_LINES);
		if ( $testsFileArray === FALSE ) {
				$logTemp .= logger('Chyba při čtení souboru "' .$settings['testRules'].
				'". Například protože neexistuje, nebo nemáte práva jej číst.', 'error');
		} else {
        $current = array('url' => '', 'statusCode' => '', 'html' => '');


        foreach ($testsFileArray as $test) {
          $test = trim($test);
            if( $test == '' || substr( $test, 0, 1 ) === "#" ) { // skip commented and empty lines
                continue;

            } else if( substr( $test, 0, 4 ) == "http" ) { // is URL

								$response = downloadURL( $test );

								// TEST if chyba 50X, tak stahnout znovu
								if ( preg_match('/^50[0-9]$/', $response['statusCode'])) {
										$response = downloadURL( $test );
								}

								// TEST if chyba 50X, tak stahnout znovu round 2
								if ( preg_match('/^50[0-9]$/', $response['statusCode'])) {
										$response = downloadURL( $test );
								}

                $current['url'] = $test;
                $current['statusCode'] = $response['statusCode'];
                $current['html'] = $response['html'];
                $logTemp .= logger('Testy pro URL <a href="'.$test.'">'.$test. '</a>:', "boldInfo");
								$logCurrentURL = logger('Testy pro URL <a href="'.$test.'">'.$test. '</a>:', "boldInfo");

            } else if ( preg_match("/^[0-9]+$/", $test ) ) { // is status Code
                if ( $current['statusCode'] == $test ) {
                    $logTemp .= logger('Správný HTTP kód '.$test);
                } else {
                    $logTemp .= logger('Špatný HTTP kód. Má být HTTP '.
                            $test .', ale vrátil se HTTP '. $current['statusCode'], 'error');
										$logErrors .= $logCurrentURL.logger('Špatný HTTP kód. Má být HTTP '.
                            $test .', ale vrátil se HTTP '. $current['statusCode'], 'error');
                }
            } else if ( preg_match("/^(href|hrefContains|plaintext|plaintextContains|content|contentContains).*/", $test )) { // tests
                $a = explode(';;', $test);
                $html = HtmlDomParser::str_get_html( $current['html'] );
                $haystack = '';
                switch ($a[0]) {
                  case 'href':
	                    $haystack = trim ($html->find($a[1], $a[2])->href);
											$needle = $a[3];
	                    if( $haystack == $needle) {
	                        $logTemp .= logger('v "'. $a[1] . ' [' . $a[2] .']" je správně "'. $needle .'".' );
	                    } else {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
	                    }
	                    break;


									case 'hrefContains':
											$haystack = trim ($html->find($a[1], $a[2])->href);
											$needle = $a[3];
											if( strpos($haystack, $needle) !== FALSE ) {
													$logTemp .= logger('v "'. $a[1] . ' [' . $a[2] .']" správně obsahuje "'. $needle .'".' );
											} else {
													$logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
											}
											break;


                  case 'plaintext':
	                    $haystack = trim ($html->find($a[1], $a[2])->plaintext);
											$needle = $a[3];
	                    if( $haystack == $needle) {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" je správně "'. $needle .'".' );
	                    } else {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je  "'. $haystack . '"', 'error');
	                    }
	                    break;


									case 'plaintextContains':
	                    $haystack = trim ($html->find($a[1], $a[2])->plaintext);
											$needle = $a[3];
	                    if( strpos($haystack, $needle) !== FALSE ) {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" správně obsahuje "'. $needle .'".' );
	                    } else {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
	                    }
	                    break;


                  case 'content':
	                    $haystack = trim ($html->find($a[1], $a[2])->content);
											$needle = $a[3];
	                    if( $haystack == $needle) {
	                        $logTemp .= logger('v "'. $a[1] . ' [' . $a[2] .']" je správně "'. $needle .'".' );
	                    } else {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má být "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
	                    }
	                    break;


									case 'contentContains':
	                    $haystack = trim ($html->find($a[1], $a[2])->content);
											$needle = $a[3];
	                    if( strpos($haystack, $needle) !== FALSE ) {
	                        $logTemp .= logger('v "'. $a[1] . ' [' . $a[2] .']" správně obsahuje "'. $needle .'".' );
	                    } else {
	                        $logTemp .= logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
													$logErrors .= $logCurrentURL.logger('v "'. $a[1] . '[' . $a[2] .']" má obsahovat "'. $needle .'", ale na stránce je "'. $haystack . '"', 'error');
	                    }
	                    break;

                  default:
                    break;
                }
            }
        }
    }

} else {
     $logTemp .= logger('Testy nejsou definovány, takže neproběhly.', "notice");
}

$logTimes .= logger('Konec testu: ' . date("H:i:s Y-m-d"), 'info' );


$log = $logTimes.'<br><h3>Chyby:</h3>'.$logErrors.'<br><br><h3>Všechny testy:</h3>'.$logTemp.'<br><br><br>';
echo $log;

// if there is even one error, we send email
if($hasError === TRUE) {
  if ( array_key_exists( 'emailUsername', $generalSettings ) && array_key_exists( 'emailPassword', $generalSettings)
  && array_key_exists( 'email', $settings)) {

			$mail = new PHPMailer;
			$mail->CharSet = "UTF-8";
			$mail->isSMTP();
			$mail->SMTPDebug = $generalSettings['smtpDebugLevel']; // 0
			$mail->Host = $generalSettings['emailHost'];
			$mail->Port = $generalSettings['emailPort'];
			$mail->SMTPSecure = $generalSettings['emailSMTPSecure'];
			$mail->SMTPAuth = $generalSettings['emailSMTPAuth'];
			$mail->Username = $generalSettings['emailUsername'];
			$mail->Password = $generalSettings['emailPassword'];
			$mail->setFrom($generalSettings['emailFrom']);
			$mail->addReplyTo($generalSettings['emailFrom']);

			// když je více emailů
			if ( strpos( $settings['email'], ';') !== false ) {
					$emails = explode(';', $settings['email'] );
					foreach ($emails as $email) {
							$mail->addAddress($email);
					}
			}	else {
					$mail->addAddress($settings['email']);
			}
			$mail->Subject = 'SEO test se nezdařil - '. $testid;
			$mail->msgHTML($log);
			$mail->AltBody = strip_tags($log);
			$mail->smtpConnect([
					'ssl' => [
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
					]
			]);
      //send the message, check for errors
      if (!$mail->send()) {
          echo "Chyba - email nebyl poslán: " . $mail->ErrorInfo;
          if (!$mail->send()) {
              echo "Chyba - email nebyl poslán: " . $mail->ErrorInfo;
          } else {
              echo "Email odeslán!";
          }
      } else {
          echo "Email odeslán!";
      }
  }
}
