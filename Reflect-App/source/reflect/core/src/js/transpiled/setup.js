"use strict";

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.3
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectSetup() {
    //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------
    (function handleSiteKeyRegeneration() {
      var regenerateSiteKeyButton = document.querySelector('#regenerateSiteKey');
      regenerateSiteKeyButton.addEventListener('click', function (event) {
        var formData = new FormData();
        formData.append('doXhr', 'generateSiteKey'); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        var xhr = new XMLHttpRequest();
        var xhrHandlerUrl = '/index.php';
        xhr.open('POST', xhrHandlerUrl);
        xhr.responseType = 'text';

        xhr.onload = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              // Process response from server
              if (xhr.responseText != '') {
                var siteKeyInput = document.querySelector('#siteKey');
                siteKeyInput.value = xhr.responseText;
              } else {// Debug
                //console.log(`Error: ${xhr.responseText}`);
              }
            }
          }
        };

        xhr.onerror = function () {
          reject(xhr.statusText);
        }; // Send form data to the server


        xhr.send(formData);
      });
    })(); //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------


    (function handleSetupFormSubmission() {
      var form = document.querySelector('#setup');
      form.addEventListener('submit', function (event) {
        var formData = new FormData(form);
        formData.append('doXhr', 'saveSiteKey'); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        var xhr = new XMLHttpRequest();
        var xhrHandlerUrl = '/index.php';
        xhr.open('POST', xhrHandlerUrl);
        xhr.responseType = 'text';

        xhr.onload = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              // Process response from server
              if (xhr.responseText === 'true') {
                Swal.fire({
                  title: 'SITE KEY SAVED',
                  text: 'Have you copied and stored the site key somewhere safe?',
                  icon: 'success',
                  showCancelButton: true,
                  confirmButtonText: 'YES',
                  cancelButtonText: 'NO',
                  reverseButtons: true
                }).then(function (userConfirmation) {
                  if (userConfirmation.value) {
                    // Reload the page
                    location.reload();
                  } else if (userConfirmation.dismiss === Swal.DismissReason.cancel) {// Do nothing
                  }
                });
              } else {
                Swal.fire({
                  title: 'ERROR',
                  text: 'Site key may have already been saved. Otherwise check config.json for possible errors.',
                  icon: 'error',
                  confirmButtonText: 'OK'
                }).then(function (userConfirmation) {
                  if (userConfirmation.value) {
                    // Reload the page
                    location.reload();
                  }
                });
              }
            }
          }
        };

        xhr.onerror = function () {
          reject(xhr.statusText);
        }; // Send form data to the server


        xhr.send(formData);
        event.preventDefault();
      });
    })();
  })();
});