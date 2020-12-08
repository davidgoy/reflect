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

/**
 * @since Class available since 1.0.0-alpha.1
 */

class Config {

  public $reflectFormMailerAddonConfig; // Associative array containing the key-value sets from the addon's config.json


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {

    $jsonFileUrl = __DIR__ . '/../config.json';

    $json = file_get_contents($jsonFileUrl);

    $this->reflectFormMailerAddonConfig = json_decode($json, true);
  }

}
