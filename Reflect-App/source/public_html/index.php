<?php

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.2
 * @since File available since v1.0.0-alpha.1
 */

require_once __DIR__ . '/../reflect/core/src/Controller.php';

$reflectController = new Reflect\Controller();

// Handle addon-specific XHR (this will target the Controller in the addon's folder)
if(isset($_POST['doAddonXhr'])) {

  $addonFolderName = filter_var($_POST['doAddonXhr'], FILTER_SANITIZE_ENCODED); // Get folder name
  unset($_POST['doAddonXhr']); // Then remove the element as we no longer need it

  require_once __DIR__ . '/../reflect/addons/' . $addonFolderName . '/src/Controller.php';

  // Convert folder name to namespace
  $addonNamespace = str_replace('-', ' ', $addonFolderName); // Replace dashes with space
  $addonNamespace = ucwords($addonNamespace); // Capitalise first letter of each word
  $addonNamespace = str_replace(' ', '', $addonNamespace); // Remove spaces

  $addonClassName = $addonNamespace . 'Addon' . '\\Controller';

  $addonController = new $addonClassName();

  if(isset($_POST['addonFunctionToRun'])) {

    $addonFunctionToRun = filter_var($_POST['addonFunctionToRun'], FILTER_SANITIZE_ENCODED);
    unset($_POST['addonFunctionToRun']); // Then remove the element as we no longer need it

    $addonController->$addonFunctionToRun();
  }

}
// Handle site (including theme and addons Settings) XHR
else if(isset($_POST['doXhr'])) {

  $action = filter_var($_POST['doXhr'], FILTER_SANITIZE_ENCODED);
  unset($_POST['doXhr']); // Then remove the element as we no longer need it

  $reflectController->handleXhr($action);
}
// Load site as usual
else {

  $reflectController->run();
}
