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


window.addEventListener('DOMContentLoaded', function() {

  (function reflectSettings() {

    // Hidden inputs
    const siteKey = document.querySelector('#siteKey').value;

    const form = document.querySelector('#settings');
    const addonCheckboxes = form.querySelectorAll('input[name="addonsToLoad[]"]');
    const checkWpConnectionButton = document.querySelector('#checkWpConnectionButton');


    /* ---------------------------------------------------------------------- */
    form.addEventListener('submit', function(event) {

      const formData = new FormData(form);

      formData.append('doXhr', 'saveSiteSettings');

      // Debug
      /*
      for(let input of formData.entries()) {

        console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
      }
      */

      apiGetData(formData).then(function(response) {

        if(response === 'true') {

          Swal.fire({
            title: 'SETTINGS SAVED',
            icon: 'success',
            confirmButtonText: 'OK'
          });
        }
        else {

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


    /* ---------------------------------------------------------------------- */
    checkWpConnectionButton.addEventListener('click', function(event) {

      const cmsProtocol = document.querySelector('#cmsProtocol').value;
      const cmsDomain = document.querySelector('#cmsDomain').value;

      if(cmsProtocol !== '' && cmsDomain !== '') {

        const formData = new FormData();

        formData.append('siteKey', siteKey);
        formData.append('cmsProtocol', cmsProtocol);
        formData.append('cmsDomain', cmsDomain);
        formData.append('doXhr', 'getWpApiInfo');

        apiGetData(formData).then(function(wpApiInfoJson) {

          if(wpApiInfoJson !== undefined && wpApiInfoJson.name !== undefined) {

            Swal.fire({
              title: 'CONNECTION SUCCESSFUL',
              text: wpApiInfoJson.name,
              icon: 'success',
              confirmButtonText: 'OK'
            });
          }
          else {

            Swal.fire({
              title: 'CONNECTION FAILED',
              text: 'Check that the URL is correct and that your WordPress API is accessible by this Reflect site.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }

      event.preventDefault();
    });


    //----------------------------------------------------------------------------
    /**
     *
     */
    //----------------------------------------------------------------------------
    (function initAddonCheckboxes() {

      for(let i = 0; i < addonCheckboxes.length; i++) {

        if(addonCheckboxes[i].type === 'checkbox') {

          let editSettingsSpanId = addonCheckboxes[i].id + 'Settings';
          let editSettingsSpan = document.querySelector('#' + editSettingsSpanId);

          if(addonCheckboxes[i].checked === true) {

            editSettingsSpan.style.visibility = 'visible';
          }
          else if(addonCheckboxes[i].checked === false) {

            editSettingsSpan.style.visibility = 'hidden';
          }


          /* ---------------------------------------------------------------------- */
          addonCheckboxes[i].addEventListener('change', function(event) {

            if(event.target.checked === true) {

              editSettingsSpan.style.visibility = 'visible';
            }
            else if(event.target.checked === false) {

              editSettingsSpan.style.visibility = 'hidden';
            }
          });
        }
      }
    })();


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    (function initSwitches() {

      let switches = document.getElementsByClassName('switch');

      for(let i = 0; i < switches.length; i++) {

        switches[i].addEventListener('change', function(event) {

          let theSwitch = event.target;
          let theActualInput = document.querySelector(`input[name=${theSwitch.id}]`); // This is a hidden input. We do it this way because Bootstrap currently uses checkbox input type to render a switch

          if(theSwitch.checked === true) {

            theActualInput.value = 'true';
          }
          else {

            theActualInput.value = 'false';
          }
        });
      }
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
