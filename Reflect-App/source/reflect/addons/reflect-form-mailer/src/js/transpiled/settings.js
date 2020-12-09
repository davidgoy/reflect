"use strict";

/**
 * Reflect Form Mailer Addon
 * @package ReflectFormMailerAddon
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect-form-mailer
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.7
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectFormMailerAddonSettings() {
    var csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken; //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------

    (function initSwitches() {
      var switches = document.getElementsByClassName('switch');

      for (var i = 0; i < switches.length; i++) {
        switches[i].addEventListener('change', function (event) {
          var theSwitch = event.target;
          var theActualInput = document.querySelector("input[name=".concat(theSwitch.id, "]")); // This is a hidden input. We do it this way because Bootstrap currently uses checkbox input type to render a switch

          if (theSwitch.checked === true) {
            theActualInput.value = 'true';
          } else {
            theActualInput.value = 'false';
          }
        });
      }
    })(); //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------


    (function handleSettingsFormSubmission() {
      var form = document.querySelector('#reflectFormMailerAddonSettings');
      form.addEventListener('submit', function (event) {
        var formData = new FormData(form);
        formData.append('doAsync', 'saveAddonSettings');
        formData.append('addonFolderName', 'reflect-form-mailer');
        formData.append('csrfPreventionToken', csrfPreventionToken); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function (response) {
          if (response === 'true') {
            Swal.fire({
              title: 'SETTINGS SAVED',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          } else {
            Swal.fire({
              title: 'SETTINGS NOT SAVED',
              text: 'Either site key cannot be verified or there is a problem with config.json.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
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