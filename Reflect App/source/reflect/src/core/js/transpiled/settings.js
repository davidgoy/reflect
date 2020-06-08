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
})({"jHCi":[function(require,module,exports) {
/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.1
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectSettings() {
    var form = document.querySelector('#settings');
    var addonCheckboxes = form.querySelectorAll('input[name="addonsToLoad[]"]');
    /* ---------------------------------------------------------------------- */

    form.addEventListener('submit', function (event) {
      var formData = new FormData(form);
      formData.append('doXhr', 'saveSiteSettings'); // Debug

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
      }).catch(function (error) {// Debug
        //console.log('Error: ' + error);
      });
    }
  })();
});
},{}]},{},["jHCi"], null)