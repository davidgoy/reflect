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
})({"YvZc":[function(require,module,exports) {
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
  (function reflectSetup() {
    //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------
    (function handleSiteKeyRegeneration() {
      var regenerateSiteKeyButton = document.querySelector('#regenerateSiteKey');
      regenerateSiteKeyButton.addEventListener('click', function (event) {
        var formData = new FormData();
        formData.append('doXhr', 'generateSiteKey'); // Debug

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
              if (xhr.responseText != '') {
                var siteKeyInput = document.querySelector('#siteKey');
                siteKeyInput.value = xhr.responseText;
              } else {// Debug
                //console.log(`Error: ${xhr.responseText}`);
              }
            }
          }
        };

        xhr.onerror = function () {
          reject(xhr.statusText);
        }; // Send form data to the server


        xhr.send(formData);
      });
    })(); //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------


    (function handleSetupFormSubmission() {
      var form = document.querySelector('#setup');
      form.addEventListener('submit', function (event) {
        var formData = new FormData(form);
        formData.append('doXhr', 'saveSiteKey'); // Debug

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
              if (xhr.responseText === 'true') {
                Swal.fire({
                  title: 'SITE KEY SAVED',
                  text: 'Have you copied and stored the site key somewhere safe?',
                  icon: 'success',
                  showCancelButton: true,
                  confirmButtonText: 'YES',
                  cancelButtonText: 'NO',
                  reverseButtons: true
                }).then(function (userConfirmation) {
                  if (userConfirmation.value) {
                    // Reload the page
                    location.reload();
                  } else if (userConfirmation.dismiss === Swal.DismissReason.cancel) {// Do nothing
                  }
                });
              } else {
                Swal.fire({
                  title: 'ERROR',
                  text: 'Site key may have already been saved. Otherwise check config.json for possible errors.',
                  icon: 'error',
                  confirmButtonText: 'OK'
                }).then(function (userConfirmation) {
                  if (userConfirmation.value) {
                    // Reload the page
                    location.reload();
                  }
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
},{}]},{},["YvZc"], null)