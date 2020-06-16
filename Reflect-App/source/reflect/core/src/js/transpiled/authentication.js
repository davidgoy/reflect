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
  (function reflectAuthentication() {
    var siteKeyInput = document.querySelector('#siteKey');
    siteKeyInput.value = ''; //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------

    (function handleAuthenticationFormSubmission() {
      var form = document.querySelector('#authentication');
      var targetActionInput = document.querySelector('#targetAction');
      var targetAction = targetActionInput.value;
      form.addEventListener('submit', function (event) {
        var formData = new FormData(form);
        formData.append('doXhr', 'authenticateSiteKey'); // Debug

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
              if (xhr.responseText !== 'false') {
                Swal.fire({
                  title: 'SITE KEY AUTHENTICATED',
                  text: 'Access granted.',
                  icon: 'success',
                  confirmButtonText: 'PROCEED'
                }).then(function (userConfirmation) {
                  if (userConfirmation.value) {
                    var documentHtml = xhr.responseText; // Debug
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