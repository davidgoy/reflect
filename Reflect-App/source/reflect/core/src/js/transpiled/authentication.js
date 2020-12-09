"use strict";

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.7
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectAuthentication() {
    var csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken;
    var siteKeyInput = document.querySelector('#siteKey');
    siteKeyInput.value = '';
    var authenticateButton = document.querySelector('#authenticateButton');
    var authenticateButtonSpinner = document.querySelector('#authenticateButtonSpinner');
    var authenticateButtonText = document.querySelector('#authenticateButtonText'); //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------

    (function handleAuthenticationFormSubmission() {
      var form = document.querySelector('#authentication');
      var targetActionInput = document.querySelector('#targetAction');
      var targetAction = targetActionInput.value;
      form.addEventListener('submit', function (event) {
        authenticateButton.setAttribute('disabled', 'disabled');
        authenticateButtonSpinner.classList.remove('d-none');
        authenticateButtonText.innerHTML = 'AUTHENTICATING...';
        var formData = new FormData(form);
        formData.append('doAsync', 'authenticateSiteKey');
        formData.append('csrfPreventionToken', csrfPreventionToken); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function (response) {
          authenticateButton.removeAttribute('disabled');
          authenticateButtonSpinner.classList.add('d-none');
          authenticateButtonText.innerHTML = 'AUTHENTICATE';

          if (response !== 'false') {
            Swal.fire({
              title: 'SITE KEY AUTHENTICATED',
              text: 'Access granted.',
              icon: 'success',
              confirmButtonText: 'PROCEED'
            }).then(function (userConfirmation) {
              if (userConfirmation.value) {
                var documentHtml = response; // Debug
                //console.log(documentHtml);

                document.open();
                document.write(documentHtml);
                document.close();
              }
            });
          } else {
            Swal.fire({
              title: 'SITE KEY INCORRECT',
              text: 'Access denied.',
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