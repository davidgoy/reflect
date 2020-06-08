// modules are defined as an array
// [ module function, map of requires ]
//
// map of requires is short require name -> numeric require
//
// anything defined in a previous bundle is accessed via the
// orig method which is the require for previous bundles
parcelRequire = (function (modules, cache, entry, globalName) {
  // Save the require from previous bundle to this closure if any
  var previousRequire = typeof parcelRequire === 'function' && parcelRequire;
  var nodeRequire = typeof require === 'function' && require;

  function newRequire(name, jumped) {
    if (!cache[name]) {
      if (!modules[name]) {
        // if we cannot find the module within our internal map or
        // cache jump to the current global require ie. the last bundle
        // that was added to the page.
        var currentRequire = typeof parcelRequire === 'function' && parcelRequire;
        if (!jumped && currentRequire) {
          return currentRequire(name, true);
        }

        // If there are other bundles on this page the require from the
        // previous one is saved to 'previousRequire'. Repeat this as
        // many times as there are bundles until the module is found or
        // we exhaust the require chain.
        if (previousRequire) {
          return previousRequire(name, true);
        }

        // Try the node require function if it exists.
        if (nodeRequire && typeof name === 'string') {
          return nodeRequire(name);
        }

        var err = new Error('Cannot find module \'' + name + '\'');
        err.code = 'MODULE_NOT_FOUND';
        throw err;
      }

      localRequire.resolve = resolve;
      localRequire.cache = {};

      var module = cache[name] = new newRequire.Module(name);

      modules[name][0].call(module.exports, localRequire, module, module.exports, this);
    }

    return cache[name].exports;

    function localRequire(x){
      return newRequire(localRequire.resolve(x));
    }

    function resolve(x){
      return modules[name][1][x] || x;
    }
  }

  function Module(moduleName) {
    this.id = moduleName;
    this.bundle = newRequire;
    this.exports = {};
  }

  newRequire.isParcelRequire = true;
  newRequire.Module = Module;
  newRequire.modules = modules;
  newRequire.cache = cache;
  newRequire.parent = previousRequire;
  newRequire.register = function (id, exports) {
    modules[id] = [function (require, module) {
      module.exports = exports;
    }, {}];
  };

  var error;
  for (var i = 0; i < entry.length; i++) {
    try {
      newRequire(entry[i]);
    } catch (e) {
      // Save first error but execute all entries
      if (!error) {
        error = e;
      }
    }
  }

  if (entry.length) {
    // Expose entry point to Node, AMD or browser globals
    // Based on https://github.com/ForbesLindesay/umd/blob/master/template.js
    var mainExports = newRequire(entry[entry.length - 1]);

    // CommonJS
    if (typeof exports === "object" && typeof module !== "undefined") {
      module.exports = mainExports;

    // RequireJS
    } else if (typeof define === "function" && define.amd) {
     define(function () {
       return mainExports;
     });

    // <script>
    } else if (globalName) {
      this[globalName] = mainExports;
    }
  }

  // Override the current require with this new one
  parcelRequire = newRequire;

  if (error) {
    // throw error from earlier, _after updating parcelRequire_
    throw error;
  }

  return newRequire;
})({"uGtA":[function(require,module,exports) {
function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/**
 * Reflect Form Mailer Addon
 * @package ReflectFormMailerAddon
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect-form-mailer
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.1
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectFormMailerAddon() {
    var addonFolderName = 'reflect-form-mailer';
    var addonConfigFormData = new FormData();
    addonConfigFormData.append('doAddonXhr', addonFolderName);
    addonConfigFormData.append('addonFunctionToRun', 'xhrGetAddonConfig'); // Get addon config

    apiGetData(addonConfigFormData).then(function (response) {
      if (response !== false) {
        var addonConfigJson = response; // Debug
        //console.log(addonConfigJson);

        var numOfForms = document.forms.length;

        if (numOfForms > 0) {
          var formNamesToTarget = addonConfigJson.formNamesToTarget;
          formNamesToTarget = formNamesToTarget.trim();
          formNamesToTarget = formNamesToTarget.replace(', ', ',');
          formNamesToTarget = formNamesToTarget.split(','); // Convert string to array of substrings

          var formNamesToExclude = addonConfigJson.formNamesToExclude;
          formNamesToExclude = formNamesToExclude.trim();
          formNamesToExclude = formNamesToExclude.replace(', ', ',');
          formNamesToExclude = formNamesToExclude.split(','); // Convert string to array of substrings

          var forms = document.forms;

          var _iterator = _createForOfIteratorHelper(forms),
              _step;

          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var form = _step.value;

              // We don't want to target the site's search form
              if (form.getAttribute('name') !== 'siteSearchForm') {
                // If forms to target is specified
                if (formNamesToTarget[0] !== '') {
                  // If the form name is found in the list of form names to target
                  if (formNamesToTarget.includes(form.getAttribute('name'))) {
                    removeHiddenInputs(form);
                    addBracketsToMultiValueInputNames(form);
                    makeFormSubmitable(form); // Debug
                    //console.log('Targeted form: ' + form.getAttribute('name'));
                  } else {// Do nothing
                    }
                } // If forms to exclude is specified
                else if (formNamesToExclude[0] !== '') {
                    // If the form name is found in the list of form names to exclude
                    if (formNamesToExclude.includes(form.getAttribute('name'))) {// Do nothing
                      // Debug
                      //console.log('Excluded form: ' + form.getAttribute('name'));
                    } else {
                      removeHiddenInputs(form);
                      addBracketsToMultiValueInputNames(form);
                      makeFormSubmitable(form);
                    }
                  } // Target all forms by default
                  else {
                      removeHiddenInputs(form);
                      addBracketsToMultiValueInputNames(form);
                      makeFormSubmitable(form); // Debug
                      //console.log('Form targeted by default: ' + form.getAttribute('name'));
                    }
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }
      } else {// Debug
          //console.log(addonFolderName + ' not activated. Empty addon config property: ' + response);
        }
    }); //--------------------------------------------------------------------------

    /**
     * @param object form
     */
    //--------------------------------------------------------------------------

    function removeHiddenInputs(form) {
      var formInputs = form.querySelectorAll('input');

      var _iterator2 = _createForOfIteratorHelper(formInputs),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var formInput = _step2.value;

          // Remove all hidden inputs
          if (formInput.type === 'hidden') {
            formInput.remove();
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    } //--------------------------------------------------------------------------

    /**
     * Add brackets [] to the names of multi-value inputs so that PHP will identify them correctly
     *
     * @param object form
     */
    //--------------------------------------------------------------------------


    function addBracketsToMultiValueInputNames(form) {
      // Get all checkbox type inputs and multiple select inputs
      var inputs = form.querySelectorAll('input[type="checkbox"], select[multiple="multiple"], select[multiple]');

      for (var i = 0; i < inputs.length; i++) {
        var oldName = inputs[i].name; // If the name does not already have square brackets

        if (!oldName.includes('[]')) {
          var newName = oldName + '[]'; // Add square bracket to indicate to PHP this input contains an array of values
          // Reiterate through every form fields

          for (var j = 0; j < form.elements.length; j++) {
            if (form[j].name === oldName) {
              form[j].setAttribute('name', newName); // Change the name of the input
              //console.log(form[j].name);
            }
          }
        }
      }
    } //--------------------------------------------------------------------------

    /**
     * @param object form
     */
    //--------------------------------------------------------------------------


    function makeFormSubmitable(form) {
      form.addEventListener('submit', function (event) {
        var addonFolderName = 'reflect-form-mailer';
        var formData = new FormData(form);
        formData.append('doAddonXhr', addonFolderName);
        formData.append('addonFunctionToRun', 'xhrSendEmail'); // Debug

        /*
        for(let input of formData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(formData).then(function (response) {
          // We will dynamically create a <script> tag...
          var swalScript = document.createElement('script'); // ... and when it's loaded, we show the alert

          swalScript.onload = function () {
            if (response === 'Email sent') {
              Swal.fire({
                title: 'Success',
                text: 'Form submitted.',
                icon: 'success',
                confirmButtonText: 'Ok'
              });
            } else {
              Swal.fire({
                title: 'Error',
                text: 'Form submission failed.',
                icon: 'error',
                confirmButtonText: 'Ok'
              });
            }
          };

          swalScript.src = '/js/sweetalert2/sweetalert2.all.min.js';
          document.head.appendChild(swalScript); // Append the <script> tag to the <head>
        }); // Prevent the form from submitting in the usual way (which would trigger a http request)

        event.preventDefault();
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
      }).catch(function (error) {// Debug
        //console.log('Error: ' + error);
      });
    }
  })();
});
},{}]},{},["uGtA"], null)