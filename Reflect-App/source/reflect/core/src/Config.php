<?php

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.15
 * @since File available since v1.0.0-alpha.1
 */

namespace Reflect;

require_once __DIR__ . '/../vendor/autoload.php'; // Do "composer dump-autoload" in Terminal if you make changes to the autoload property in composer.json

/**
 * @since Class available since 1.0.0-alpha.1
 */

class Config {

  public $reflectConfig; // Associative array containing the key-value sets from the site's config.json

  // Properties derived and composed from various settings...
  // CMS API routes
  public $cmsApiBaseRoute;
  public $cmsMenusApiRoute;
  public $cmsPrimaryMenuApiRoute;
  public $cmsFooterMenuApiRoute;
  public $cmsPageContentApiRoute;
  public $cmsPagesApiRoute;
  public $cmsPostsApiRoute;
  public $cmsCategoriesApiRoute;
  public $cmsUsersApiRoute;
  public $cmsSearchApiRoute;
  // Static files folder paths
  public $staticFolderPath;
  public $staticMenusFolderPath;
  public $staticPagesFolderPath;
  public $staticPostsFolderPath;


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {

    // Get all config properties from the main Reflect config.json file

    $jsonFileUrl = __DIR__ . '/../config.json';
    $json = file_get_contents($jsonFileUrl);
    $this->reflectConfig = json_decode($json, true);

    // CMS API routes
    $this->cmsApiBaseRoute = $this->reflectConfig['cmsProtocol'] . '://' . $this->reflectConfig['cmsDomain'] . '/' . $this->reflectConfig['cmsApiSegment'];
    $this->cmsMenusApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsMenusApiEndpoint'] . '/';
    $this->cmsPrimaryMenuApiRoute = $this->cmsMenusApiRoute . $this->reflectConfig['primaryMenuSlug'];
    $this->cmsFooterMenuApiRoute = $this->cmsMenusApiRoute . $this->reflectConfig['footerMenuSlug'];
    $this->cmsPageContentApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsPageApiEndpoint'];
    $this->cmsPostContentApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsPostApiEndpoint'];
    $this->cmsPagesApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsPagesApiEndpoint'];
    $this->cmsPostsApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsPostsApiEndpoint'];
    $this->cmsCategoriesApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsCategoriesApiEndpoint'];
    $this->cmsUsersApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsUsersApiEndpoint'];
    $this->cmsSearchApiRoute = $this->cmsApiBaseRoute . $this->reflectConfig['cmsSearchApiEndpoint'];

    // Static files folder paths
    $this->staticFolderPath = __DIR__ . "/../../../{$this->reflectConfig['documentRoot']}/{$this->reflectConfig['staticFolderName']}/";
    $this->staticMenusFolderPath = "{$this->staticFolderPath}{$this->reflectConfig['staticMenusFolderName']}/";
    $this->staticPagesFolderPath = "{$this->staticFolderPath}{$this->reflectConfig['staticPagesFolderName']}/";
    $this->staticPostsFolderPath = "{$this->staticFolderPath}{$this->reflectConfig['staticPostsFolderName']}/";

  }

}
