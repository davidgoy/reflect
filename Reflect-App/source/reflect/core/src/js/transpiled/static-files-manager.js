"use strict";

function _readOnlyError(name) { throw new Error("\"" + name + "\" is read-only"); }

/**
 * Reflect
 * @package Reflect
 * @author Min Tat Goy <david@davidgoy.dev>
 * @link https://github.com/davidgoy/reflect
 * @copyright 2020 Min Tat Goy
 * @license https://www.gnu.org/licenses/gpl.html   GPLv2 or later
 * @version 1.0.0-beta.11
 * @since File available since v1.0.0-alpha.1
 */
window.addEventListener('DOMContentLoaded', function () {
  (function reflectStaticFilesManager() {
    var csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken; // Hidden inputs

    var siteKey = document.querySelector('#siteKey').value;
    var sfmListItemsPerPage = document.querySelector('#sfmListItemsPerPage').value;
    var cmsBaseUrl = document.querySelector('#cmsBaseUrl').value;
    var staticPostsPageFileNumberSeparator = document.querySelector('#staticPostsPageFileNumberSeparator').value;
    var cmsPostsPageSlug = document.querySelector('#cmsPostsPageSlug').value;
    var primaryMenuSlug = document.querySelector('#primaryMenuSlug').value;
    var footerMenuSlug = document.querySelector('#footerMenuSlug').value; // Buttons

    var getMenusButton = document.querySelector('#getMenusButton');
    var getPageListButton = document.querySelector('#getPageListButton');
    var getPostListButton = document.querySelector('#getPostListButton'); // Settings

    var sfmForm = document.querySelector('#sfmForm'); // List components

    var selectListToDisplayText = document.querySelector('#selectListToDisplayText');
    var textHeading = document.querySelector('#textHeading');
    var noItemsFoundText = document.querySelector('#noItemsFoundText');
    var listBulkActionForm = document.querySelector('#listBulkActionForm');
    var listItemsBulkSelectCheckbox = document.querySelector('#listItemsBulkSelectCheckbox');
    var listTable = document.querySelector('#listTable');
    var listPagination = document.querySelector('#listPagination');
    var loadingSpinner = document.querySelector('#loadingSpinner');
    var contentType = '';
    setupEventHandling(); //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------

    function setupEventHandling() {
      /* ---------------------------------------------------------------------- */
      sfmForm.addEventListener('submit', function (event) {
        var sfmFormData = new FormData(sfmForm);
        sfmFormData.append('doAsync', 'saveSiteSettings');
        sfmFormData.append('csrfPreventionToken', csrfPreventionToken); // Debug

        /*
        for(let input of sfmFormData.entries()) {
           console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(sfmFormData).then(function (response) {
          if (response === 'true') {
            Swal.fire({
              title: 'STATIC SETTINGS SAVED',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          } else {
            Swal.fire({
              title: 'STATIC SETTINGS NOT SAVED',
              text: 'Either site key cannot be verified or there is a problem with config.json.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
        event.preventDefault();
      });
      /* ---------------------------------------------------------------------- */

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
      /* ---------------------------------------------------------------------- */


      getPageListButton.addEventListener('click', function (event) {
        contentType = 'page';
        textHeading.innerHTML = 'PAGES';
        noItemsFoundText.innerHTML = 'No published pages found on your WordPress site.';
        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.add('active');
        getPostListButton.classList.remove('active');
        getMenusButton.classList.remove('active');
        sessionStorage.clear();
        sessionStorage.setItem('currentLinkNumber', 1); // Clear some dynamically inserted elements

        var staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for (var _i = 0; _i < staticFileColumnContents.length; _i++) {
          staticFileColumnContents[_i].innerHTML = '';
        }

        buildList();
        buildPagination();
      });
      /* ---------------------------------------------------------------------- */

      getPostListButton.addEventListener('click', function (event) {
        contentType = 'post';
        textHeading.innerHTML = 'POSTS';
        noItemsFoundText.innerHTML = 'No published posts found on your WordPress site.';
        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.remove('active');
        getPostListButton.classList.add('active');
        getMenusButton.classList.remove('active');
        sessionStorage.clear();
        sessionStorage.setItem('currentLinkNumber', 1); // Clear some dynamically inserted elements

        var staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for (var _i2 = 0; _i2 < staticFileColumnContents.length; _i2++) {
          staticFileColumnContents[_i2].innerHTML = '';
        }

        buildList();
        buildPagination();
      });
      /* ---------------------------------------------------------------------- */

      getMenusButton.addEventListener('click', function (event) {
        contentType = 'menu';
        textHeading.innerHTML = 'MENUS';
        noItemsFoundText.innerHTML = 'No menus found on your WordPress site.';
        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.remove('active');
        getPostListButton.classList.remove('active');
        getMenusButton.classList.add('active');
        sessionStorage.clear(); // Clear some dynamically inserted elements

        var staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for (var _i3 = 0; _i3 < staticFileColumnContents.length; _i3++) {
          staticFileColumnContents[_i3].innerHTML = '';
        }

        buildMenuItems();
      });
      /* ---------------------------------------------------------------------- */

      listTable.addEventListener('click', function (event) {
        var listItemActionLink = event.target;
        var itemId = listItemActionLink.dataset.id;
        var itemSlug = listItemActionLink.dataset.slug; // Create a new object...

        var item = {}; //... with the following properties and values

        item.contentType = contentType;
        item.id = itemId;
        item.slug = itemSlug; // Detect for clicks on links that matches class...

        if (event.target.matches('.listItemViewLink')) {
          var staticMode = document.querySelector('input[name="staticMode"]');

          if (staticMode.value === 'false') {
            Swal.fire({
              title: 'STATIC MODE IS OFF',
              text: 'Turn on Static Mode (remember to hit SAVE), then try again.',
              icon: 'warning',
              confirmButtonText: 'OK'
            });
            event.preventDefault();
          }
        } else if (event.target.matches('.listItemGenerateLink') || event.target.matches('.listItemRegenerateLink')) {
          processListItemActionLinkClick(item, 'generate');
          event.preventDefault();
        } else if (event.target.matches('.listItemDeleteLink')) {
          processListItemActionLinkClick(item, 'delete');
          event.preventDefault();
        }
      });
      /* ---------------------------------------------------------------------- */

      menusTable.addEventListener('click', function (event) {
        var actionLink = event.target;
        var slug = actionLink.dataset.slug; // Detect for clicks on links that matches class...

        if (event.target.matches('.menuGenerateLink') || event.target.matches('.menuRegenerateLink')) {
          processMenuActionLinkClick(slug, 'generate');
          event.preventDefault();
        } else if (event.target.matches('.menuDeleteLink')) {
          processMenuActionLinkClick(slug, 'delete');
          event.preventDefault();
        }
      });
      /* ---------------------------------------------------------------------- */

      listBulkActionForm.addEventListener('submit', function (event) {
        processBulkActionFormSubmit();
        event.preventDefault();
      });
      /* ---------------------------------------------------------------------- */

      listItemsBulkSelectCheckbox.addEventListener('change', function (event) {
        var bulkSelectCheckbox = event.target;
        toggleListItemCheckboxes(bulkSelectCheckbox);
      });
      /* ---------------------------------------------------------------------- */

      listPagination.addEventListener('click', function (event) {
        // Detect for clicks on links that matches class
        if (event.target.matches('.listPaginationLink')) {
          var paginationLink = event.target;
          var paginationLinkNumber = paginationLink.dataset.paginationLinkNumber;
          sessionStorage.clear();
          sessionStorage.setItem('currentLinkNumber', paginationLinkNumber);
          buildList();
          buildPagination();
          event.preventDefault();
        }
      });
    } //--------------------------------------------------------------------------

    /**
     * @param object bulkSelectCheckbox
     */
    //--------------------------------------------------------------------------


    function toggleListItemCheckboxes(bulkSelectCheckbox) {
      var listItemCheckboxes = document.getElementsByClassName('listItemCheckbox');

      if (bulkSelectCheckbox.checked === true) {
        for (var i = 0; i < listItemCheckboxes.length; i++) {
          listItemCheckboxes[i].checked = true;
        }
      } else if (bulkSelectCheckbox.checked === false) {
        for (var _i4 = 0; _i4 < listItemCheckboxes.length; _i4++) {
          listItemCheckboxes[_i4].checked = false;
        }
      }
    } //--------------------------------------------------------------------------

    /**
     * @param object item
     * @param string action
     */
    //--------------------------------------------------------------------------


    function processListItemActionLinkClick(item, action) {
      var listItemActionsSpinner = document.querySelector("#listItemActionsSpinner_".concat(item.slug));
      var listItemStatusText = document.querySelector("#listItemStatusText_".concat(item.slug));
      var listItemActions = document.querySelector("#listItemActions_".concat(item.slug)); // Set UI

      listItemActionsSpinner.style.display = 'initial';
      listItemStatusText.style.display = 'none';
      listItemActions.style.visibility = 'hidden';
      var itemsToProcess = 1;
      var items = [];
      items.push(item);
      items = JSON.stringify(items);
      var formData = new FormData();
      formData.append('siteKey', siteKey);
      formData.append('contentType', contentType);
      formData.append('items', items);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if (action === 'generate') {
        formData.append('doAsync', 'generateStaticFiles');
      } else if (action === 'delete') {
        formData.append('doAsync', 'deleteStaticFiles');
      }

      apiGetData(formData).then(function (totalProcessed) {
        totalProcessed = parseInt(totalProcessed);

        if (totalProcessed === itemsToProcess) {
          if (action === 'generate') {
            listItemStatusText.innerHTML = 'File generated';
            listItemActions.innerHTML = "<a class=\"listItemViewLink\" href=\"/".concat(item.slug, "/\" target=\"_blank\">VIEW</a> | <a class=\"listItemRegenerateLink\" data-id=\"").concat(item.id, "\" data-slug=\"").concat(item.slug, "\" href=\"#\">REGENERATE</a> | <a class=\"listItemDeleteLink\" data-id=\"").concat(item.id, "\" data-slug=\"").concat(item.slug, "\" href=\"#\">DELETE</a>");
          } else if (action === 'delete') {
            listItemStatusText.innerHTML = 'File deleted';
            listItemActions.innerHTML = "<a class=\"listItemGenerateLink\" data-id=\"".concat(item.id, "\" data-slug=\"").concat(item.slug, "\" href=\"#\">GENERATE</a>");
          }
        } else {
          if (action === 'generate') {
            listItemStatusText.innerHTML = 'Error: File not generated';
          } else if (action === 'delete') {
            listItemStatusText.innerHTML = 'Error: File not deleted';
          }
        } // Set UI


        listItemActionsSpinner.style.display = 'none';
        listItemStatusText.style.display = 'initial';
        listItemActions.style.visibility = 'visible';
      });
    } //--------------------------------------------------------------------------

    /**
     * @param string slug
     * @param string action
     */
    //--------------------------------------------------------------------------


    function processMenuActionLinkClick(slug, action) {
      var menuActionsSpinner = document.querySelector("#menuActionsSpinner_".concat(slug));
      var menuStatusText = document.querySelector("#menuStatusText_".concat(slug));
      var menuActions = document.querySelector("#menuActions_".concat(slug)); // Set UI

      menuActionsSpinner.style.display = 'initial';
      menuStatusText.style.display = 'none';
      menuActions.style.visibility = 'hidden';
      var itemsToProcess = 1;
      var formData = new FormData();
      formData.append('siteKey', siteKey);
      formData.append('contentType', contentType);
      formData.append('slug', slug);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if (action === 'generate') {
        formData.append('doAsync', 'generateStaticFiles');
      } else if (action === 'delete') {
        formData.append('doAsync', 'deleteStaticFiles');
      }

      apiGetData(formData).then(function (totalProcessed) {
        totalProcessed = parseInt(totalProcessed);

        if (totalProcessed === itemsToProcess) {
          if (action === 'generate') {
            menuStatusText.innerHTML = 'File generated';
            menuActions.innerHTML = "<a class=\"menuRegenerateLink\" data-slug=\"".concat(slug, "\" href=\"#\">REGENERATE</a> | <a class=\"menuDeleteLink\" data-slug=\"").concat(slug, "\" href=\"#\">DELETE</a>");
          } else if (action === 'delete') {
            menuStatusText.innerHTML = 'File deleted';
            menuActions.innerHTML = "<a class=\"menuGenerateLink\" data-slug=\"".concat(slug, "\" href=\"#\">GENERATE</a>");
          }
        } else {
          if (action === 'generate') {
            menuStatusText.innerHTML = 'Error: File not generated';
          } else if (action === 'delete') {
            menuStatusText.innerHTML = 'Error: File not deleted';
          }
        } // Set UI


        menuActionsSpinner.style.display = 'none';
        menuStatusText.style.display = 'initial';
        menuActions.style.visibility = 'visible';
      });
    } //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------


    function processBulkActionFormSubmit() {
      // Set UI
      var listBulkActionFormSubmitButton = document.querySelector('#listBulkActionFormSubmitButton');
      listBulkActionFormSubmitButton.classList.add('disabled'); // Disable button

      var originalButtonText = listBulkActionFormSubmitButton.innerHTML;
      listBulkActionFormSubmitButton.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"> <span class="sr-only">Working...</span> </div> WORKING...';
      var bulkAction = document.querySelector('#listBulkActionSelect').value;

      if (bulkAction != '') {
        var items = [];
        var listItemCheckboxes = document.getElementsByClassName('listItemCheckbox');

        for (var i = 0; i < listItemCheckboxes.length; i++) {
          if (listItemCheckboxes[i].checked === true) {
            // Create a new object...
            var item = {}; //... with the following properties and values

            item.contentType = contentType;
            item.id = listItemCheckboxes[i].dataset.id;
            item.slug = listItemCheckboxes[i].dataset.slug;
            items.push(item);
          }
        }

        if (items.length > 0) {
          var itemsToProcess = items.length;
          items = JSON.stringify(items); // Debug
          //console.log(items);

          var formData = new FormData();
          formData.append('siteKey', siteKey);
          formData.append('contentType', contentType);
          formData.append('items', items);
          formData.append('csrfPreventionToken', csrfPreventionToken);
          var alertSuccessTitle = '';
          var alertErrorTitle = '';

          if (bulkAction === 'generate') {
            formData.append('doAsync', 'generateStaticFiles');
          } else if (bulkAction === 'delete') {
            formData.append('doAsync', 'deleteStaticFiles');
          }

          apiGetData(formData).then(function (totalProcessed) {
            totalProcessed = parseInt(totalProcessed);

            if (totalProcessed === itemsToProcess) {
              if (bulkAction === 'generate') {
                alertSuccessTitle = 'STATIC FILE(S) GENERATED';
              } else if (bulkAction === 'delete') {
                alertSuccessTitle = 'STATIC FILE(S) DELETED';
              }

              Swal.fire({
                title: alertSuccessTitle,
                text: "".concat(totalProcessed, " of ").concat(itemsToProcess, " items successfully processed."),
                icon: 'success',
                confirmButtonText: 'OK'
              });
            } else {
              Swal.fire({
                title: 'ERROR ENCOUNTERED',
                text: "".concat(totalProcessed, " of ").concat(itemsToProcess, " items processed."),
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }

            buildList(); // Set UI

            listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button

            listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
          });
        } else {
          Swal.fire({
            title: 'NO ITEMS SELECTED',
            text: 'Please select at least one item to perform this action.',
            icon: 'warning',
            confirmButtonText: 'OK'
          }); // Set UI

          listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button

          listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
        }
      } else {
        Swal.fire({
          title: 'NO BULK ACTION SELECTED',
          text: 'Please select an action to perform on the checked items.',
          icon: 'warning',
          confirmButtonText: 'OK'
        }); // Set UI

        listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button

        listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
      }
    } //--------------------------------------------------------------------------

    /**
     *
     */
    //--------------------------------------------------------------------------


    function buildMenuItems() {
      // Set UI
      loadingSpinner.classList.remove('invisible'); // Show loading spinner

      var list = document.querySelector('#list');
      list.classList.add('invisible'); // Hide list

      var menusTable = document.querySelector('#menusTable');
      menusTable.classList.add('invisible'); // Hide menus table

      var noItemsFoundText = document.querySelector('#noItemsFoundText');
      noItemsFoundText.classList.add('invisible'); // Hide text

      var rowsHtml = '';
      var staticFileColumnHtml = '';
      var menusTableBody = document.querySelector('#menusTableBody'); // Get all static files if available...

      var formData = new FormData();
      formData.append('siteKey', siteKey);
      formData.append('doAsync', 'getStaticFilesInfo');
      formData.append('contentType', 'menu');
      formData.append('csrfPreventionToken', csrfPreventionToken);
      apiGetData(formData).then(function (staticFiles) {
        var menuSlugs = [primaryMenuSlug, footerMenuSlug]; // For each menu...

        var _loop = function _loop(i) {
          var formData = new FormData();
          formData.append('siteKey', siteKey);
          formData.append('doAsync', 'getMenuItems');
          formData.append('menuSlug', menuSlugs[i]);
          formData.append('csrfPreventionToken', csrfPreventionToken);
          apiGetData(formData).then(function (menuItems) {
            // Set UI
            loadingSpinner.classList.add('invisible'); // Hide loading spinner

            noItemsFoundText.classList.remove('invisible'); // Show text by default (unless at least menu exists on CMS)
            // If the menu exists in CMS

            if (menuItems.length > 0) {
              staticFileColumnHtml = "<div class=\"staticFileColumnContent\" id=\"menuActionsSpinner_".concat(menuSlugs[i], "\" style=\"display: none;\"><div class=\"spinner-border spinner-border-sm text-secondary\" role=\"status\"><span class=\"sr-only\">Working...</span></div> Working...</div>\n                                      <div class=\"staticFileColumnContent\" id=\"menuStatusText_").concat(menuSlugs[i], "\">Not found</div>\n                                      <div class=\"staticFileColumnContent\" id=\"menuActions_").concat(menuSlugs[i], "\"><a class=\"menuGenerateLink\" data-slug=\"").concat(menuSlugs[i], "\" href=\"#\">GENERATE</a></div>");

              for (var j = 0; j < staticFiles.length; j++) {
                var fileSlug = staticFiles[j].fileName.replace('.html', ''); // Remove .html from file name
                // If static file exists for the menu...

                if (fileSlug === menuSlugs[i]) {
                  staticFileColumnHtml = "<div class=\"staticFileColumnContent\" id=\"menuActionsSpinner_".concat(menuSlugs[i], "\" style=\"display: none;\"><div class=\"spinner-border spinner-border-sm text-secondary\" role=\"status\"><span class=\"sr-only\">Working...</span></div> Working...</div>\n                                          <div class=\"staticFileColumnContent\" id=\"menuStatusText_").concat(menuSlugs[i], "\">Generated ").concat(staticFiles[j].fileModified, "</div>\n                                          <div class=\"staticFileColumnContent\" id=\"menuActions_").concat(menuSlugs[i], "\"><a class=\"menuRegenerateLink\" data-slug=\"").concat(menuSlugs[i], "\" href=\"#\">REGENERATE</a> | <a class=\"menuDeleteLink\" data-slug=\"").concat(menuSlugs[i], "\" href=\"#\">DELETE</a></div>");
                  break;
                }
              }

              rowsHtml = rowsHtml + "\n\n              <tr>\n                <td class=\"small\">\n                  ".concat(menuSlugs[i].replace('-', ' '), "\n                </td>\n                <td class=\"small\">\n                  ").concat(menuSlugs[i], "\n                </td>\n                <td class=\"small\">\n                  ").concat(staticFileColumnHtml, "\n                </td>\n              </tr>\n              ");
              menusTableBody.innerHTML = rowsHtml; // Set UI

              loadingSpinner.classList.add('invisible'); // Hide loading spinner

              noItemsFoundText.classList.add('invisible'); // Hide text

              menusTable.classList.remove('invisible'); // Show menus table
            }
          });
        };

        for (var i = 0; i < menuSlugs.length; i++) {
          _loop(i);
        }
      });
    } //--------------------------------------------------------------------------

    /**
     * @param array params
     */
    //--------------------------------------------------------------------------


    function buildList() {
      var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
      // Set UI
      loadingSpinner.classList.remove('invisible'); // Show loading spinner

      var list = document.querySelector('#list');
      list.classList.add('invisible'); // Hide list

      var menusTable = document.querySelector('#menusTable');
      menusTable.classList.add('invisible'); // Hide menus table

      var noItemsFoundText = document.querySelector('#noItemsFoundText');
      noItemsFoundText.classList.add('invisible'); // Hide text

      var contentTypeFirstLetterCapped = contentType.charAt(0).toUpperCase() + contentType.substring(1); // Capitalise first letter of word (e.g. from "page" to "Page")

      var listTableBody = document.querySelector('#listTableBody');
      var formData = new FormData();
      formData.append('siteKey', siteKey);
      formData.append('doAsync', "get".concat(contentTypeFirstLetterCapped, "s"));
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if (params['perPage'] !== undefined) {
        formData.append('perPage', parseInt(params['perPage']));
      } else {
        formData.append('perPage', parseInt(sfmListItemsPerPage));
      }

      if (params['pageNumber'] !== undefined) {
        formData.append('pageNumber', parseInt(params['pageNumber']));
      } else {
        formData.append('pageNumber', parseInt(sessionStorage.getItem('currentLinkNumber')));
      }

      if (params['orderBy'] !== undefined) {
        formData.append('orderBy', params['orderBy']);
      }

      if (params['order'] !== undefined) {
        formData.append('order', params['order']);
      } // Get items (pages or posts) from CMS


      apiGetData(formData).then(function (items) {
        // Page(s) or post(s) found on CMS
        if (items.length > 0) {
          var _formData = new FormData();

          _formData.append('siteKey', siteKey);

          _formData.append('doAsync', 'getStaticFilesInfo');

          _formData.append('contentType', contentType);

          _formData.append('csrfPreventionToken', csrfPreventionToken); // Get all static files info (if available)


          apiGetData(_formData).then(function (staticFiles) {
            // Debug
            //console.log(staticFiles);
            var rowsHtml = '';
            var staticFileColumnHtml = '';

            for (var i = 0; i < items.length; i++) {
              var cmsContentModified = new Date(items[i].modified).toLocaleString('en-GB', {
                timeZone: 'UTC'
              });
              staticFileColumnHtml = "<div class=\"staticFileColumnContent\" id=\"listItemActionsSpinner_".concat(items[i].slug, "\" style=\"display: none;\"><div class=\"spinner-border spinner-border-sm text-secondary\" role=\"status\"><span class=\"sr-only\">Working...</span></div> Working...</div>\n                                      <div class=\"staticFileColumnContent\" id=\"listItemStatusText_").concat(items[i].slug, "\">Not found</div>\n                                      <div class=\"staticFileColumnContent\" id=\"listItemActions_").concat(items[i].slug, "\"><a class=\"listItemGenerateLink\" data-id=\"").concat(items[i].id, "\" data-slug=\"").concat(items[i].slug, "\" href=\"#\">GENERATE</a></div>");

              for (var j = 0; j < staticFiles.length; j++) {
                var fileSlug = staticFiles[j].fileName.replace('.html', ''); // Remove .html from file name

                var regex = new RegExp('^.*(?=' + staticPostsPageFileNumberSeparator + '[0-9])');
                var postsPageSlug = fileSlug.match(regex); // If static file exists for the item...

                if (fileSlug === items[i].slug || postsPageSlug !== null && postsPageSlug[0] === items[i].slug) {
                  staticFileColumnHtml = "<div class=\"staticFileColumnContent\" id=\"listItemActionsSpinner_".concat(items[i].slug, "\" style=\"display: none;\"><div class=\"spinner-border spinner-border-sm text-secondary\" role=\"status\"><span class=\"sr-only\">Working...</span></div> Working...</div>\n                                          <div class=\"staticFileColumnContent\" id=\"listItemStatusText_").concat(items[i].slug, "\">Generated ").concat(staticFiles[j].fileModified, "</div>\n                                          <div class=\"staticFileColumnContent\" id=\"listItemActions_").concat(items[i].slug, "\"><a class=\"listItemViewLink\" href=\"/").concat(items[i].slug, "/\" target=\"_blank\">VIEW</a> | <a class=\"listItemRegenerateLink\" data-id=\"").concat(items[i].id, "\" data-slug=\"").concat(items[i].slug, "\" href=\"#\">REGENERATE</a> | <a class=\"listItemDeleteLink\" data-id=\"").concat(items[i].id, "\" data-slug=\"").concat(items[i].slug, "\" href=\"#\">DELETE</a></div>");
                  break;
                }
              }

              rowsHtml = rowsHtml + "\n\n              <tr>\n                <th scope=\"row\">\n                  <input class=\"listItemCheckbox\" type=\"checkbox\" data-id=\"".concat(items[i].id, "\" data-slug=\"").concat(items[i].slug, "\">\n                </th>\n                <td class=\"small\">\n                  ").concat(items[i].title.rendered, "\n                </td>\n                <td class=\"small\">\n                  ").concat(items[i].slug, "\n                </td>\n                <td class=\"small\">\n                  ").concat(cmsContentModified, "<br><a href=\"").concat(cmsBaseUrl).concat(items[i].slug, "\" target=\"_blank\">VIEW ON WP</a>\n                </td>\n                <td class=\"small\">\n                  ").concat(staticFileColumnHtml, "\n                </td>\n              </tr>\n              ");
            } // Debug
            //console.log(rowsHtml);


            listTableBody.innerHTML = rowsHtml; // Set UI

            loadingSpinner.classList.add('invisible'); // Hide loading spinner

            list.classList.remove('invisible'); // Show list

            listItemsBulkSelectCheckbox.checked = false; // Uncheck bulk select checkbox
          });
        } // No page(s) or post(s) found on CMS
        else {
            // Set UI
            loadingSpinner.classList.add('invisible'); // Hide loading spinner

            noItemsFoundText.classList.remove('invisible'); // Show text
          }
      });
    } //--------------------------------------------------------------------------

    /**
     * @param array params
     */
    //--------------------------------------------------------------------------


    function buildPagination() {
      var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
      var contentTypeFirstLetterCapped = contentType.charAt(0).toUpperCase() + contentType.substring(1); // Capitalise first letter of word (e.g. from "page" to "Page")

      var formData = new FormData();
      formData.append('siteKey', siteKey);
      formData.append('doAsync', "getTotal".concat(contentTypeFirstLetterCapped, "s"));
      formData.append('csrfPreventionToken', csrfPreventionToken); // Get the total number of published items from CMS

      apiGetData(formData).then(function (response) {
        var totalPublishedItems = parseInt(response);
        var perPage = parseInt(sfmListItemsPerPage);
        var currentLinkNumber = parseInt(sessionStorage.getItem('currentLinkNumber'));

        if (params['perPage'] !== undefined) {
          perPage = (_readOnlyError("perPage"), params['perPage']);
        }

        if (params['pageNumber'] !== undefined) {
          currentLinkNumber = (_readOnlyError("currentLinkNumber"), params['pageNumber']);
        }

        var totalLinks = Math.ceil(totalPublishedItems / perPage); // Only display pagination if there are more than one pagination pages

        if (totalLinks > 1 && totalPublishedItems > perPage) {
          var _listPagination = document.querySelector('#listPagination');

          var previousLinkHtml = '';
          var numberedLinksHtml = '';
          var nextLinkHtml = '';
          var paginationHtml = '';

          if (currentLinkNumber > 1) {
            previousLinkHtml = "\n              <li class=\"page-item\">\n                <a class=\"page-link listPaginationLink\" data-pagination-link-number=\"".concat(currentLinkNumber - 1, "\" href=\"#\">&laquo;</a>\n              </li>\n            ");
          }

          for (var i = 0; i < totalLinks; i++) {
            if (i + 1 === currentLinkNumber) {
              numberedLinksHtml = numberedLinksHtml + "\n                <li class=\"page-item active\" aria-current=\"page\">\n                  <a class=\"page-link listPaginationLink\" data-pagination-link-number=\"".concat(i + 1, "\" href=\"#\">").concat(i + 1, "</a>\n                </li>\n              ");
            } else {
              numberedLinksHtml = numberedLinksHtml + "\n                <li class=\"page-item\">\n                  <a class=\"page-link listPaginationLink\" data-pagination-link-number=\"".concat(i + 1, "\" href=\"#\">").concat(i + 1, "</a>\n                </li>\n              ");
            }
          }

          if (currentLinkNumber !== totalLinks) {
            nextLinkHtml = "\n              <li class=\"page-item\">\n                <a class=\"page-link listPaginationLink\" data-pagination-link-number=\"".concat(currentLinkNumber + 1, "\" href=\"#\">&raquo;</a>\n              </li>\n            ");
          }

          paginationHtml = previousLinkHtml + numberedLinksHtml + nextLinkHtml; // Debug
          //console.log(paginationHtml);

          _listPagination.innerHTML = paginationHtml; // Pagination info text...

          var _listPaginationInfo = document.querySelector('#listPaginationInfo');

          var fromPaginationItem = currentLinkNumber * perPage + 1 - perPage;
          var toPaginationItem = currentLinkNumber * perPage;

          if (currentLinkNumber === totalLinks) {
            toPaginationItem = currentLinkNumber * perPage - (currentLinkNumber * perPage - totalPublishedItems);
          }

          _listPaginationInfo.innerHTML = "Showing ".concat(fromPaginationItem, " - ").concat(toPaginationItem, " of ").concat(totalPublishedItems, " items");
        } // Clear out any HTML, if there were previously any
        else {
            listPagination.innerHTML = '';
            listPaginationInfo.innerHTML = '';
          }
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
        } else {// ...
        }
      }).then(function (data) {
        return data;
      })["catch"](function (error) {
        console.log('Error: ' + error);
      });
    }
  })();
});