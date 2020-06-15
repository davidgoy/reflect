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

namespace Reflect;

/**
 * @since Class available since 1.0.0-alpha.1
 */

class Utils {


  //----------------------------------------------------------------------------
  /**
   *
   */
  //----------------------------------------------------------------------------
  public function __construct() {


  }


  //----------------------------------------------------------------------------
  /**
   * @param string $source
   * @param string $destination
   * @param string $folderPermission
   */
  //----------------------------------------------------------------------------
  public function copyFilesAndFolders($source, $destination, $folderPermission) {

    // Look for files and/or folders
    $items = scandir($source);

    // At least one or more files/folders found
    if(count($items) > 0) {

      foreach ($items as $key => $item) {

        // If the item is a valid folder (e.g. not dots or symbolic links)
        if(is_dir($source . $item) === true && $item !== '.' && $item !== '..' && is_link($source . $item) === false) {

          // Create the folder
          mkdir($destination . $item, $folderPermission, true);

          $this->copyFilesAndFolders($source . $item . '/', $destination . $item . '/', $folderPermission);
        }
        else {

          // If the item is a valid file
          if(is_file($source . $item) && $item !== '.DS_Store') {

            // Copy the file
            copy($source . $item, $destination . $item);
          }
        }

      }
      unset($item);
    }
  }


  //----------------------------------------------------------------------------
  /**
   * @param string $target
   */
  //----------------------------------------------------------------------------
  public function deleteFilesAndFolders($target) {

    // Make sure it's a folder
    if(is_dir($target)) {

      // Look for files and/or subfolders
      $items = scandir($target . '/');

      // At least one or more files/subfolders found
      if(count($items) > 0) {

        foreach ($items as $key => $item) {

          // Skip dots
          if($item !== '.' && $item !== '..') {

            // Make sure it's not a symlink
            if(is_file($target . '/' . $item) === true && is_link($target . '/' . $item) === false) {

              unlink($target . '/' . $item);
            }
            // It's a subfolder
            else {

              $this->deleteFilesAndFolders($target . '/' . $item . '/');
            }
          }

        }
        unset($item);

        rmdir($target);
      }
    }
  }
}
