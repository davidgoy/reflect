"use strict";

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.4
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectSetup() {
    var csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken; //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------

    (function handleSiteKeyRegeneration() {
      var regenerateSiteKeyButton = document.querySelector('#regenerateSiteKey');
      regenerateSiteKeyButton.addEventListener('click', function (event) {
        var formData = new FormData();
        formData.append('doAsync', 'generateSiteKey');
        formData.append('csrfPreventionToken', csrfPreventionToken); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function (response) {
          if (response !== 'false') {
            var siteKeyInput = document.querySelector('#siteKey');
            siteKeyInput.value = response;
          } else {// Debug
            //console.log(`Error: ${response}`);
          }
        });
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
        formData.append('doAsync', 'saveSiteKey');
        formData.append('csrfPreventionToken', csrfPreventionToken); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function (response) {
          if (response === 'true') {
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
        }); // Prevent the form from submitting in the usual way (which would trigger a http request)

        event.preventDefault();
      });
    })(); //--------------------------------------------------------------------------

    /**
     * @param FormData formData
     * @return object data
     */
    //--------------------------------------------------------------------------


    function apiGetData(formData) {
      return fetch('/index.php', {
        method: 'POST',
        cache: 'no-cache',
        body: formData
      }).then(function (response) {
        if (response.ok) {
          return response.json();
        } else {// Do nothing
        }
      }).then(function (data) {
        return data;
      })["catch"](function (error) {// Debug
        //console.log('Error: ' + error);
      });
    }
  })();
});