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
  (function reflectSettings() {
    var csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken; // Update related elements

    var checkForUpdateText = document.querySelector('#checkForUpdateText');
    var checkForUpdateButton = document.querySelector('#checkForUpdateButton');
    var checkingInProgressText = document.querySelector('#checkingInProgressText');
    var upToDateText = document.querySelector('#upToDateText');
    var updateAvailableText = document.querySelector('#updateAvailableText');
    var updateInProgressText = document.querySelector('#updateInProgressText');
    var updateNowButton = document.querySelector('#updateNowButton');
    var updateCompletedText = document.querySelector('#updateCompletedText');
    var updateFailedText = document.querySelector('#updateFailedText');
    var updateFailedReasonText = document.querySelector('#updateFailedReasonText');
    var newerVersionText = document.querySelector('#newerVersionText');
    var updateSpinner = document.querySelector('#updateSpinner'); // Hidden inputs

    var siteKey = document.querySelector('#siteKey').value;
    var form = document.querySelector('#settings');
    var addonCheckboxes = form.querySelectorAll('input[name="addonsToLoad[]"]');
    var checkWpConnectionButton = document.querySelector('#checkWpConnectionButton');
    var checkWpConnectionButtonText = document.querySelector('#checkWpConnectionButtonText');
    var checkWpConnectionButtonSpinner = document.querySelector('#checkWpConnectionButtonSpinner');
    var saveSettingsButton = document.querySelector('#saveSettingsButton');
    /* ---------------------------------------------------------------------- */

    form.addEventListener('submit', function (event) {
      var formData = new FormData(form);
      formData.append('doAsync', 'saveSiteSettings');
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
    /* ---------------------------------------------------------------------- */

    checkWpConnectionButton.addEventListener('click', function (event) {
      var cmsProtocol = document.querySelector('#cmsProtocol').value;
      var cmsDomain = document.querySelector('#cmsDomain').value;

      if (cmsDomain !== '') {
        checkWpConnectionButton.setAttribute('disabled', 'disabled');
        checkWpConnectionButtonText.innerHTML = 'CONNECTING...';
        checkWpConnectionButtonSpinner.classList.remove('d-none');

        if (cmsProtocol !== '' && cmsDomain !== '') {
          var formData = new FormData();
          formData.append('siteKey', siteKey);
          formData.append('cmsProtocol', cmsProtocol);
          formData.append('cmsDomain', cmsDomain);
          formData.append('doAsync', 'getWpApiInfo');
          formData.append('csrfPreventionToken', csrfPreventionToken);
          apiGetData(formData).then(function (wpApiInfoJson) {
            checkWpConnectionButton.removeAttribute('disabled');
            checkWpConnectionButtonText.innerHTML = 'CHECK CONNECTION';
            checkWpConnectionButtonSpinner.classList.add('d-none');

            if (wpApiInfoJson !== undefined && wpApiInfoJson.name !== undefined) {
              Swal.fire({
                title: 'CONNECTION SUCCESSFUL',
                text: wpApiInfoJson.name,
                icon: 'success',
                confirmButtonText: 'OK'
              });
            } else {
              Swal.fire({
                title: 'CONNECTION FAILED',
                text: 'Check that the URL is correct and that your WordPress API is accessible by this Reflect site.',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        }
      } else {
        Swal.fire({
          title: 'NO DOMAIN NAME ENTERED',
          text: 'Please enter the domain name of your WordPress CMS.',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }

      event.preventDefault();
    });
    /* ---------------------------------------------------------------------- */

    checkForUpdateButton.addEventListener('click', function (event) {
      var formData = new FormData();
      formData.append('doAsync', 'checkAvailableUpdate');
      formData.append('csrfPreventionToken', csrfPreventionToken);
      checkForUpdateText.classList.add('d-none');
      checkingInProgressText.classList.remove('d-none');
      updateSpinner.classList.remove('d-none');
      apiGetData(formData).then(function (latestAvailableVersion) {
        checkingInProgressText.classList.add('d-none');

        if (latestAvailableVersion !== 'false') {
          newerVersionText.innerHTML = latestAvailableVersion;
          updateAvailableText.classList.remove('d-none');
          updateSpinner.classList.add('d-none');
        } else {
          upToDateText.classList.remove('d-none');
          updateSpinner.classList.add('d-none');
        }
      });
      event.preventDefault();
    });
    /* ---------------------------------------------------------------------- */

    updateNowButton.addEventListener('click', function (event) {
      var formData = new FormData();
      formData.append('doAsync', 'installUpdate');
      formData.append('csrfPreventionToken', csrfPreventionToken);
      updateAvailableText.classList.add('d-none');
      updateInProgressText.classList.remove('d-none');
      updateSpinner.classList.remove('d-none');
      saveSettingsButton.setAttribute('disabled', 'disabled');
      apiGetData(formData).then(function (response) {
        updateSpinner.classList.add('d-none');
        updateInProgressText.classList.add('d-none');

        if (response === 'true') {
          updateCompletedText.classList.remove('d-none');
        } else {
          updateFailedReasonText.innerHTML = response;
          updateFailedText.classList.remove('d-none');
          saveSettingsButton.removeAttribute('disabled');
        }
      });
      event.preventDefault();
    }); //----------------------------------------------------------------------------

    /**
     *
     */
    //----------------------------------------------------------------------------

    (function initAddonCheckboxes() {
      for (var i = 0; i < addonCheckboxes.length; i++) {
        if (addonCheckboxes[i].type === 'checkbox') {
          (function () {
            var editSettingsSpanId = addonCheckboxes[i].id + 'Settings';
            var editSettingsSpan = document.querySelector('#' + editSettingsSpanId);

            if (addonCheckboxes[i].checked === true) {
              editSettingsSpan.style.visibility = 'visible';
            } else if (addonCheckboxes[i].checked === false) {
              editSettingsSpan.style.visibility = 'hidden';
            }
            /* ---------------------------------------------------------------------- */


            addonCheckboxes[i].addEventListener('change', function (event) {
              if (event.target.checked === true) {
                editSettingsSpan.style.visibility = 'visible';
              } else if (event.target.checked === false) {
                editSettingsSpan.style.visibility = 'hidden';
              }
            });
          })();
        }
      }
    })(); //--------------------------------------------------------------------------

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