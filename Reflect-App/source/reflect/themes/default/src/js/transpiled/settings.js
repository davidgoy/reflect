"use strict";

/**
 * Default Theme
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.4
 * @since File available since v1.0.0-beta.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function defaultThemeSettings() {
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
      var form = document.querySelector('#defaultThemeSettings');
      form.addEventListener('submit', function (event) {
        var formData = new FormData(form);
        formData.append('doAsync', 'saveThemeSettings');
        formData.append('themeFolderName', 'default');
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
     *
     */
    //--------------------------------------------------------------------------


    (function attachColourPickerToInputs() {
      // Primary menu background colour
      var primaryMenuBgColourInput = document.querySelector('#primaryMenuBgColour');
      var primaryMenuBgColourPickr = {};
      attachColourPicker(primaryMenuBgColourPickr, primaryMenuBgColourInput); // Primary menu text colour

      var primaryMenuTextColourInput = document.querySelector('#primaryMenuTextColour');
      var primaryMenuTextColourPickr = {};
      attachColourPicker(primaryMenuTextColourPickr, primaryMenuTextColourInput); // Primary menu item background colour (hovered or active)

      var primaryMenuItemHoverAndActiveBgColourInput = document.querySelector('#primaryMenuItemHoverAndActiveBgColour');
      var primaryMenuItemHoverAndActiveBgColourPickr = {};
      attachColourPicker(primaryMenuItemHoverAndActiveBgColourPickr, primaryMenuItemHoverAndActiveBgColourInput); // Primary menu item text colour (hovered or active)

      var primaryMenuItemHoverAndActiveTextColourInput = document.querySelector('#primaryMenuItemHoverAndActiveTextColour');
      var primaryMenuItemHoverAndActiveTextColourPickr = {};
      attachColourPicker(primaryMenuItemHoverAndActiveTextColourPickr, primaryMenuItemHoverAndActiveTextColourInput); // Primary menu sub-item background colour (hovered or active)

      var primaryMenuSubItemHoverAndActiveBgColourInput = document.querySelector('#primaryMenuSubItemHoverAndActiveBgColour');
      var primaryMenuSubItemHoverAndActiveBgColourPickr = {};
      attachColourPicker(primaryMenuSubItemHoverAndActiveBgColourPickr, primaryMenuSubItemHoverAndActiveBgColourInput); // Primary menu sub-item text colour (hovered or active)

      var primaryMenuSubItemHoverAndActiveTextColourInput = document.querySelector('#primaryMenuSubItemHoverAndActiveTextColour');
      var primaryMenuSubItemHoverAndActiveTextColourPickr = {};
      attachColourPicker(primaryMenuSubItemHoverAndActiveTextColourPickr, primaryMenuSubItemHoverAndActiveTextColourInput); // Footer menu background colour

      var footerMenuBgColourInput = document.querySelector('#footerMenuBgColour');
      var footerMenuBgColourPickr = {};
      attachColourPicker(footerMenuBgColourPickr, footerMenuBgColourInput); // Footer menu text colour

      var footerMenuTextColourInput = document.querySelector('#footerMenuTextColour');
      var footerMenuTextColourPickr = {};
      attachColourPicker(footerMenuTextColourPickr, footerMenuTextColourInput);
    })(); //--------------------------------------------------------------------------

    /**
     * @param object pickrInstance
     * @param HTMLElement inputElement
     */
    //--------------------------------------------------------------------------


    function attachColourPicker(pickrInstance, inputElement) {
      pickrInstance = new Pickr({
        el: inputElement,
        useAsButton: true,
        "default": inputElement.value,
        theme: 'classic',
        components: {
          preview: true,
          hue: true,
          interaction: {
            input: true,
            save: true
          }
        }
      }).on('init', function (pickrInstance) {
        inputElement.value = pickrInstance.getSelectedColor().toHEXA().toString(0);
      }).on('save', function (color) {
        inputElement.value = color.toHEXA().toString(0);
        pickrInstance.hide();
      });
    } //--------------------------------------------------------------------------

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