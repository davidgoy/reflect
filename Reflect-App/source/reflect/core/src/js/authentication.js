/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.9
 * @since File available since v1.0.0-alpha.1
 */


window.addEventListener('DOMContentLoaded', function() {

  (function reflectAuthentication() {

    const csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken;

    const siteKeyInput = document.querySelector('#siteKey');
    siteKeyInput.value = '';

    const authenticateButton = document.querySelector('#authenticateButton');
    const authenticateButtonSpinner = document.querySelector('#authenticateButtonSpinner');
    const authenticateButtonText = document.querySelector('#authenticateButtonText');


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    (function handleAuthenticationFormSubmission() {

      const form = document.querySelector('#authentication');

      const targetActionInput = document.querySelector('#targetAction');
      const targetAction = targetActionInput.value;

      form.addEventListener('submit', function(event) {

        authenticateButton.setAttribute('disabled', 'disabled');
        authenticateButtonSpinner.classList.remove('d-none');
        authenticateButtonText.innerHTML = 'AUTHENTICATING...';

        const formData = new FormData(form);

        formData.append('doAsync', 'authenticateSiteKey');
        formData.append('csrfPreventionToken', csrfPreventionToken);

        // Debug
        /*
        for(let input of formData.entries()) {

          console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function(response) {

          authenticateButton.removeAttribute('disabled');
          authenticateButtonSpinner.classList.add('d-none');
          authenticateButtonText.innerHTML = 'AUTHENTICATE';

          if(response !== 'false') {

            Swal.fire({
              title: 'SITE KEY AUTHENTICATED',
              text: 'Access granted.',
              icon: 'success',
              confirmButtonText: 'PROCEED'
            })
            .then(function(userConfirmation) {

              if(userConfirmation.value) {

                const documentHtml = response;

                // Debug
                //console.log(documentHtml);

                document.open();
                document.write(documentHtml);
                document.close();
              }

            });
          }
          else {

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

    })();

    //--------------------------------------------------------------------------
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
      })
      .then(function(response) {

        if(response.ok) {

          return response.json();
        }
        else {

          // Do nothing
        }

      }).then(function(data) {

        return data;
      }).catch(function(error) {

        // Debug
        //console.log('Error: ' + error);

      });

    }

  })();
});
