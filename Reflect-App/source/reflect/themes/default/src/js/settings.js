/**
 * Default Theme
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.7
 * @since File available since v1.0.0-beta.1
 */


window.addEventListener('DOMContentLoaded', function() {

  (function defaultThemeSettings() {

    const csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken;


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
     *
     */
    //--------------------------------------------------------------------------
    (function handleSettingsFormSubmission() {

      const form = document.querySelector('#defaultThemeSettings');

      form.addEventListener('submit', function(event) {

        const formData = new FormData(form);

        formData.append('doAsync', 'saveThemeSettings');
        formData.append('themeFolderName', 'default');
        formData.append('csrfPreventionToken', csrfPreventionToken);

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

    })();


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    (function attachColourPickerToInputs() {

      // Primary menu background colour
      const primaryMenuBgColourInput = document.querySelector('#primaryMenuBgColour');
      let primaryMenuBgColourPickr = {};
      attachColourPicker(primaryMenuBgColourPickr, primaryMenuBgColourInput);

      // Primary menu text colour
      const primaryMenuTextColourInput = document.querySelector('#primaryMenuTextColour');
      let primaryMenuTextColourPickr = {};
      attachColourPicker(primaryMenuTextColourPickr, primaryMenuTextColourInput);

      // Primary menu item background colour (hovered or active)
      const primaryMenuItemHoverAndActiveBgColourInput = document.querySelector('#primaryMenuItemHoverAndActiveBgColour');
      let primaryMenuItemHoverAndActiveBgColourPickr = {};
      attachColourPicker(primaryMenuItemHoverAndActiveBgColourPickr, primaryMenuItemHoverAndActiveBgColourInput);

      // Primary menu item text colour (hovered or active)
      const primaryMenuItemHoverAndActiveTextColourInput = document.querySelector('#primaryMenuItemHoverAndActiveTextColour');
      let primaryMenuItemHoverAndActiveTextColourPickr = {};
      attachColourPicker(primaryMenuItemHoverAndActiveTextColourPickr, primaryMenuItemHoverAndActiveTextColourInput);

      // Primary menu sub-item background colour (hovered or active)
      const primaryMenuSubItemHoverAndActiveBgColourInput = document.querySelector('#primaryMenuSubItemHoverAndActiveBgColour');
      let primaryMenuSubItemHoverAndActiveBgColourPickr = {};
      attachColourPicker(primaryMenuSubItemHoverAndActiveBgColourPickr, primaryMenuSubItemHoverAndActiveBgColourInput);

      // Primary menu sub-item text colour (hovered or active)
      const primaryMenuSubItemHoverAndActiveTextColourInput = document.querySelector('#primaryMenuSubItemHoverAndActiveTextColour');
      let primaryMenuSubItemHoverAndActiveTextColourPickr = {};
      attachColourPicker(primaryMenuSubItemHoverAndActiveTextColourPickr, primaryMenuSubItemHoverAndActiveTextColourInput);

      // Footer menu background colour
      const footerMenuBgColourInput = document.querySelector('#footerMenuBgColour');
      let footerMenuBgColourPickr = {};
      attachColourPicker(footerMenuBgColourPickr, footerMenuBgColourInput);

      // Footer menu text colour
      const footerMenuTextColourInput = document.querySelector('#footerMenuTextColour');
      let footerMenuTextColourPickr = {};
      attachColourPicker(footerMenuTextColourPickr, footerMenuTextColourInput);
    })();

    //--------------------------------------------------------------------------
    /**
     * @param object pickrInstance
     * @param HTMLElement inputElement
     */
    //--------------------------------------------------------------------------
    function attachColourPicker(pickrInstance, inputElement) {

      pickrInstance = new Pickr({
        el: inputElement,
        useAsButton: true,
        default: inputElement.value,
        theme: 'classic',
        components: {
          preview: true,
          hue: true,
          interaction: {
            input: true,
            save: true
          }
        }
      }).on('init', pickrInstance => {
        inputElement.value = pickrInstance.getSelectedColor().toHEXA().toString(0);
      }).on('save', color => {
        inputElement.value = color.toHEXA().toString(0);
        pickrInstance.hide();
      });
    }

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
