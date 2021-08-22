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

use Reflect\Config;

/**
 * @since Class available since 1.0.0-alpha.1
 */

class Model {

  private $config;


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {

    $this->config = new Config();
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $config
   * @return string $configChanges
   */
  //----------------------------------------------------------------------------
  public function getConfigChanges($config) {

    $url = $config['configChangesFileUrl'];

    $json = file_get_contents($url); // Perform GET request

    if($json !== false) {

      $data = json_decode($json, true);

      if(is_array($data) === true) {

        return $data;
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
   * @param string $cmsProtocol
   * @param string $cmsDomain
   * @return array|bool
   */
  //----------------------------------------------------------------------------
  public function getWpApiData($cmsProtocol, $cmsDomain) {

    $url = $cmsProtocol . '://' . $cmsDomain . '/wp-json/';

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $wpApiJson = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request

    if($wpApiJson !== false) {

      $wpApiData = json_decode($wpApiJson, true);

      if(is_array($wpApiData) === true) {

        return $wpApiData;
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
   * @param array $params
   * @return string $totalPages
   */
  //----------------------------------------------------------------------------
  public function getTotalPages($params = null) {

    $url = $this->config->cmsPagesApiRoute;

    if(isset($params['before'])) {

      $url = $url . '&before=' . $params['before'];
    }

    if(isset($params['after'])) {

      $url = $url . '&after=' . $params['after'];
    }

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $httpResponseHeader = get_headers($url . '&per_page=1', 1, stream_context_create($sslParams));
    $totalPages = $httpResponseHeader['X-WP-TotalPages'];

    return $totalPages;
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $params
   * @return string $totalPosts
   */
  //----------------------------------------------------------------------------
  public function getTotalPosts($params = null) {

    $url = $this->config->cmsPostsApiRoute;

    if(isset($params['before'])) {

      $url = $url . '&before=' . $params['before'];
    }

    if(isset($params['after'])) {

      $url = $url . '&after=' . $params['after'];
    }

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $httpResponseHeader = get_headers($url . '&per_page=1', 1, stream_context_create($sslParams));
    $totalPosts = $httpResponseHeader['X-WP-Total'];

    return $totalPosts;
  }


  //----------------------------------------------------------------------------
  /**
   * @param bool $unmodified
   * @return array $primaryMenu
   */
  //----------------------------------------------------------------------------
  public function getCmsPrimaryMenu($unmodified = false) {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsPrimaryMenuApiRoute;

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $menuItems = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request

    if($unmodified === true) {

      if($menuItems !== false || $menuItems !== 'false') {

        return json_decode($menuItems, true);
      }
      else {

        return [];
      }
    }

    $menuItems = str_replace($config['cmsProtocol'] . ':\/\/', '\/\/', $menuItems);
    $menuItems = preg_replace('/' . $config['cmsDomain'] . '/i', $_SERVER['SERVER_NAME'], $menuItems);
    $menuItems = json_decode($menuItems, true); // Decode JSON and convert to array

    if(!empty($menuItems)) {

      $primaryMenu = $this->convertToNestedMenu($menuItems);

      return $primaryMenu;
    }
    else {

      return [];
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param bool $unmodified
   * @return array $footerMenu
   */
  //----------------------------------------------------------------------------
  public function getCmsFooterMenu($unmodified = false) {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsFooterMenuApiRoute;

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $menuItems = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request

    if($unmodified === true) {

      if($menuItems !== false || $menuItems !== 'false') {

        return json_decode($menuItems, true);
      }
      else {

        return [];
      }
    }

    $menuItems = str_replace($config['cmsProtocol'] . ':\/\/', '\/\/', $menuItems);
    $menuItems = preg_replace('/' . $config['cmsDomain'] . '/i', $_SERVER['SERVER_NAME'], $menuItems);
    $footerMenu = json_decode($menuItems, true); // Decode JSON and convert to array

    if(!empty($footerMenu)) {

      return $footerMenu;
    }
    else {

      return [];
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $contentType
   * @param array $params
   * @return array $content
   */
  //----------------------------------------------------------------------------
  public function getCmsPagesOrPosts($contentType, $params = null) {

    // Pages or Posts

    if($contentType === 'pages' || $contentType === 'page') {

      $url = $this->config->cmsPagesApiRoute;
    }
    else if($contentType === 'posts' || $contentType === 'post') {

      $url = $this->config->cmsPostsApiRoute;
    }

    // Params

    if(isset($params['perPage'])) {

      $url = $url . '&per_page=' . $params['perPage'];
    }

    if(isset($params['pageNumber'])) {

      $url = $url . '&page=' . $params['pageNumber'];
    }

    if(isset($params['orderBy'])) {

      $url = $url . '&orderby=' . $params['orderBy'];
    }

    if(isset($params['order'])) {

      $url = $url . '&order=' . $params['order'];
    }

    if(isset($params['include']) && count($params['include']) > 0) {

      $ids = $params['include'];

      for($i = 0; $i < count($ids); $i++) {

        $url = $url . '&include[]=' . $ids[$i]['id'];
      }
    }

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $content = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
    $content = $this->removeCmsReferenceFromAnchorLinks($content);
    $content = json_decode($content, true); // Decode JSON and convert to array

    return $content;
  }


  //----------------------------------------------------------------------------
  /**
   * @return array
   */
  //----------------------------------------------------------------------------
  public function getCmsSearchResultsContent() {

    $config = $this->config->reflectConfig;

    if(isset($_GET['searchTerms']) && !empty($_GET['searchTerms'])) {

      $searchTerms = filter_var(trim($_GET['searchTerms']), FILTER_SANITIZE_STRING);
      $searchTerms = htmlspecialchars($searchTerms, ENT_QUOTES);

      // Remove all characters that are not alphanumerics, hyphens, comma, underscores or spaces
      $sanitisedSearchTerms = preg_replace('/[^a-zA-Z0-9-,_\s]+/i', '', $searchTerms);

      $sanitisedSearchTerms = str_replace(', ', '+', $sanitisedSearchTerms); // Replace comma and space combination with '+'
      $sanitisedSearchTerms = str_replace(' ', '+', $sanitisedSearchTerms); // Replace space with '+'
      $sanitisedSearchTerms = str_replace(',', '+', $sanitisedSearchTerms); // Replace comma with '+'

      $url = $this->config->cmsSearchApiRoute . $sanitisedSearchTerms;

      // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
      $sslParams = [
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false
        ]
      ];

      $results = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
      $results = json_decode($results, true); // Decode JSON and convert to array

      // Debug
      //exit(var_dump($results));

      if(count($results) > 0) {

        return [
          'title' => 'Search Results',
          'searchTerms' => $searchTerms,
          'results' => $results
        ];
      }
      else {

        return [
          'title' => 'Search Results',
          'searchTerms' => $searchTerms,
          'results' => []
        ];
      }
    }
    else {

      header('Location: //' . $_SERVER['SERVER_NAME']);
      exit();
    }

  }


  //----------------------------------------------------------------------------
  /**
   * @return array
   */
  //----------------------------------------------------------------------------
  public function getCmsHomePageContent() {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsPageContentApiRoute . $config['cmsHomePageSlug'];

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $content = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
    $content = $this->removeCmsReferenceFromAnchorLinks($content);
    $content = json_decode($content, true); // Decode JSON and convert to array

    // If featured image was set
    if(isset($content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      return [
        'title' => $content[0]['title']['rendered'],
        'body' => $content[0]['content']['rendered'],
        'featuredMedia' => $featuredMedia
      ];
    }
    else {
      return [];
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param int $pageNumber
   * @return array $posts
   */
  //----------------------------------------------------------------------------
  public function getCmsPostsPageContent($pageNumber = null) {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsPostsApiRoute;

    // Pagination

    $url = $url . '&per_page=' . $config['postsPerPage'];

    $paginationPageNumber = 1;
    $numOfPaginationLinks = 0;
    $paginationTotalPosts = $this->getTotalPosts();

    if($paginationTotalPosts > $config['postsPerPage']) {

      $numOfPaginationLinks = ceil($paginationTotalPosts / $config['postsPerPage']);

      // Page number (used in pagination)
      if(isset($pageNumber) && !empty($pageNumber)) {

        $url = $url . '&page=' . $pageNumber;
      }
      // If specified in query string
      else if(isset($_GET['page']) && !empty($_GET['page'])) {

        $paginationPageNumber = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);

        $url = $url . '&page=' . $paginationPageNumber;
      }
    }

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $posts = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
    $posts = $this->removeCmsReferenceFromAnchorLinks($posts);
    $posts = json_decode($posts, true); // Decode JSON and convert to array

    return $posts;
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $slug
   * @return array
   */
  //----------------------------------------------------------------------------
  public function getCmsPageContent($slug) {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsPageContentApiRoute . $slug;

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $content = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
    $content = $this->removeCmsReferenceFromAnchorLinks($content);
    $content = json_decode($content, true); // Decode JSON and convert to array

    // If featured image was set
    if(isset($content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      return [
        'title' => $content[0]['title']['rendered'],
        'body' => $content[0]['content']['rendered'],
        'featuredMedia' => $featuredMedia
      ];
    }
    else {

      return [];
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $slug
   * @return array
   */
  //----------------------------------------------------------------------------
  public function getCmsPostContent($slug) {

    $config = $this->config->reflectConfig;

    $url = $this->config->cmsPostContentApiRoute . $slug;

    // Allow Reflect to fetch data from WP site hosted locally that uses self-signed SSL certificate
    $sslParams = [
      'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
      ]
    ];

    $content = file_get_contents($url, false, stream_context_create($sslParams)); // Perform GET request
    $content = $this->removeCmsReferenceFromAnchorLinks($content);
    $content = json_decode($content, true); // Decode JSON and convert to array

    // If featured image was set
    if(isset($content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'])) {

      $featuredMedia = $content[0]['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['full']['source_url'];
    }
    else {

      $featuredMedia = null;
    }

    if(count($content) > 0) {

      return [
        'title' => $content[0]['title']['rendered'],
        'body' => $content[0]['content']['rendered'],
        'date' => $content[0]['date'],
        'modified' => $content[0]['modified'],
        //'authorName' => $authorName,
        'featuredMedia' => $featuredMedia
      ];
    }
    else {

      return [];
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param array $menuItems
   * @return array $nestedMenu
   */
  //----------------------------------------------------------------------------
  private function convertToNestedMenu($menuItems) {

    $idsOfMenuItemsToRemove = [];

    foreach ($menuItems as $key => $menuItem) {

      $menuItems[$key]['children'] = []; // Creating a new property to be added to each menu item

      // If a menu item has a parent
      if($menuItem['menu_item_parent'] !== '0') {

        $parentId = (int)$menuItem['menu_item_parent']; // Get the parent ID

        $childMenuItem = $menuItem;

        $index = 0;

        foreach ($menuItems as $key => $menuItem) {

          if($menuItem['ID'] === $parentId) {

            array_push($menuItems[$index]['children'], $childMenuItem); // Add the child menu item to its parent menu item "children" array

            array_push($idsOfMenuItemsToRemove, $childMenuItem['ID']);
          }

          $index++;
        }
        unset($menuItem);
      }

    }
    unset($menuItem);


    $nestedMenu = [];

    foreach ($menuItems as $key => $menuItem) {

      if(!in_array($menuItem['ID'], $idsOfMenuItemsToRemove)) {

        array_push($nestedMenu, $menuItem);
      }
    }
    unset($menuItem);

    return $nestedMenu;

  }


  //----------------------------------------------------------------------------
  /**
   * @param string $content
   * @return string $content
   */
  //----------------------------------------------------------------------------
  private function removeCmsReferenceFromAnchorLinks($content) {

    $config = $this->config->reflectConfig;

    $searchString = 'href=\"' . $config['cmsProtocol'] . ':\\/\\/' . $config['cmsDomain'];
    $replacementString = 'href=\"';

    $content = str_replace($searchString, $replacementString, $content);

    return $content;
  }

}
