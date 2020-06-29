/**
 * Reflect Form Mailer Addon
 * @package ReflectFormMailerAddon
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect-form-mailer
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.4
 * @since File available since v1.0.0-alpha.1
 */


window.addEventListener('DOMContentLoaded', function() {

  (function reflectFormMailerAddon() {

    const csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken;

    const addonFolderName = 'reflect-form-mailer';
    const addonConfigFormData = new FormData();

    addonConfigFormData.append('doAddonAsync', addonFolderName);
    addonConfigFormData.append('addonFunctionToRun', 'asyncGetAddonConfig');
    addonConfigFormData.append('csrfPreventionToken', csrfPreventionToken);

    // Get addon config
    apiGetData(addonConfigFormData).then(function(response) {

      if(response !== false) {

        const addonConfigJson = response;

        // Debug
        //console.log(addonConfigJson);

        let numOfForms = document.forms.length;

        if(numOfForms > 0) {

          let formNamesToTarget = addonConfigJson.formNamesToTarget;
          formNamesToTarget = formNamesToTarget.trim();
          formNamesToTarget = formNamesToTarget.replace(', ', ',');
          formNamesToTarget = formNamesToTarget.split(','); // Convert string to array of substrings

          let formNamesToExclude = addonConfigJson.formNamesToExclude;
          formNamesToExclude = formNamesToExclude.trim();
          formNamesToExclude = formNamesToExclude.replace(', ', ',');
          formNamesToExclude = formNamesToExclude.split(','); // Convert string to array of substrings

          let forms = document.forms;

          for(let form of forms) {

            // We don't want to target the site's search form
            if(form.getAttribute('name') !== 'siteSearchForm') {

              // If forms to target is specified
              if(formNamesToTarget[0] !== '') {

                // If the form name is found in the list of form names to target
                if(formNamesToTarget.includes(form.getAttribute('name'))) {

                  removeHiddenInputs(form);

                  addBracketsToMultiValueInputNames(form);

                  makeFormSubmitable(form);

                  // Debug
                  //console.log('Targeted form: ' + form.getAttribute('name'));
                }
                else {

                  // Do nothing
                }
              }
              // If forms to exclude is specified
              else if(formNamesToExclude[0] !== '') {

                // If the form name is found in the list of form names to exclude
                if(formNamesToExclude.includes(form.getAttribute('name'))) {

                  // Do nothing

                  // Debug
                  //console.log('Excluded form: ' + form.getAttribute('name'));
                }
                else {

                  removeHiddenInputs(form);

                  addBracketsToMultiValueInputNames(form);

                  makeFormSubmitable(form);
                }
              }
              // Target all forms by default
              else {

                removeHiddenInputs(form);

                addBracketsToMultiValueInputNames(form);

                makeFormSubmitable(form);

                // Debug
                //console.log('Form targeted by default: ' + form.getAttribute('name'));
              }
            }
          }
        }
      }
      else {

        // Debug
        //console.log(addonFolderName + ' not activated. Empty addon config property: ' + response);
      }
    });


    //--------------------------------------------------------------------------
    /**
     * @param object form
     */
    //--------------------------------------------------------------------------
    function removeHiddenInputs(form) {

      let formInputs = form.querySelectorAll('input');

      for(let formInput of formInputs) {

        // Remove all hidden inputs
        if(formInput.type === 'hidden') {

          formInput.remove();
        }
      }
    }


    //--------------------------------------------------------------------------
    /**
     * Add brackets [] to the names of multi-value inputs so that PHP will identify them correctly
     *
     * @param object form
     */
    //--------------------------------------------------------------------------
    function addBracketsToMultiValueInputNames(form) {

      // Get all checkbox type inputs and multiple select inputs
      let inputs = form.querySelectorAll('input[type="checkbox"], select[multiple="multiple"], select[multiple]');

      for(let i = 0; i < inputs.length; i++) {

        const oldName = inputs[i].name;

        // If the name does not already have square brackets
        if(!oldName.includes('[]')) {

          const newName = oldName + '[]'; // Add square bracket to indicate to PHP this input contains an array of values

          // Reiterate through every form fields
          for(let j = 0; j < form.elements.length; j++) {

            if(form[j].name === oldName) {

              form[j].setAttribute('name', newName); // Change the name of the input

              //console.log(form[j].name);
            }
          }
        }
      }
    }


    //--------------------------------------------------------------------------
    /**
     * @param object form
     */
    //--------------------------------------------------------------------------
    function makeFormSubmitable(form) {

      form.addEventListener('submit', function(event) {

        const addonFolderName = 'reflect-form-mailer';
        let formData = new FormData(form);

        formData.append('doAddonAsync', addonFolderName);
        formData.append('addonFunctionToRun', 'asyncSendEmail');
        formData.append('csrfPreventionToken', csrfPreventionToken);

        // Debug
        /*
        for(let input of formData.entries()) {

          console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function(response) {

          if(response === 'Email sent') {

            Swal.fire({
              title: 'Success',
              text: 'Form submitted.',
              icon: 'success',
              confirmButtonText: 'Ok'
            });
          }
          else {

            Swal.fire({
              title: 'Error',
              text: 'Form submission failed.',
              icon: 'error',
              confirmButtonText: 'Ok'
            });
          }
        });

        // Prevent the form from submitting in the usual way (which would trigger a http request)
        event.preventDefault();
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
