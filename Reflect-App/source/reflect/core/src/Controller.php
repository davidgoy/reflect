<?php

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.10
 * @since File available since v1.0.0-alpha.1
 */

namespace Reflect;

require_once __DIR__ . '/../vendor/autoload.php'; // Do "composer dump-autoload" in Terminal if you make changes to the autoload property in composer.json

use Reflect\Config;
use Reflect\Model;
use Reflect\Utils;
use RandomLib\Factory;

/**
 * @since Class available since 1.0.0-alpha.1
 */

class Controller {

  private $config;
  private $model;
  private $utils;


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {

    $this->config = new Config();
    $this->model = new Model();
    $this->utils = new Utils();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function run() {

    if($this->config->reflectConfig['skipConfigCheck'] === 'false') {

      $this->checkSiteConfig();
    }

    // If site key is not set, it is likely that this is a new install
    if(!isset($this->config->reflectConfig['siteKey']) || empty($this->config->reflectConfig['siteKey'])) {

      $this->loadSetupPage();
    }

    $this->checkSpecialPageInvocation(); // Load special page if it is invoked via url

    $this->displayDocument();
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $action
   */
  //----------------------------------------------------------------------------
  public function handleAsync($action) {

    $this->authenticateCsrfPreventionToken();

    switch($action) {

      case 'generateSiteKey':

        $siteKey = $this->generateSiteKey();

        echo json_encode($siteKey);

        exit();

      case 'saveSiteKey':

        $this->asyncSaveSiteKey();

        break;

      case 'authenticateSiteKey':

        $this->asyncAuthenticateSiteKey();

        break;

      case 'checkAvailableUpdate':

        $this->asyncCheckAvailableUpdate();

        break;

      case 'installUpdate':

        $this->asyncInstallUpdate();

        break;

      case 'getWpApiInfo':

        $this->asyncGetWpApiInfo();

        break;

      case 'saveSiteSettings':

        $this->asyncSaveSettings();

        break;

      case 'saveThemeSettings':

        $this->asyncSaveSettings();

        break;

      case 'saveAddonSettings':

        $this->asyncSaveSettings();

        break;

      case 'getStaticFilesInfo':

        $this->asyncGetStaticFilesInfo();

        break;

      case 'generateStaticFiles':

        $this->asyncGenerateStaticFiles();

        break;

      case 'deleteStaticFiles':

        $this->asyncDeleteStaticFiles();

        break;

      case 'getTotalPages':

        $this->asyncGetTotalPagesOrPosts('pages');

        break;

      case 'getTotalPosts':

        $this->asyncGetTotalPagesOrPosts('posts');

        break;

      case 'getPages':

        $this->asyncGetPagesOrPosts('pages');

        break;

      case 'getPosts':

        $this->asyncGetPagesOrPosts('posts');

        break;

      case 'getMenuItems':

        $this->asyncGetMenuItems();

        break;
    }
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function setCSRFPreventionToken() {

    session_start();

    if(!isset($_SESSION['csrfPreventionToken']) || empty($_SESSION['csrfPreventionToken'])) {

      $factory = new Factory();
      $generator = $factory->getLowStrengthGenerator();
      $numOfCharacters = 8;
      $possibleCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
      $csrfPreventionToken = $generator->generateString($numOfCharacters, $possibleCharacters);

      $_SESSION['csrfPreventionToken'] = $csrfPreventionToken;
    }
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
   *
   */
  //----------------------------------------------------------------------------
  private function checkSpecialPageInvocation() {

    if($this->config->reflectConfig['skipSpecialPageInvocationCheck'] === 'false') {

      // For site...

      // Settings page
      if($this->settingsPageInvoked() === true) {

        $targetAction = 'load_settings';
        $this->loadAuthenticationPage($targetAction); // Authenticate user before allowing to proceed further
      }
      // Static Files Manager page
      else if($this->staticFilesManagerPageInvoked() === true) {

        $targetAction = 'load_static_files_manager';
        $this->loadAuthenticationPage($targetAction); // Authenticate user before allowing to proceed further
      }
      // For theme...
      else {

        $params = [];

        $params['themeFolderName'] = $this->config->reflectConfig['siteTheme'];

        if($this->settingsPageInvoked($params) === true) {

          $targetAction = 'load_settings';
          $this->loadAuthenticationPage($targetAction, $params); // Authenticate user before allowing to proceed to theme's Settings page
        }
        // For addon...
        else {

          $params = []; // Reset params array

          $listOfAddonsToLoad = $this->getListOfAddonsToLoad();

          for($i = 0; $i < count($listOfAddonsToLoad); $i++) {

            $params['addonFolderName'] = $listOfAddonsToLoad[$i];

            // Settings page
            if($this->settingsPageInvoked($params) === true) {

              $targetAction = 'load_settings';
              $this->loadAuthenticationPage($targetAction, $params); // Authenticate user before allowing to proceed to addon's Settings page
            }
          }
        }
      }
    }
  }

  //----------------------------------------------------------------------------
  /**
   * @return string $siteKey
   */
  //----------------------------------------------------------------------------
  private function generateSiteKey() {

    // For security, only generate site key if there is currently none
    if(empty($this->config->reflectConfig['siteKey'])) {

      $factory = new Factory();
      $generator = $factory->getLowStrengthGenerator();
      $numOfCharacters = 32;
      $possibleCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
      $siteKey = $generator->generateString($numOfCharacters, $possibleCharacters);

      $siteKey = 'REFLECT-' . $siteKey . '-' . time();

      return $siteKey;
    }
    else {

      return 'false';
    }

  }


  //----------------------------------------------------------------------------
  /**
   * @return array $listOfAddonsToLoad
   */
  //----------------------------------------------------------------------------
  private function getListOfAddonsToLoad() {

    $listOfAddonsToLoad = [];

    if(trim($this->config->reflectConfig['addonsToLoad']) !== '') {

      $addonsAvailable = $this->getSubfolderNames(__DIR__ . '/../../addons');

      $listOfAddonsToLoad = [];

      $tempArr = explode(',', str_replace(' ', '', trim($this->config->reflectConfig['addonsToLoad']))); // Folder names of each addon to load

      for($i = 0; $i < count($tempArr); $i++) {

        // Make sure that the addon folder actually exists
        if(in_array($tempArr[$i], $addonsAvailable)) {

          array_push($listOfAddonsToLoad, $tempArr[$i]);
        }
      }

    }

    return $listOfAddonsToLoad;
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncCheckAvailableUpdate() {

    $config = $this->config->reflectConfig;
    $currentReleasedDate = $config['released'];
    $currentReleasedDate = strtotime($currentReleasedDate); // Convert date to UNIX timestamp

    $configChanges = $this->model->getConfigChanges($config);

    $numOfChanges = count($configChanges['updates']); // Number of version changes recorded in the config changes JSON file

    if($numOfChanges > 0) {

      $releasedDates = [];

      for($i = 0; $i < $numOfChanges; $i++) {

        array_push($releasedDates, $configChanges['updates'][$i]['released']);
      }

      // Now get the latest released date
      $latestReleasedDate = max(array_map('strtotime', $releasedDates)); // This will be in UNIX timestamp

      // If the latest release is newer than the current release
      if($latestReleasedDate - $currentReleasedDate > 0) {

        $latestAvailableVersion = 'false';

        $latestReleasedDate = date('Y-m-d H:i:s', $latestReleasedDate);

        for($j = 0; $j < $numOfChanges; $j++) {

          if($configChanges['updates'][$j]['released'] == $latestReleasedDate) {

            $latestAvailableVersion = $configChanges['updates'][$j]['version'];
          }
        }

        echo json_encode($latestAvailableVersion);
      }
      // No updates available
      else {

        echo json_encode('false');
      }
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncInstallUpdate() {

    // Download ZIP and unpack files into temp folder

    $config = $this->config->reflectConfig;

    $tempFolderPath = __DIR__ . '/../../reflect-temp';
    $reflectDownloadUrl = $config['latestVersionDownloadUrl'];

    $path = parse_url($reflectDownloadUrl, PHP_URL_PATH);
    $pathParts = pathinfo($path);
    $zipFileName = $pathParts['basename'];
    $zipFilePath = $tempFolderPath . '/' . $zipFileName;

    $downloadAndExtractZipFileSuccess = $this->downloadAndExtractZipFile($reflectDownloadUrl, $zipFilePath, $tempFolderPath);

    if($downloadAndExtractZipFileSuccess === true) {

      // First, let's update the core config file...

      $newConfigFilePath = $tempFolderPath . '/source/reflect/core/config.json';

      $configFileUpdated = $this->updateConfigFile($config, $newConfigFilePath);

      if($configFileUpdated === true) {

        // Then let's update the config file of each officially bundled themes...

        $bundledThemes = explode(',', str_replace(' ', '', trim($config['bundledThemes'])));

        for($i = 0; $i < count($bundledThemes); $i++) {

          $currentThemeConfigFilePath = __DIR__ . '/../../themes/' . $bundledThemes[$i] . '/config.json';

          // Make sure that the theme exists on the server (just in case the user manually deleted it)
          if(file_exists($currentThemeConfigFilePath)) {

            $currentThemeConfig = file_get_contents($currentThemeConfigFilePath);
            $currentThemeConfig = json_decode($currentThemeConfig, true);

            $newThemeConfigFilePath = $tempFolderPath . '/source/reflect/themes/' . $bundledThemes[$i] . '/config.json';

            $themeConfigFileUpdated = $this->updateConfigFile($currentThemeConfig, $newThemeConfigFilePath);
          }
        }

        // Finally let's update the config file of each officially bundled addons...

        $bundledAddons = explode(',', str_replace(' ', '', trim($config['bundledAddons'])));

        for($j = 0; $j < count($bundledAddons); $j++) {

          $currentAddonConfigFilePath = __DIR__ . '/../../addons/' . $bundledAddons[$j] . '/config.json';

          // Make sure that the addon exists on the server (just in case the user manually deleted it)
          if(file_exists($currentAddonConfigFilePath)) {

            $currentAddonConfig = file_get_contents($currentAddonConfigFilePath);
            $currentAddonConfig = json_decode($currentAddonConfig, true);

            $newAddonConfigFilePath = $tempFolderPath . '/source/reflect/addons/' . $bundledAddons[$j] . '/config.json';

            $addonConfigFileUpdated = $this->updateConfigFile($currentAddonConfig, $newAddonConfigFilePath);
          }
        }


          // Copy the folders in place (temporarily appending "new" to the folder names)...

          // Document root CSS
          $this->utils->copyFilesAndFolders($tempFolderPath . '/source/public_html/css', __DIR__ . '/../../../' . $config['documentRoot'] . '/css_new', 0755);

          // Document root JS
          $this->utils->copyFilesAndFolders($tempFolderPath . '/source/public_html/js', __DIR__ . '/../../../' . $config['documentRoot'] . '/js_new', 0755);

          // Document root index.php
          copy($tempFolderPath . '/source/public_html/index.php', __DIR__ . '/../../../' . $config['documentRoot'] . '/index.new');

          // Core
          $this->utils->copyFilesAndFolders($tempFolderPath . '/source/reflect/core', __DIR__ . '/../../core_new', 0755);
          // Themes
          $this->utils->copyFilesAndFolders($tempFolderPath . '/source/reflect/themes', __DIR__ . '/../../themes_new', 0755);
          // Addons
          $this->utils->copyFilesAndFolders($tempFolderPath . '/source/reflect/addons', __DIR__ . '/../../addons_new', 0755);

          // Delete downloaded temporary files
          $this->utils->deleteFilesAndFolders($tempFolderPath);

          $documentRootFolderPath = __DIR__ . '/../../../' . $config['documentRoot'];
          $reflectFolderPath = __DIR__ . '/../../';

          // Debug 1/2
          //ob_start();


          // Delete all previous backup folders/files if they exists...

          if(file_exists($documentRootFolderPath . '/css_bak') === true) {
            $this->utils->deleteFilesAndFolders($documentRootFolderPath . '/css_bak');
          }

          if(file_exists($documentRootFolderPath . '/js_bak') === true) {
            $this->utils->deleteFilesAndFolders($documentRootFolderPath . '/js_bak');
          }

          if(file_exists($documentRootFolderPath . '/index.bak') === true) {
            $this->utils->deleteFilesAndFolders($documentRootFolderPath . '/index.bak');
          }

          if(file_exists($reflectFolderPath . 'core_bak') === true) {
            $this->utils->deleteFilesAndFolders($reflectFolderPath . 'core_bak');
          }

          if(file_exists($reflectFolderPath . 'addons_bak') === true) {
            $this->utils->deleteFilesAndFolders($reflectFolderPath . 'addons_bak');
          }

          if(file_exists($reflectFolderPath . 'themes_bak') === true) {
            $this->utils->deleteFilesAndFolders($reflectFolderPath . 'themes_bak');
          }


          // Change PHP's working directory in preparation for folder and/or file renaming.
          // Note: This is because we cannot use relative path (e.g. '/../../') since we are renaming the folder containing this controller!

          chdir($documentRootFolderPath);

          rename('css', 'css_bak');
          rename('css_new', 'css');

          rename('js', 'js_bak');
          rename('js_new', 'js');

          rename('index.php', 'index.bak');
          rename('index.new', 'index.php');


          chdir($reflectFolderPath);

          rename('core', 'core_bak');
          rename('core_new', 'core');

          rename('addons', 'addons_bak');
          rename('addons_new', 'addons');

          rename('themes', 'themes_bak');
          rename('themes_new', 'themes');


          chdir(__DIR__); // Restore the default PHP working directory. This may not be necessary, but we do it just to be safe.

          // // Debug 2/2
          // $outputBuffer = ob_get_contents();
          // ob_end_clean();
          // echo json_encode($outputBuffer);
          // exit();

          echo json_encode('true');

      }
      else {

        if(file_exists($tempFolderPath)) {

          // Delete downloaded temporary files
          $this->utils->deleteFilesAndFolders($tempFolderPath);
        }

        echo json_encode('Config file(s) update failed.');
      }

    }
    else {

      if(file_exists($tempFolderPath)) {

        // Delete downloaded temporary files
        $this->utils->deleteFilesAndFolders($tempFolderPath);
      }

      echo json_encode('Download and/or extraction failed.');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncSaveSiteKey() {

    // For security, only allow site key to be saved if there is currently none (such as when setting up a new Reflect site)
    if(empty($this->config->reflectConfig['siteKey']) && isset($_POST['siteKey'])) {

      $siteKey = filter_var($_POST['siteKey'], FILTER_SANITIZE_STRING);

      $this->config->reflectConfig['siteKey'] = $siteKey;

      $config = json_encode($this->config->reflectConfig, JSON_PRETTY_PRINT);

      $configFileReplaced = file_put_contents(__DIR__ . '/../config.json', $config);

      // Updated config.json successfully written
      if($configFileReplaced !== false) {

        echo json_encode('true');
      }
      else {

        echo json_encode('false');
      }
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncSaveSettings() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing settings to be saved
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      // Addon settings...
      if(isset($processedFormData['addonFolderName']) && !empty($processedFormData['addonFolderName'])) {

        $configJsonFileUrl = __DIR__ . "/../../addons/{$processedFormData['addonFolderName']}/config.json";
        $config = file_get_contents($configJsonFileUrl);
        $config = json_decode($config, true);

      }
      // Theme settings...
      else if(isset($processedFormData['themeFolderName']) && !empty($processedFormData['themeFolderName'])) {

        $configJsonFileUrl = __DIR__ . "/../../themes/{$processedFormData['themeFolderName']}/config.json";
        $config = file_get_contents($configJsonFileUrl);
        $config = json_decode($config, true);
      }
      // Site settings...
      else {

        $configJsonFileUrl = __DIR__ . '/../config.json';
        $config = $this->config->reflectConfig;
      }

      $updatedConfig = [];

      foreach ($config as $property => $value) {

        if(isset($processedFormData[$property])) {

          // If the posted form input contains multiple values (such as checkboxes, multiple select, etc.)
          if(is_array($processedFormData[$property])) {

            $processedFormData[$property] = array_filter($processedFormData[$property]); // Remove all empty elements (this is to compensate for the hidden input)

            $processedFormData[$property] = implode(',', $processedFormData[$property]);
          }

          $updatedConfig[$property] = $processedFormData[$property]; // Use value submitted by form
        }
        else {

          $updatedConfig[$property] = $value; // Use existing value from config.json
        }
      }
      unset($value);

      ksort($updatedConfig);

      $updatedConfig = json_encode($updatedConfig, JSON_PRETTY_PRINT);

      $configFileReplaced = file_put_contents($configJsonFileUrl, $updatedConfig);

      // Updated config.json successfully written
      if($configFileReplaced !== false) {

        echo json_encode('true');
      }
      else {

        echo json_encode('false');
      }
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncAuthenticateSiteKey() {

    if(isset($_POST['siteKey']) && isset($_POST['targetAction'])) {

      $siteKey = filter_var($_POST['siteKey'], FILTER_SANITIZE_STRING);
      $targetAction = filter_var($_POST['targetAction'], FILTER_SANITIZE_STRING);

      if($siteKey === $this->config->reflectConfig['siteKey']) {

        // Load Settings page
        if($targetAction === 'load_settings') {

          // ... for addon
          if(isset($_POST['addonFolderName']) && !empty($_POST['addonFolderName'])) {

            $document = $this->getAddonSettingsPage();
          }
          // ... for theme
          else if(isset($_POST['themeFolderName']) && !empty($_POST['themeFolderName'])) {

            $document = $this->getThemeSettingsPage();
          }
          // ... for site
          else {

            $document = $this->getSettingsPage();
          }

          echo json_encode($document);
        }
        // Load Static Files Manager page
        else if($targetAction === 'load_static_files_manager') {

          $document = $this->getStaticFilesManagerPage();

          echo json_encode($document);
        }
      }
      else {

        echo json_encode('false');
      }
    }
    else {

      echo json_encode('false');
    }

    exit();

  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncGetWpApiInfo() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      $wpApiData = $this->model->getWpApiData($processedFormData['cmsProtocol'], $processedFormData['cmsDomain']);

      if(is_array($wpApiData) === true) {

        echo json_encode($wpApiData);
      }
      else {

        echo json_encode('false');
      }
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncGetStaticFilesInfo() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $this->createStaticFolders(); // Create all required folders, if they don't exist

      $config = $this->config->reflectConfig;


      if($processedFormData['contentType'] === 'menu') {

        $menus = [];

        $menuFileNames = $this->getFileNamesFromFolder($this->config->staticMenusFolderPath, '.html');

        foreach ($menuFileNames as $key => $menuFileName) {

          $menuFilePath = $this->config->staticMenusFolderPath . $menuFileName;

          if(file_exists($menuFilePath)) {

            $menuFileModified = filemtime($menuFilePath);

            $menus[$key]['fileName'] = $menuFileName;
            $menus[$key]['folderPath'] = $this->config->staticMenusFolderPath;
            $menus[$key]['fileModified'] = date('d/m/Y, H:i:s', $menuFileModified);
            $menus[$key]['fileSize'] = filesize($menuFilePath) . ' bytes';
          }
        }
        unset($menuFileName);

        $files = $menus;
      }
      else if($processedFormData['contentType'] === 'page') {

        $pages = [];

        $pageFileNames = $this->getFileNamesFromFolder($this->config->staticPagesFolderPath, '.html');

        foreach ($pageFileNames as $key => $pageFileName) {

          $pageFilePath = $this->config->staticPagesFolderPath . $pageFileName;

          if(file_exists($pageFilePath)) {

            $pageFileModified = filemtime($pageFilePath);

            $pages[$key]['fileName'] = $pageFileName;
            $pages[$key]['folderPath'] = $this->config->staticPagesFolderPath;
            $pages[$key]['fileModified'] = date('d/m/Y, H:i:s', $pageFileModified);
            $pages[$key]['fileSize'] = filesize($pageFilePath) . ' bytes';
          }
        }
        unset($pageFileName);

        $files = $pages;
      }
      else if($processedFormData['contentType'] === 'post') {

        $posts = [];

        $postFileNames = $this->getFileNamesFromFolder($this->config->staticPostsFolderPath, '.html');

        foreach ($postFileNames as $key => $postFileName) {

          $postFilePath = $this->config->staticPostsFolderPath . $postFileName;

          if(file_exists($postFilePath)) {

            $postFileModified = filemtime($postFilePath);

            $posts[$key]['fileName'] = $postFileName;
            $posts[$key]['folderPath'] = $this->config->staticPostsFolderPath;
            $posts[$key]['fileModified'] = date('d/m/Y, H:i:s', $postFileModified);
            $posts[$key]['fileSize'] = filesize($postFilePath) . ' bytes';
          }
        }
        unset($postFileName);

        $files = $posts;
      }

      echo json_encode($files, JSON_PRETTY_PRINT);

    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncGenerateStaticFiles() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      $this->createStaticFolders(); // Create all required folders, if they don't exist

      $staticFilesGenerated = false;

      // Pages or posts
      if($processedFormData['contentType'] === 'page' || $processedFormData['contentType'] === 'post') {

        if(!empty($_POST['items'])) {

          $contentType = $processedFormData['contentType'];

          $params = [];

          $params['include'] = json_decode($_POST['items'], true);

          $itemContents = $this->model->getCmsPagesOrPosts($contentType, $params);

          if(count($itemContents) > 0) {

            $itemsProcessedOk = 0;

            for($i = 0; $i < count($itemContents); $i++) {

              $slug = $itemContents[$i]['slug'];
              $type = $itemContents[$i]['type'];

              if($type === 'page') {

                // Home page
                if($slug === $config['cmsHomePageSlug']) {

                  $success = $this->generateStaticHomePageFile($itemContents[$i]);

                  if($success === 'true') {

                    $itemsProcessedOk++;
                  }
                }
                // Posts page
                else if($slug === $config['cmsPostsPageSlug']) {

                  $success = $this->generateStaticPostsPageFile();

                  if($success === 'true') {

                    $itemsProcessedOk++;
                  }
                }
                // Any other page
                else {

                  $success = $this->generateStaticPageFile($slug, $itemContents[$i]);

                  if($success === 'true') {

                    $itemsProcessedOk++;
                  }
                }
              }
              else if($type === 'post') {

                $success = $this->generateStaticPostFile($slug, $itemContents[$i]);

                if($success === 'true') {

                  $itemsProcessedOk++;
                }
              }
            }

            echo json_encode($itemsProcessedOk);
          }

        }
      }
      // Menu
      else if($processedFormData['contentType'] === 'menu' && isset($processedFormData['slug'])) {

        $success = $this->generateStaticMenuFile($processedFormData['slug']);

        if($success === 'true') {

          echo json_encode(1);
        }
      }
      else {

        echo json_encode('false');
      }

    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $contentType
   */
  //----------------------------------------------------------------------------
  private function asyncGetTotalPagesOrPosts($contentType) {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      // Pages
      if($contentType === 'pages') {

        // Number of pages available to be fetched from CMS, filtered by params
        $totalPages = $this->model->getTotalPages($processedFormData);

        echo json_encode($totalPages);

      }
      // Posts
      else if($contentType === 'posts') {

        // Number of posts available to be fetched from CMS, filtered by params
        $totalPosts = $this->model->getTotalPosts($processedFormData);

        echo json_encode($totalPosts);
      }
      else {

        echo json_encode('false');
      }
    }
    else {

      echo json_encode('false');
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $contentType
   */
  //----------------------------------------------------------------------------
  private function asyncGetPagesOrPosts($contentType) {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      $pagesOrPosts = $this->model->getCmsPagesOrPosts($contentType, $processedFormData);

      echo json_encode($pagesOrPosts, JSON_PRETTY_PRINT);
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncGetMenuItems() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      if(isset($processedFormData['menuSlug']) && $processedFormData['menuSlug'] === $config['primaryMenuSlug']) {

        $menuItems = $this->model->getCmsPrimaryMenu(true);

        echo json_encode($menuItems, JSON_PRETTY_PRINT);
      }
      else if(isset($processedFormData['menuSlug']) && $processedFormData['menuSlug'] === $config['footerMenuSlug']) {

        $menuItems = $this->model->getCmsFooterMenu(true);

        echo json_encode($menuItems, JSON_PRETTY_PRINT);
      }
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function asyncDeleteStaticFiles() {

    $processedFormData = $this->getProcessedFormData();

    // For security, make sure the site key matches before allowing to progress any further
    if(isset($processedFormData['siteKey']) && $processedFormData['siteKey'] === $this->config->reflectConfig['siteKey']) {

      $config = $this->config->reflectConfig;

      // If type is page or post
      if(isset($processedFormData['contentType']) && ($processedFormData['contentType'] === 'page' || $processedFormData['contentType'] === 'post')) {

        if(!empty($_POST['items'])) {

          $items = json_decode($_POST['items'], true); // These are the selected checkbox items

          if($processedFormData['contentType'] === 'page') {

            $folderPath = $this->config->staticPagesFolderPath;
          }
          else if($processedFormData['contentType'] === 'post') {

            $folderPath = $this->config->staticPostsFolderPath;
          }

          $files = scandir($folderPath);

          $filesToDelete = []; // Static files to delete

          $totalPostsPageFiles = 0;

          for($i = 0; $i < count($items); $i++) {

            for($j = 0; $j < count($files); $j++) {

              // If item is a Posts page
              if($items[$i]['slug'] === $config['cmsPostsPageSlug']) {

                $regex = '/^.*(?=' . $config['staticPostsPageFileNumberSeparator'] . '[0-9]\.html)/'; // Capture the slug of a Posts page static file

                $isStaticPostsPageFile = preg_match($regex, $files[$j], $matches);

                // It's a static Posts page file and matches the item selected by user
                if($isStaticPostsPageFile === 1 && $matches[0] === $items[$i]['slug']) {

                  array_push($filesToDelete, $files[$j]);

                  $totalPostsPageFiles++;
                }
              }
              // Any other page or post or menu
              else {

                $regex = '/^.*(?=\.html)/'; // Capture the slug of a page static file

                $isStaticFile = preg_match($regex, $files[$j], $matches);

                // It's a static file and it matches the item selected by user
                if($isStaticFile === 1 && $matches[0] === $items[$i]['slug']) {

                  array_push($filesToDelete, $files[$j]);
                }
              }
            }
          }

          $deletionOk = false;
          $totalFilesDeleted = 0;

          //$undeletedFiles = []; // Debug only

          for($k = 0; $k < count($filesToDelete); $k++) {

            $deletionOk = unlink($folderPath . $filesToDelete[$k]);

            if($deletionOk) {

              $totalFilesDeleted++;
            }
            else {

              // Debug
              //array_push($undeletedFiles, $folderPath . $filesToDelete[$k]);
            }
          }

          // Work out total items (not files) processed...

          // If it's a Posts page
          if($totalPostsPageFiles > 0) {

            echo json_encode($totalFilesDeleted - $totalPostsPageFiles + 1);
          }
          // If it's any other page or post
          else {

            echo json_encode($totalFilesDeleted);
          }

        }
        else {

          echo json_encode('false');
        }
      }
      // If type is menu
      else if(isset($processedFormData['contentType']) && $processedFormData['contentType'] === 'menu' ) {

        if(isset($processedFormData['slug']) && !empty($processedFormData['slug'])) {

          $deletionOk = unlink($this->config->staticMenusFolderPath . $processedFormData['slug'] . '.html');

          if($deletionOk) {

            echo json_encode(1);
          }
          else {

            echo json_encode('false');
          }
        }
      }
    }
    else {

      echo json_encode('false');
    }

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $downloadUrl
   * @param string $zipFilePath
   * @param string $destinationFolderPath
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function downloadAndExtractZipFile($downloadUrl, $zipFilePath, $destinationFolderPath) {

    $config = $this->config->reflectConfig;

    // If the folder exists, we will delete it (and all its content) before re-creating
    if(file_exists($destinationFolderPath)) {

      $this->utils->deleteFilesAndFolders($destinationFolderPath);

      mkdir($destinationFolderPath, 0755);
    }
    else {

      mkdir($destinationFolderPath, 0755);
    }

    // Determine http protocol...
    // Secure (https)
    if(!empty($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] === 443) {

      $zipFileContent = fopen($downloadUrl, 'r'); // Read ZIP file
    }
    // Not secure (e.g. http on localhost)
    else {

      // Allow non-secure (http) site/host to download from secure (https) site
      $sslParams = [
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false
        ]
      ];

      $zipFileContent = fopen($downloadUrl, 'r', false, stream_context_create($sslParams)); // Read ZIP file
    }

    // If ZIP file read successfully
    if($zipFileContent !== false) {

      $fileCopied = file_put_contents($zipFilePath, $zipFileContent);

      if($fileCopied !== false) {

        // Confirm that the ZIP file we downloaded actually exists
        if(file_exists($zipFilePath)) {

          // Check if the user's server has the PHP ZipArchive extension installed
          if(class_exists('ZipArchive')) {

            $zipFile = new \ZipArchive; // Remember: The preceeding "\" is used to load a global class when namespace is used

            $zipFileReadSuccess = $zipFile->open($zipFilePath); // Open ZIP file for reading

            if($zipFileReadSuccess === true) {

              $zipFileExtractSuccess = $zipFile->extractTo($destinationFolderPath . '/'); // Extract ZIP file

              if($zipFileExtractSuccess === true) {

                $zipFile->close();

                unlink($zipFilePath); // Delete ZIP file

                return true;
              }
              else {

                return false;
              }
            }
            else {

              return false;
            }
          }
          // Use PclZip instead
          else {

            require_once __DIR__ . '/../vendor/pclzip/pclzip/pclzip.lib.php'; // For some reason I cannot get Composer's autoload to work, so I have to resort to using require_once :-(

            $zipFile = new \PclZip($zipFilePath);

            error_reporting(E_ALL & ~E_NOTICE); // Temporarily turn off PHP notice (but turn on all other error reportings) before running the below because the PclZip library currently triggers it (Notice: A non well formed numeric value encountered in ......./pclzip.lib.php on line 1797)
            $zipFileExtractSuccess = $zipFile->extract(PCLZIP_OPT_PATH, $destinationFolderPath);
            error_reporting(); // Turn error reporting back to default

            if($zipFileExtractSuccess === 0 || $zipFileExtractSuccess < 0) {

              return false;
            }
            else {

              unlink($zipFilePath); // Delete ZIP file

              return true;
            }
          }
        }
        else {

          return false;
        }
      }
      else {

        return false;
      }
    }
    else {

      return false;
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $currentConfig
   * @param string $newConfigFilePath
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function updateConfigFile($currentConfig, $newConfigFilePath) {

    $currentReleasedDate = $currentConfig['released'];
    $currentReleasedDate = strtotime($currentReleasedDate); // Convert date to UNIX timestamp

    $configChanges = $this->model->getConfigChanges($currentConfig); // Get config changes from GitHub

    $numOfChanges = count($configChanges['updates']); // Number of version changes recorded in the config changes JSON file hosted on GitHub

    if($numOfChanges > 0) {

      $updates = [];

      // Now get all available changes (i.e. updates that are later than our current existing version)

      $releasedDatesAsc = [];

      for($i = 0; $i < $numOfChanges; $i++) {

        $releasedTimestamp = strtotime($configChanges['updates'][$i]['released']); // Convert date to UNIX timestamp

        // If the update is newer than the user's current version
        if($releasedTimestamp > $currentReleasedDate) {

          array_push($releasedDatesAsc, $configChanges['updates'][$i]['released']);
        }
      }

      // As a precaution, we need to make sure that the changes are in ascending chronological order

      sort($releasedDatesAsc);

      for($i = 0; $i < count($releasedDatesAsc); $i++) {

        for($j = 0; $j < $numOfChanges; $j++) {

          if($releasedDatesAsc[$i] === $configChanges['updates'][$j]['released']) {

            array_push($updates, $configChanges['updates'][$j]);
          }
        }
      }

      // Now let's run through each version change...

      for($i = 0; $i < count($updates); $i++) {

        // First, check for removed properties and remove them accordingly...

        $propertiesRemoved = $updates[$i]['changes']['removed'];

        if(count($propertiesRemoved) > 0) {

          // See if the property we want to remove matches the existing property name...

          foreach($propertiesRemoved as $nameOfPropertyToRemove => $arbitraryValue) {

            foreach($currentConfig as $existingName => $existingValue) {

              // Matches
              if($nameOfPropertyToRemove === $existingName) {

                unset($currentConfig[$existingName]);
              }
            }
            unset($existingValue);
          }
          unset($arbitraryValue);
        }

        // Next, check for replaced property names and and rename accordingly...

        $propertiesReplaced = $updates[$i]['changes']['replaced'];

        if(count($propertiesReplaced) > 0) {

          // See if the property name we want to replace matches the existing name...

          foreach($propertiesReplaced as $nameToReplace => $replacementName) {

            foreach($currentConfig as $existingName => $existingValue) {

              // Matches
              if($nameToReplace === $existingName) {

                // Replace the property name but preserve its value

                $currentConfig[$replacementName] = $existingValue;
                unset($currentConfig[$existingName]);
              }
            }
            unset($existingValue);
          }
          unset($replacementName);
        }

        // Finally, check for added properties and add them accordingly...

        $propertiesAdded = $updates[$i]['changes']['added'];

        if(count($propertiesAdded) > 0) {

          foreach($propertiesAdded as $newPropertyName => $newPropertyDefaultValue) {

            // Make sure that the property does not already exist!
            if(!isset($currentConfig[$newPropertyName])) {

              $currentConfig[$newPropertyName] = $newPropertyDefaultValue;
            }
          }
          unset($newPropertyDefaultValue);
        }
      }


      // Now we need the new config file to mirror the updated config file

      $newConfigJson = file_get_contents($newConfigFilePath);

      if($newConfigJson !== false) {

        $newConfig = json_decode($newConfigJson, true);

        if(is_array($newConfig) === true) {

          foreach ($newConfig as $newConfigPropertyName => $newConfigPropertyValue) {

            foreach ($currentConfig as $currentConfigPropertyName => $currentConfigPropertyValue) {

              if($currentConfigPropertyName === $newConfigPropertyName) {

                $newConfig[$newConfigPropertyName] = $currentConfigPropertyValue;
              }
            }
            unset($currentConfigPropertyValue);
          }
          unset($newConfigPropertyValue);


          $latestVersionNumber = $configChanges['updates'][(0)]['version'];
          $newConfig['version'] = $latestVersionNumber; // Update the version number to the last update we applied

          $latestReleasedDate = $configChanges['updates'][(0)]['released'];
          $newConfig['released'] = $latestReleasedDate; // Update the release date to that of the last update we applied

          ksort($newConfig); // Sort associative array alphabetically by its keys (so that the properties in the config file will be ordered nicely in an ascending order)

          $newConfig = json_encode($newConfig, JSON_PRETTY_PRINT);

          $newConfigFileUpdated = file_put_contents($newConfigFilePath, $newConfig);

          // New config.json successfully updated
          if($newConfigFileUpdated !== false) {

            return true;
          }
          else {

            return false;
          }
        }
        else {

          return false;
        }
      }
      else {

        return false;
      }
    }
    else {

      return false;
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $version
   * @return array $versionParts
   */
  //----------------------------------------------------------------------------
  private function getSemVerParts($version) {


    $version = str_replace('-', '.', $version);
    $versionParts = explode('.', $version);

    // If it's a pre-release version
    if(count($versionParts) === 5) {

      $versionParts['majorVersion'] = (int)$versionParts[0];
      $versionParts['minorVersion'] = (int)$versionParts[1];
      $versionParts['patchVersion'] = (int)$versionParts[2];
      $versionParts['preReleaseLabel'] = $versionParts[3];
      $versionParts['preReleaseVersion'] = (int)$versionParts[4];
    }
    // If it's a production version
    else if(count($versionParts) === 3) {

      $versionParts['majorVersion'] = (int)$versionParts[0];
      $versionParts['minorVersion'] = (int)$versionParts[1];
      $versionParts['patchVersion'] = (int)$versionParts[2];
    }
    else {

      $versionParts = [];
    }

    return $versionParts;
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $folderPath
   * @param string $fileExtension
   * @return array $fileNames
   */
  //----------------------------------------------------------------------------
  private function getFileNamesFromFolder($folderPath, $fileExtension) {

    $folderItems = scandir($folderPath); // Items include files and subfolders

    $fileNames = [];

    for($i = 0; $i < count($folderItems); $i++) {

      // If the file name has the extension we want
      if(strpos($folderItems[$i], $fileExtension)) {

        array_push($fileNames, $folderItems[$i]);
      }
    }

    return $fileNames;
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function displayDocument() {

    if($this->config->reflectConfig['staticMode'] === 'true') {

      $document = $this->getStaticDocument();
    }
    else {

      $document = $this->getDynamicDocument();
    }

    echo $document;

    exit();
  }


  //----------------------------------------------------------------------------
  /**
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function checkSiteConfig() {

    // If site key is not set, it is likely that this is a new install
    if(!isset($this->config->reflectConfig['siteKey']) || empty($this->config->reflectConfig['siteKey'])) {

      $this->loadSetupPage();
    }
    // Settings page will be loaded if there are missing settings in config.json
    else if($this->requiredPropertiesOk() !== true) {

      $targetAction = 'load_settings';
      $this->loadAuthenticationPage($targetAction); // Authenticate user before allowing to proceed to Settings page
    }
    // Config ok
    else {

      return true;
    }

  }


  //----------------------------------------------------------------------------
  /**
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function requiredPropertiesOk() {

    $config = $this->config->reflectConfig;

    $requiredProperties = explode(',', str_replace(' ', '', trim($config['requiredProperties'])));

    if(count($requiredProperties) > 0 && !empty($requiredProperties[0])) {

      for ($i = 0; $i < count($requiredProperties); $i++) {

        $property = $requiredProperties[$i];

        if(!isset($config[$property]) || empty($config[$property])) {

          return $property; // Return the problematic property name for debug
        }
      }
    }

    return true;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $params
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function settingsPageInvoked($params = null) {

    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // For addon
    if(isset($params['addonFolderName'])) {

      $addonFolderName = $params['addonFolderName'];

      $addonSettingsPageSlug = $this->config->reflectConfig['settingsPageSlug'] . '/addons/' . $addonFolderName;

      if($urlPath === '/' . $addonSettingsPageSlug || $urlPath === '/' . $addonSettingsPageSlug . '/') {

        return true;
      }
      else {

        return false;
      }
    }
    // For theme
    else if(isset($params['themeFolderName'])) {

      $themeFolderName = $params['themeFolderName'];

      $themeSettingsPageSlug = $this->config->reflectConfig['settingsPageSlug'] . '/themes/' . $themeFolderName;

      if($urlPath === '/' . $themeSettingsPageSlug || $urlPath === '/' . $themeSettingsPageSlug . '/') {

        return true;
      }
      else {

        return false;
      }
    }
    // For site
    else {

      if(!empty($this->config->reflectConfig['settingsPageSlug']) && ($urlPath === '/' . $this->config->reflectConfig['settingsPageSlug'] || $urlPath === '/' . $this->config->reflectConfig['settingsPageSlug'] . '/')) {

        return true;
      }
      else {

        return false;
      }
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @return bool
   */
  //----------------------------------------------------------------------------
  private function staticFilesManagerPageInvoked() {

    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if(!empty($this->config->reflectConfig['sfmPageSlug']) && ($urlPath === '/' . $this->config->reflectConfig['sfmPageSlug'] || $urlPath === '/' . $this->config->reflectConfig['sfmPageSlug'] . '/')) {

      return true;
    }
    else {

      return false;
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $targetAction
   * @param array $params
   */
  //----------------------------------------------------------------------------
  private function loadAuthenticationPage($targetAction, $params = null) {

    // Variables required by the view
    $config = $this->config->reflectConfig;
    $targetAction = $targetAction; // Unnecessary but good for code readability
    $params = $params; // Unnecessary but good for code readability
    $pageTitle = 'Authentication';
    $mainContentFile = 'authentication.php';
    $pageCssFile = 'authentication.css';
    $pageJsFile = 'authentication.js';

    require_once __DIR__ . '/views/base.php';
    exit();
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function loadSetupPage() {

    $config = $this->config->reflectConfig;

    $siteKey = $this->generateSiteKey();

    if($siteKey !== 'false') {

      $pageTitle = 'Setup';
      $mainContentFile = 'setup.php';
      $pageCssFile = 'setup.css';
      $pageJsFile = 'setup.js';

      require_once __DIR__ . '/views/base.php';
    }
    else {

      $mainContentFile = 'error-404.php';
      require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/base.php";
    }

    exit();

  }


  //----------------------------------------------------------------------------
  /**
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getSettingsPage() {

    // Variables required by the view...

    $config = $this->config->reflectConfig;
    $pageTitle = 'Settings';
    $mainContentFile = 'settings.php';
    $pageCssFile = 'settings.css';
    $pageJsFile = 'settings.js';

    $addonsToLoad = explode(',', $config['addonsToLoad']);
    $addonsAvailable = $this->getSubfolderNames(__DIR__ . '/../../addons');

    $themesAvailable = $this->getSubfolderNames(__DIR__ . '/../../themes');

    // Load the view as ouput buffer, then grab the document's HTML
    ob_start();
    require_once __DIR__ . '/views/base.php';
    $document = ob_get_contents();
    ob_end_clean();

    return $document;
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getStaticFilesManagerPage() {

    // Variables required by the view
    $config = $this->config->reflectConfig;
    $pageTitle = 'Static Files Manager';
    $mainContentFile = 'static-files-manager.php';
    $pageCssFile = 'static-files-manager.css';
    $pageJsFile = 'static-files-manager.js';

    // Load the view as ouput buffer, then grab the document's HTML
    ob_start();
    require_once __DIR__ . '/views/base.php';
    $document = ob_get_contents();
    ob_end_clean();

    return $document;
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getAddonSettingsPage() {

    $addonFolderName = filter_var($_POST['addonFolderName'], FILTER_SANITIZE_STRING);

    // Variables required by the view

    // Addon config
    $addonConfigJson = file_get_contents(__DIR__ . "/../../addons/{$addonFolderName}/config.json");
    $addonConfig = json_decode($addonConfigJson, true);
    // Site config
    $config = $this->config->reflectConfig;

    ob_start();
    require_once __DIR__ . "/../../addons/{$addonFolderName}/src/views/settings.php";
    $document = ob_get_contents();
    ob_end_clean();

    return $document;
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getThemeSettingsPage() {

    $themeFolderName = filter_var($_POST['themeFolderName'], FILTER_SANITIZE_STRING);

    // Variables required by the view

    // Theme config
    $themeConfigJson = file_get_contents(__DIR__ . "/../../themes/{$themeFolderName}/config.json");
    $themeConfig = json_decode($themeConfigJson, true);
    // Site config
    $config = $this->config->reflectConfig;

    ob_start();
    require_once __DIR__ . "/../../themes/{$themeFolderName}/src/views/settings.php";
    $document = ob_get_contents();
    ob_end_clean();

    return $document;
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

        $processedFormData[$inputName] = [];

        $inputValues = $inputValue;
        $numOfValues = count($inputValues);

        for($i = 0; $i < $numOfValues; $i++) {

          // Sanitise user input
          $inputValues[$i] = filter_var($inputValues[$i], FILTER_SANITIZE_STRING);
          $inputValues[$i] = trim($inputValues[$i]);
          $inputValues[$i] = htmlspecialchars($inputValues[$i], ENT_QUOTES);

          array_push($processedFormData[$inputName], $inputValues[$i]);
        }
      }
      else {

        // Sanitise user input
        $inputValue = filter_var($inputValue, FILTER_SANITIZE_STRING);
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
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getDynamicDocument() {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    $slug = $this->getCurrentPageSlug();

    $primaryMenu = $this->model->getCmsPrimaryMenu();
    $footerMenu = $this->model->getCmsFooterMenu();

    // If site is Under Maintenance
    if($config['underMaintenanceMode'] === 'true') {

      // If no exempted IP is specified or if the specified IP does not match user IP
      if(empty($config['underMaintenanceExemptedIp']) || $config['underMaintenanceExemptedIp'] !== $_SERVER['REMOTE_ADDR']) {

        // Slugs that we want affected by Under Maintenance mode
        $underMaintenanceAffectedSlugs = explode(',', str_replace(' ', '', trim($config['underMaintenanceAffectedSlugs'])));

        // Slugs that we want exempted from Under Maintenance mode
        $underMaintenanceExemptedSlugs = explode(',', str_replace(' ', '', trim($config['underMaintenanceExemptedSlugs'])));

        // If the slug is NOT found in the list of Under Maintenance slugs
        if(!empty($config['underMaintenanceAffectedSlugs']) && in_array($slug, $underMaintenanceAffectedSlugs) === false) {

          // Do nothing
        }
        // If the page or post is exempted from Under Maintenance mode
        else if(!empty($config['underMaintenanceExemptedSlugs']) && in_array($slug, $underMaintenanceExemptedSlugs) === true) {

          // Do nothing
        }
        else {

          if(!empty($config['cmsUnderMaintenancePageSlug'])) {

            $slug = $config['cmsUnderMaintenancePageSlug'];
          }
          else {

            $slug = false;
          }

          // If the entire site will be put Under Maintenance without any exception
          if(empty($config['underMaintenanceAffectedSlugs']) && empty($config['underMaintenanceExemptedSlugs'])) {

            // Don't display menus
            $primaryMenu = null;
            $footerMenu = null;
          }
        }
      }
    }

    // Under maintenance page (if no slug was specified)
    if($config['underMaintenanceMode'] === 'true' && $slug === false) {

      $mainContentFile = 'under-maintenance.php';
    }
    // Search page
    else if((strpos($_SERVER['REQUEST_URI'], "/?searchTerms=") !== false || strpos($_SERVER['REQUEST_URI'], "?searchTerms=") !== false) && $config['underMaintenanceMode'] === 'false') {

      $mainContentFile = 'search.php';
      $cmsContent = $this->model->getCmsSearchResultsContent();
    }
    // Home page
    else if($slug === '' || $slug === $config['cmsHomePageSlug']) {

      $mainContentFile = 'home.php';
      $cmsContent = $this->model->getCmsHomePageContent();
    }
    // Posts page
    else if($slug === $config['cmsPostsPageSlug']) {

      $mainContentFile = 'posts.php';
      $cmsContent = $this->model->getCmsPageContent($slug);
      $cmsPosts = $this->model->getCmsPostsPageContent();

      // Pagination variables required by template
      $paginationTotalPosts = $this->model->getTotalPosts();
      $numOfPaginationLinks = ceil($paginationTotalPosts / $config['postsPerPage']);

      if(isset($_GET['page'])) {

        $paginationPageNumber = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
      }
      else {

        $paginationPageNumber = 1;
      }

      $pageFirstItem = ($config['postsPerPage'] * $paginationPageNumber) + 1 - $config['postsPerPage'];
      $pageLastItem = ($config['postsPerPage'] * $paginationPageNumber) - ($config['postsPerPage'] - count($cmsPosts));
      $postsPageUrl = '//' . $_SERVER['SERVER_NAME'] . '/' . $config['cmsPostsPageSlug'] . '/';
    }
    // Any other page or post
    else {

      // Any page
      $mainContentFile = 'page.php';
      $cmsContent = $this->model->getCmsPageContent($slug);

      // Any post
      if(empty(count($cmsContent))) {

        $mainContentFile = 'post.php';
        $cmsContent = $this->model->getCmsPostContent($slug);

        // Requested page/post does not exists
        if(empty(count($cmsContent))) {

          $mainContentFile = 'error-404.php';
        }
      }
    }

    // Site addons
    $addons = $this->getAddons();

    ob_start();
    require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/base.php";
    $document = ob_get_contents();
    ob_end_clean();

    return $document;
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $document
   */
  //----------------------------------------------------------------------------
  private function getStaticDocument() {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    // Primary menu
    $primaryMenuFilePath = $this->config->staticMenusFolderPath . 'primary-menu.html';
    $primaryMenuHtml = (file_exists($primaryMenuFilePath) === true ? file_get_contents($primaryMenuFilePath) : '');
    // Footer menu
    $footerMenuFilePath = $this->config->staticMenusFolderPath . 'footer-menu.html';
    $footerMenuHtml = (file_exists($footerMenuFilePath) === true ? file_get_contents($footerMenuFilePath) : '');

    // Main content...

    // Default to 404 error (when requested static file does not exists)
    ob_start();
    require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/error-404.php";
    $mainHtml = ob_get_contents();
    ob_end_clean();

    $slug = $this->getCurrentPageSlug();

    // If site is Under Maintenance
    if($config['underMaintenanceMode'] === 'true') {

      // If no exempted IP is specified or if the specified IP does not match user IP
      if(empty($config['underMaintenanceExemptedIp']) || $config['underMaintenanceExemptedIp'] !== $_SERVER['REMOTE_ADDR']) {

        // Slugs that we want affected by Under Maintenance mode
        $underMaintenanceAffectedSlugs = explode(',', str_replace(' ', '', trim($config['underMaintenanceAffectedSlugs'])));

        // Slugs that we want exempted from Under Maintenance mode
        $underMaintenanceExemptedSlugs = explode(',', str_replace(' ', '', trim($config['underMaintenanceExemptedSlugs'])));

        // If the slug is NOT found in the list of Under Maintenance slugs
        if(!empty($config['underMaintenanceAffectedSlugs']) && in_array($slug, $underMaintenanceAffectedSlugs) === false) {

          // Do nothing
        }
        // If the page or post is exempted from Under Maintenance mode
        else if(!empty($config['underMaintenanceExemptedSlugs']) && in_array($slug, $underMaintenanceExemptedSlugs) === true) {

          // Do nothing
        }
        else {

          if(!empty($config['cmsUnderMaintenancePageSlug'])) {

            $slug = $config['cmsUnderMaintenancePageSlug'];
          }
          else {

            $slug = false;
          }

          // If the entire site will be put Under Maintenance without any exception
          if(empty($config['underMaintenanceAffectedSlugs']) && empty($config['underMaintenanceExemptedSlugs'])) {

            // Don't display menus
            $primaryMenuHtml = '';
            $footerMenuHtml = '';
          }
        }
      }
    }

    // Under maintenance page (if no slug was specified)
    if($config['underMaintenanceMode'] === 'true' && $slug === false) {

      ob_start();
      require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/under-maintenance.php";
      $mainHtml = ob_get_contents();
      ob_end_clean();
    }
    // Search page
    else if((strpos($_SERVER['REQUEST_URI'], "/?searchTerms=") !== false || strpos($_SERVER['REQUEST_URI'], "?searchTerms=") !== false) && $config['underMaintenanceMode'] === 'false') {

      $mainHtml = $this->getSearchPageMainHtml();
    }
    // Home page
    else if($slug === '' || $slug === $config['cmsHomePageSlug']) {

      $homePageFilePath = $this->config->staticPagesFolderPath . $config['cmsHomePageSlug'] . '.html';

      if(file_exists($homePageFilePath)) {

        $mainHtml = file_get_contents($homePageFilePath);
      }
    }
    // Posts page
    else if($slug === $config['cmsPostsPageSlug']) {

      if(isset($_GET['page'])) {

        $paginationPageNumber = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
      }
      else {

        $paginationPageNumber = 1;
      }

      $postsPageFilePath = $this->config->staticPagesFolderPath . $config['cmsPostsPageSlug'] . $config['staticPostsPageFileNumberSeparator'] . $paginationPageNumber . '.html';

      if(file_exists($postsPageFilePath)) {

        $mainHtml = file_get_contents($postsPageFilePath);
      }

    }
    // Any other page or post
    else {

      $pageFilePath = $this->config->staticPagesFolderPath . $slug . '.html';
      $postFilePath = $this->config->staticPostsFolderPath . $slug . '.html';

      if(file_exists($pageFilePath)) {

        $mainHtml = file_get_contents($pageFilePath);
      }
      // Post
      else if(file_exists($postFilePath)) {

        $mainHtml = file_get_contents($postFilePath);
      }
    }

    // Site addons
    $addons = $this->getAddons();

    ob_start();
    require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/base.php";
    $document = ob_get_contents();
    ob_end_clean();

    // Insert html parts into document...

    $document = str_replace('<!--{{ primary-menu }}-->', $primaryMenuHtml, $document);
    $document = str_replace('<!--{{ footer-menu }}-->', $footerMenuHtml, $document);
    $document = str_replace('<!--{{ main }}-->', $mainHtml, $document);

    return $document;
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $currentPageSlug
   */
  //----------------------------------------------------------------------------
  private function getCurrentPageSlug() {

    $currentPageSlug = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $currentPageSlug = filter_var(trim($currentPageSlug), FILTER_SANITIZE_STRING);
    $currentPageSlug = str_replace('/', '', $currentPageSlug);

    return $currentPageSlug;
  }


  //----------------------------------------------------------------------------
  /**
   * @return array $addons
   */
  //----------------------------------------------------------------------------
  private function getAddons() {

    $slug = $this->getCurrentPageSlug();

    $config = $this->config->reflectConfig;

    $addons = [];

    $listOfAddonsToLoad = $this->getListOfAddonsToLoad();

    for($i = 0; $i < count($listOfAddonsToLoad); $i++) {

      $addonFolderName = $listOfAddonsToLoad[$i];

      // Get addon config
      $addonConfigJson = file_get_contents(__DIR__ . "/../../addons/{$addonFolderName}/config.json");
      $addonConfig = json_decode($addonConfigJson, true);

      // For the home page
      if($slug === '' || $slug === $config['cmsHomePageSlug']) {

        if($addonConfig['runOnHomePage'] === 'true') {

          array_push($addons, $this->getAddonFiles($addonConfig));
        }
      }
      // For all other pages
      else {

        if(!empty($addonConfig['slugsToRun'])) {

          $slugsToRun = explode(',', str_replace(' ', '', trim($addonConfig['slugsToRun'])));

          for($j = 0; $j < count($slugsToRun); $j++) {

            if(strpos($slug, $slugsToRun[$j]) !== false) {

              array_push($addons, $this->getAddonFiles($addonConfig));
            }
          }
        }
        else if(!empty($addonConfig['slugsToExclude'])) {

          $slugsToExclude = explode(',', str_replace(' ', '', trim($addonConfig['slugsToExclude'])));

          if(!in_array($slug, $slugsToExclude)) {

            array_push($addons, $this->getAddonFiles($addonConfig));
          }
        }
        else {

          array_push($addons, $this->getAddonFiles($addonConfig));
        }

      }


    }

    return $addons;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $addonConfig
   * @return array $addon
   */
  //----------------------------------------------------------------------------
  private function getAddonFiles($addonConfig) {

    $config = $this->config->reflectConfig;

    $addon = [];

    $addon['name'] = $addonConfig['addonName'];

    $addon['css'] = file_get_contents(__DIR__ . "/../../addons/{$addonConfig['addonFolderName']}/src/css/main.css");

    if($config['olderBrowsersSupport'] === 'true') {

      $addon['js'] = file_get_contents(__DIR__ . "/../../addons/{$addonConfig['addonFolderName']}/src/js/transpiled/main.js");
    }
    else {

      $addon['js'] = file_get_contents(__DIR__ . "/../../addons/{$addonConfig['addonFolderName']}/src/js/main.js");
    }

    return $addon;
  }


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  private function createStaticFolders() {

    // Create the folders, if they do not yet exists

    if(!file_exists($this->config->staticFolderPath)) {

      mkdir($this->config->staticFolderPath, 0755);
    }

    if(!file_exists($this->config->staticMenusFolderPath)) {

      mkdir($this->config->staticMenusFolderPath, 0755);
    }

    if(!file_exists($this->config->staticPagesFolderPath)) {

      mkdir($this->config->staticPagesFolderPath, 0755);
    }

    if(!file_exists($this->config->staticPostsFolderPath)) {

      mkdir($this->config->staticPostsFolderPath, 0755);
    }

  }


  //----------------------------------------------------------------------------
  /**
   * @param string $menuSlug
   * @return string
   */
  //----------------------------------------------------------------------------
  private function generateStaticMenuFile($menuSlug) {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    if($menuSlug === $config['primaryMenuSlug']) {

      $primaryMenu = $this->model->getCmsPrimaryMenu();

      if(!empty($primaryMenu)) {

        ob_start();
        require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/partials/primary-menu.php";
        $primaryMenuHtml = ob_get_contents();
        ob_end_clean();

        file_put_contents($this->config->staticMenusFolderPath. $config['primaryMenuSlug'] . '.html', $primaryMenuHtml);

        return 'true';
      }
      else {

        return 'false';
      }
    }
    else if($menuSlug === $config['footerMenuSlug']) {

      $footerMenu = $this->model->getCmsFooterMenu();

      if(!empty($footerMenu)) {

        ob_start();
        require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/partials/footer-menu.php";
        $footerMenuHtml = ob_get_contents();
        ob_end_clean();

        file_put_contents($this->config->staticMenusFolderPath. $config['footerMenuSlug'] . '.html', $footerMenuHtml);

        return 'true';
      }
      else {

        return 'false';
      }
    }
    else {

      return 'false';
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $content
   * @return string
   */
  //----------------------------------------------------------------------------
  private function generateStaticHomePageFile($content) {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    $templateFile = 'home.php';

    // If featured image was set
    if(isset($content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      $cmsContent = [
        'title' => $content['title']['rendered'],
        'body' => $content['content']['rendered'],
        'featuredMedia' => $featuredMedia
      ];
    }
    else {

      $cmsContent = [];
    }

    if(!empty($cmsContent)) {

      ob_start();
      require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/{$templateFile}";
      $contentHtml = ob_get_contents();
      ob_end_clean();

      file_put_contents($this->config->staticPagesFolderPath. "{$config['cmsHomePageSlug']}.html", $contentHtml);

      return 'true';
    }
    else {

      return 'false';
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @return string
   */
  //----------------------------------------------------------------------------
  private function generateStaticPostsPageFile() {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    $templateFile = 'posts.php';

    $cmsContent = $this->model->getCmsPageContent($config['cmsPostsPageSlug']);

    if(!empty($cmsContent)) {

      $totalPosts = $this->model->getTotalPosts();

      if($totalPosts > 0) {

        $numOfPaginationLinks = ceil($totalPosts / $config['postsPerPage']);

        // Create one or more Posts pages, depending on the number of total posts and posts per page (set by user)
        for($paginationPageNumber = 1; $paginationPageNumber <= $numOfPaginationLinks; $paginationPageNumber++) {

          $cmsPosts = $this->model->getCmsPostsPageContent($paginationPageNumber);

          // Pagination variables required by template
          $paginationTotalPosts = $totalPosts;
          $numOfPaginationLinks = $numOfPaginationLinks;
          $pageFirstItem = ($config['postsPerPage'] * $paginationPageNumber) + 1 - $config['postsPerPage'];
          $pageLastItem = ($config['postsPerPage'] * $paginationPageNumber) - ($config['postsPerPage'] - count($cmsPosts));
          $postsPageUrl = '//' . $_SERVER['SERVER_NAME'] . '/' . $config['cmsPostsPageSlug'] . '/';

          ob_start();
          require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/{$templateFile}";
          $mainContentHtml = ob_get_contents();
          ob_end_clean();

          file_put_contents($this->config->staticPagesFolderPath. "{$config['cmsPostsPageSlug']}{$config['staticPostsPageFileNumberSeparator']}{$paginationPageNumber}.html", $mainContentHtml);
        }
      }
      else {

        ob_start();
        require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/{$templateFile}";
        $mainContentHtml = ob_get_contents();
        ob_end_clean();

        file_put_contents($this->config->staticPagesFolderPath. "{$config['cmsPostsPageSlug']}.html", $mainContentHtml);
      }

      return 'true';
    }
    else {

      return 'false';
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $slug
   * @param array $content
   * @return string
   */
  //----------------------------------------------------------------------------
  private function generateStaticPageFile($slug, $content) {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    $templateFile = 'page.php';

    // If featured image was set
    if(isset($content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      $cmsContent = [
        'title' => $content['title']['rendered'],
        'body' => $content['content']['rendered'],
        'featuredMedia' => $featuredMedia
      ];
    }
    else {

      $cmsContent = [];
    }

    if(!empty($cmsContent)) {

      ob_start();
      require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/{$templateFile}";
      $contentHtml = ob_get_contents();
      ob_end_clean();

      file_put_contents($this->config->staticPagesFolderPath. "{$slug}.html", $contentHtml);

      return 'true';
    }
    else {

      return 'false';
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $slug
   * @param array $content
   * @return string
   */
  //----------------------------------------------------------------------------
  private function generateStaticPostFile($slug, $content) {

    $config = $this->config->reflectConfig;

    // Get theme config
    $themeConfigJsonFileUrl = __DIR__ . '/../../themes/' . $config['siteTheme'] . '/config.json';
    $themeConfigJson = file_get_contents($themeConfigJsonFileUrl);
    $themeConfig = json_decode($themeConfigJson, true);

    $templateFile = 'post.php';

    // If featured image was set
    if(isset($content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      $cmsContent = [
        'title' => $content['title']['rendered'],
        'body' => $content['content']['rendered'],
        'date' => $content['date'],
        'modified' => $content['modified'],
        //'authorName' => $authorName,
        'featuredMedia' => $featuredMedia
      ];
    }
    else {

      $cmsContent = [];
    }

    if(!empty($cmsContent)) {

      ob_start();
      require __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/{$templateFile}";
      $contentHtml = ob_get_contents();
      ob_end_clean();

      file_put_contents($this->config->staticPostsFolderPath. "{$slug}.html", $contentHtml);

      return 'true';
    }
    else {

      return 'false';
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @return string $mainHtml
   */
  //----------------------------------------------------------------------------
  private function getSearchPageMainHtml() {

    $searchTerms = '';
    $results = [];

    if(isset($_GET['searchTerms']) && !empty($_GET['searchTerms'])) {

      $searchTerms = filter_var(trim($_GET['searchTerms']), FILTER_SANITIZE_STRING);
      $searchTerms = htmlspecialchars($searchTerms, ENT_QUOTES);

      $matchedPageSlugs = $this->searchStaticFilesForKeywords($searchTerms, $this->config->staticPagesFolderPath);
      $matchedPostSlugs = $this->searchStaticFilesForKeywords($searchTerms, $this->config->staticPostsFolderPath);

      $matchedSlugs = array_merge($matchedPageSlugs, $matchedPostSlugs);

      if(count($matchedSlugs) > 0) {

        for($i = 0; $i < count($matchedSlugs); $i++) {

          $slug = $matchedSlugs[$i];

          $title = preg_replace('/(-\d*)$/', '', $slug); // Remove any "-n" (where n is a number) if it occurs at the end of the string
          $title = str_replace('-', ' ', $title); // Replace hyphens with spaces
          $title = ucwords(strtolower($title));

          $result = [
            'title' => $title,
            'url' => $_SERVER['SERVER_NAME'] . '/' . $slug
          ];

          array_push($results, $result);
        }
      }
    }

    $config = $this->config->reflectConfig;

    $cmsContent = [
      'searchTerms' => $searchTerms,
      'results' => $results
    ];

    ob_start();
    require_once __DIR__ . "/../../themes/{$config['siteTheme']}/src/templates/search.php";
    $mainHtml = ob_get_contents();
    ob_end_clean();

    return $mainHtml;
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $searchTerms
   * @param string $folderPath
   * @return array $matchedSlugs
   */
  //----------------------------------------------------------------------------
  private function searchStaticFilesForKeywords($searchTerms, $folderPath) {

    $config = $this->config->reflectConfig;

    $processedSearchTerms = preg_replace('/[^a-zA-Z0-9-,_\s]+/i', '', $searchTerms); // Remove all characters that are not alphanumerics, hyphens, comma, underscores or spaces
    $processedSearchTerms = trim($processedSearchTerms);
    $processedSearchTerms = str_replace(', ', ',', $processedSearchTerms); // Replace comma-and-space combo wih just comma

    $fileNames = $this->getFileNamesFromFolder($folderPath, '.html');

    $keywordsSets = explode(',', $processedSearchTerms); // Use comma to break the search terms into sets

    $matchedSlugs = [];

    for($i = 0; $i < count($fileNames); $i++) {

      $fileContent = file_get_contents($folderPath . $fileNames[$i]);

      for($j = 0; $j < count($keywordsSets); $j++) {

        $found = preg_match('/(' . $keywordsSets[$j] . ')/i', $fileContent);

        if($found === 1) {

          $slug = str_replace('.html', '', $fileNames[$i]);
          $slug = preg_replace('/(' . $config['staticPostsPageFileNumberSeparator'] . '\d*)$/', '', $slug); // If it's a Posts page file, then remove the page number separator and the page number

          // We don't want to include the Posts page
          if($slug !== $config['cmsPostsPageSlug']) {

            array_push($matchedSlugs, $slug);
          }
        }
      }
    }

    return $matchedSlugs;
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $folderPath
   * @return array $subfolderNames
   */
  //----------------------------------------------------------------------------
  private function getSubfolderNames($folderPath) {

    $subfolderNames = [];

    // Scan for subfolders
    foreach(new \DirectoryIterator($folderPath) as $scanResult) {

      if(!$scanResult->isDot() && $scanResult->isDir()) {

        array_push($subfolderNames, $scanResult->getFilename());
      }
    }
    unset($scanResult);

    return $subfolderNames;
  }

}
