<?php

/**
 * Reflect Form Mailer Addon
 * @package ReflectFormMailerAddon
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect-form-mailer
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.6
 * @since File available since v1.0.0-alpha.1
 */

namespace ReflectFormMailerAddon;

require_once __DIR__ . '/../vendor/autoload.php'; // Do "composer dump-autoload" in Terminal if you make changes to the autoload property in composer.json

use Reflect\Config as ReflectConfig;
use ReflectFormMailerAddon\Config as ReflectFormMailerAddonConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use RandomLib\Factory;

/**
 * @since Class available since 1.0.0-alpha.1
 */


class Controller {

  private $reflectConfig;
  private $reflectFormMailerAddonConfig;


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {

    $siteConfig = new ReflectConfig();
    $addonConfig = new ReflectFormMailerAddonConfig();

    $this->reflectConfig = $siteConfig->reflectConfig;
    $this->reflectFormMailerAddonConfig = $addonConfig->reflectFormMailerAddonConfig;
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function asyncGetAddonConfig() {

    $this->authenticateCsrfPreventionToken();

    $processedFormData = $this->getProcessedFormData();

    // Get addon config
    $addonConfigJson = file_get_contents(__DIR__ . "/../config.json");
    $addonConfig = json_decode($addonConfigJson, true);

    echo json_encode($addonConfig);

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function asyncSendEmail() {

    $this->authenticateCsrfPreventionToken();

    $processedFormData = $this->getProcessedFormData();

    unset($processedFormData['csrfPreventionToken']);

    $sentStatus = $this->sendEmail($processedFormData);

    echo json_encode($sentStatus);

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function authenticateCsrfPreventionToken() {

    $processedFormData = $this->getProcessedFormData();

    if(!isset($_SESSION['csrfPreventionToken']) || $_SESSION['csrfPreventionToken'] !== $processedFormData['csrfPreventionToken']) {

      echo json_encode('false');

      exit();
    }

    unset($processedFormData['csrfPreventionToken']);
  }


  //----------------------------------------------------------------------------
  /**
   * @return array $processedFormData
   */
  //----------------------------------------------------------------------------
  private function getProcessedFormData() {

    $formData = $_POST;

    $processedFormData = [];

    foreach ($formData as $inputName => $inputValue) {

      // If the input contains multiple values (e.g. checkboxes)
      if(is_array($inputValue)) {

        $inputValues = $inputValue;
        $numOfValues = count($inputValues);

        for($i = 0; $i < $numOfValues; $i++) {

          // Sanitise user input
          $inputValues[$i] = filter_var($inputValues[$i], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
          $inputValues[$i] = trim($inputValues[$i]);
          $inputValues[$i] = htmlspecialchars($inputValues[$i], ENT_NOQUOTES);

          $processedFormData[$inputName . ' ' . ($i + 1)] = $inputValues[$i];
        }
      }
      else {

        // Sanitise user input
        $inputValue = filter_var($inputValue, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $inputValue = trim($inputValue);
        $inputValue = htmlspecialchars($inputValue, ENT_QUOTES);

        $processedFormData[$inputName] = $inputValue;
      }

    }
    unset($inputValue);

    return $processedFormData;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $processedFormData
   * @return array $emailContent
   */
  //----------------------------------------------------------------------------
  private function getEmailContent($processedFormData) {

    $messageHeading = $this->reflectConfig['siteName'];

    $messageContent = '';
    $messageAltContent = '';

    foreach ($processedFormData as $inputName => $inputValue) {

      // For HTML
      $messageContent = $messageContent . "<b>{$inputName}:</b> {$inputValue}<br />";
      // Plain text
      $messageAltContent = $messageAltContent . "{$inputName}: {$inputValue}" . PHP_EOL;
    }
    unset($inputValue);

    $messageFooter = "Sent via form on {$this->reflectConfig['siteName']} ({$_SERVER['SERVER_NAME']})";

    // Email body in plain text
    $emailAltBody = $messageHeading . PHP_EOL . PHP_EOL . $messageAltContent . PHP_EOL . PHP_EOL . $messageFooter;

    // Get the email template
    $emailBody = file_get_contents(__DIR__ . '/views/email.php');

    // Replace all placeholders with data
    $emailBody = preg_replace('/{{ HEADING }}/', $messageHeading, $emailBody);
    $emailBody = preg_replace('/{{ CONTENT }}/', $messageContent, $emailBody);
    $emailBody = preg_replace('/{{ FOOTER }}/', $messageFooter, $emailBody);

    $emailContent['emailBody'] = $emailBody;
    $emailContent['emailAltBody'] = $emailAltBody;

    return $emailContent;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $processedFormData
   * @return string $csvFilePath
   */
  //----------------------------------------------------------------------------
  private function prepareEmailAttachment($processedFormData) {

    // Create the temporary CSV file that we will attach to the email...

    // Create a random CSV filename (to avoid race condition)
    $factory = new Factory();
    $generator = $factory->getLowStrengthGenerator();
    $numOfCharacters = 8;
    $possibleCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $csvFileName = $generator->generateString($numOfCharacters, $possibleCharacters);

    $csvFilePath = $csvFileName . '_' . time() . '.csv'; // Save the temporary file in "public" folder

    // Build the header
    $csvFileContentHeader = '';

    $i = 0;

    foreach ($processedFormData as $inputName => $inputValue) {

      $csvFileContentHeader = $csvFileContentHeader . $inputName;

      $i++;

      if($i < count($processedFormData)) {

        $csvFileContentHeader = $csvFileContentHeader . ',';
      }
    }

    // The comma-separated values (one line only)
    $commaSeparatedValues = implode(',', $processedFormData);

    // Build the CSV file content
    $csvFileContent = $csvFileContentHeader . PHP_EOL . $commaSeparatedValues;

    // Create the CSV file
    file_put_contents($csvFilePath, $csvFileContent);

    return $csvFilePath;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $processedFormData
   * @return string $sentStatus
   */
  //----------------------------------------------------------------------------
  private function sendEmail($processedFormData) {

    $mail = new PHPMailer(true);

    try {

      $mail->isHTML(true);
      $mail->setFrom($this->reflectFormMailerAddonConfig['fromEmailAddress'], $this->reflectFormMailerAddonConfig['fromName']);
      $mail->addAddress($this->reflectFormMailerAddonConfig['recipientEmailAddress'], $this->reflectFormMailerAddonConfig['recipientName']);

      // Attach form data to email
      if($this->reflectFormMailerAddonConfig['attachFormDataToEmail'] === 'true') {

        $csvFilePath = $this->prepareEmailAttachment($processedFormData);
        $mail->addAttachment($csvFilePath);
      }

      $mail->Subject = "Form data received from {$this->reflectConfig['siteName']} ({$_SERVER['SERVER_NAME']})";

      $emailContent = $this->getEmailContent($processedFormData);
      $mail->Body = $emailContent['emailBody'];
      $mail->AltBody = $emailContent['emailAltBody'];

      $mail->send();

      // Delete the temporary CSV file (if we created it earlier)
      if($this->reflectFormMailerAddonConfig['attachFormDataToEmail'] === 'true') {

        if(file_exists($csvFilePath)) {

          unlink($csvFilePath);
        }
      }

      $sentStatus = 'Email sent';
    }
    catch(Exception $exception) {

      $sentStatus = $mail->ErrorInfo;
    }

    return $sentStatus;
  }

}
