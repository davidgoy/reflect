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


window.addEventListener('DOMContentLoaded', function() {

  (function reflectStaticFilesManager() {

    const csrfPreventionToken = document.querySelector('#csrfPreventionToken').dataset.csrfPreventionToken;

    // Hidden inputs
    const siteKey = document.querySelector('#siteKey').value;
    const sfmListItemsPerPage = document.querySelector('#sfmListItemsPerPage').value;
    const cmsBaseUrl = document.querySelector('#cmsBaseUrl').value;
    const staticPostsPageFileNumberSeparator = document.querySelector('#staticPostsPageFileNumberSeparator').value;
    const cmsPostsPageSlug = document.querySelector('#cmsPostsPageSlug').value;
    const primaryMenuSlug = document.querySelector('#primaryMenuSlug').value;
    const footerMenuSlug = document.querySelector('#footerMenuSlug').value;

    // Buttons
    const getMenusButton = document.querySelector('#getMenusButton');
    const getPageListButton = document.querySelector('#getPageListButton');
    const getPostListButton = document.querySelector('#getPostListButton');

    // Settings
    const sfmForm = document.querySelector('#sfmForm');

    // List components
    const selectListToDisplayText = document.querySelector('#selectListToDisplayText');
    const textHeading = document.querySelector('#textHeading');
    const noItemsFoundText = document.querySelector('#noItemsFoundText');
    const listBulkActionForm = document.querySelector('#listBulkActionForm');
    const listItemsBulkSelectCheckbox = document.querySelector('#listItemsBulkSelectCheckbox');
    const listTable = document.querySelector('#listTable');
    const listPagination = document.querySelector('#listPagination');

    const loadingSpinner = document.querySelector('#loadingSpinner');

    let contentType = '';


    setupEventHandling();


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    function setupEventHandling() {


      /* ---------------------------------------------------------------------- */
      sfmForm.addEventListener('submit', function(event) {

        const sfmFormData = new FormData(sfmForm);

        sfmFormData.append('doAsync', 'saveSiteSettings');
        sfmFormData.append('csrfPreventionToken', csrfPreventionToken);

        // Debug
        /*
        for(let input of sfmFormData.entries()) {

          console.log('Input name: ' + input[0] + ' | Input value: ' + input[1]);
        }
        */

        apiGetData(sfmFormData).then(function(response) {

          if(response === 'true') {

            Swal.fire({
              title: 'STATIC SETTINGS SAVED',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          }
          else {

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


      /* ---------------------------------------------------------------------- */
      getPageListButton.addEventListener('click', function(event) {

        contentType = 'page';
        textHeading.innerHTML = 'PAGES';
        noItemsFoundText.innerHTML = 'No published pages found on your WordPress site.';

        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.add('active');
        getPostListButton.classList.remove('active');
        getMenusButton.classList.remove('active');

        sessionStorage.clear();
        sessionStorage.setItem('currentLinkNumber', 1);

        // Clear some dynamically inserted elements
        const staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for(let i = 0; i < staticFileColumnContents.length; i++) {

          staticFileColumnContents[i].innerHTML = '';
        }

        buildList();
        buildPagination();
      });


      /* ---------------------------------------------------------------------- */
      getPostListButton.addEventListener('click', function(event) {

        contentType = 'post';
        textHeading.innerHTML = 'POSTS';
        noItemsFoundText.innerHTML = 'No published posts found on your WordPress site.';

        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.remove('active');
        getPostListButton.classList.add('active');
        getMenusButton.classList.remove('active');

        sessionStorage.clear();
        sessionStorage.setItem('currentLinkNumber', 1);

        // Clear some dynamically inserted elements
        const staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for(let i = 0; i < staticFileColumnContents.length; i++) {

          staticFileColumnContents[i].innerHTML = '';
        }

        buildList();
        buildPagination();
      });


      /* ---------------------------------------------------------------------- */
      getMenusButton.addEventListener('click', function(event) {

        contentType = 'menu';
        textHeading.innerHTML = 'MENUS';
        noItemsFoundText.innerHTML = 'No menus found on your WordPress site.';

        selectListToDisplayText.classList.add('invisible');
        getPageListButton.classList.remove('active');
        getPostListButton.classList.remove('active');
        getMenusButton.classList.add('active');

        sessionStorage.clear();

        // Clear some dynamically inserted elements
        const staticFileColumnContents = document.getElementsByClassName('staticFileColumnContent');

        for(let i = 0; i < staticFileColumnContents.length; i++) {

          staticFileColumnContents[i].innerHTML = '';
        }

        buildMenuItems();
      });


      /* ---------------------------------------------------------------------- */
      listTable.addEventListener('click', function(event) {

        const listItemActionLink = event.target;
        const itemId = listItemActionLink.dataset.id;
        const itemSlug = listItemActionLink.dataset.slug;

        // Create a new object...
        let item = {};

        //... with the following properties and values
        item.contentType = contentType;
        item.id = itemId;
        item.slug = itemSlug;

        // Detect for clicks on links that matches class...

        if(event.target.matches('.listItemViewLink')) {

          const staticMode = document.querySelector('input[name="staticMode"]');

          if(staticMode.value === 'false') {

            Swal.fire({
              title: 'STATIC MODE IS OFF',
              text: 'Turn on Static Mode (remember to hit SAVE), then try again.',
              icon: 'warning',
              confirmButtonText: 'OK'
            });

            event.preventDefault();
          }
        }
        else if(event.target.matches('.listItemGenerateLink') || event.target.matches('.listItemRegenerateLink')) {

          processListItemActionLinkClick(item, 'generate');

          event.preventDefault();
        }
        else if(event.target.matches('.listItemDeleteLink')) {

          processListItemActionLinkClick(item, 'delete');

          event.preventDefault();
        }

      });


      /* ---------------------------------------------------------------------- */
      menusTable.addEventListener('click', function(event) {

        const actionLink = event.target;
        const slug = actionLink.dataset.slug;

        // Detect for clicks on links that matches class...

        if(event.target.matches('.menuGenerateLink') || event.target.matches('.menuRegenerateLink')) {

          processMenuActionLinkClick(slug, 'generate');

          event.preventDefault();
        }
        else if(event.target.matches('.menuDeleteLink')) {

          processMenuActionLinkClick(slug, 'delete');

          event.preventDefault();
        }

      });


      /* ---------------------------------------------------------------------- */
      listBulkActionForm.addEventListener('submit', function(event) {

        processBulkActionFormSubmit();

        event.preventDefault();
      });


      /* ---------------------------------------------------------------------- */
      listItemsBulkSelectCheckbox.addEventListener('change', function(event) {

        const bulkSelectCheckbox = event.target;

        toggleListItemCheckboxes(bulkSelectCheckbox);
      });


      /* ---------------------------------------------------------------------- */
      listPagination.addEventListener('click', function(event) {

        // Detect for clicks on links that matches class
        if(event.target.matches('.listPaginationLink')) {

          const paginationLink = event.target;
          const paginationLinkNumber = paginationLink.dataset.paginationLinkNumber;

          sessionStorage.clear();
          sessionStorage.setItem('currentLinkNumber', paginationLinkNumber);

          buildList();
          buildPagination();

          event.preventDefault();
        }
      });
    }


    //--------------------------------------------------------------------------
    /**
     * @param object bulkSelectCheckbox
     */
    //--------------------------------------------------------------------------
    function toggleListItemCheckboxes(bulkSelectCheckbox) {

      let listItemCheckboxes = document.getElementsByClassName('listItemCheckbox');

      if(bulkSelectCheckbox.checked === true) {

        for(let i = 0; i < listItemCheckboxes.length; i++) {

          listItemCheckboxes[i].checked = true;
        }
      }
      else if(bulkSelectCheckbox.checked === false) {

        for(let i = 0; i < listItemCheckboxes.length; i++) {

          listItemCheckboxes[i].checked = false;
        }
      }
    }


    //--------------------------------------------------------------------------
    /**
     * @param object item
     * @param string action
     */
    //--------------------------------------------------------------------------
    function processListItemActionLinkClick(item, action) {

      const listItemActionsSpinner = document.querySelector(`#listItemActionsSpinner_${item.slug}`);
      const listItemStatusText = document.querySelector(`#listItemStatusText_${item.slug}`);
      const listItemActions = document.querySelector(`#listItemActions_${item.slug}`);

      // Set UI
      listItemActionsSpinner.style.display = 'initial';
      listItemStatusText.style.display = 'none';
      listItemActions.style.visibility = 'hidden';

      const itemsToProcess = 1;

      let items = [];
      items.push(item);
      items = JSON.stringify(items);

      const formData = new FormData();

      formData.append('siteKey', siteKey);
      formData.append('contentType', contentType);
      formData.append('items', items);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if(action === 'generate') {

        formData.append('doAsync', 'generateStaticFiles');

      }
      else if(action === 'delete') {

        formData.append('doAsync', 'deleteStaticFiles');
      }

      apiGetData(formData).then(function(totalProcessed) {

        totalProcessed = parseInt(totalProcessed);

        if(totalProcessed === itemsToProcess) {

          if(action === 'generate') {

            listItemStatusText.innerHTML = 'File generated';
            listItemActions.innerHTML = `<a class="listItemViewLink" href="/${item.slug}/" target="_blank">VIEW</a> | <a class="listItemRegenerateLink" data-id="${item.id}" data-slug="${item.slug}" href="#">REGENERATE</a> | <a class="listItemDeleteLink" data-id="${item.id}" data-slug="${item.slug}" href="#">DELETE</a>`;
          }
          else if(action === 'delete') {

            listItemStatusText.innerHTML = 'File deleted';
            listItemActions.innerHTML = `<a class="listItemGenerateLink" data-id="${item.id}" data-slug="${item.slug}" href="#">GENERATE</a>`;
          }
        }
        else {

          if(action === 'generate') {

            listItemStatusText.innerHTML = 'Error: File not generated';
          }
          else if(action === 'delete') {

            listItemStatusText.innerHTML = 'Error: File not deleted';
          }
        }

        // Set UI
        listItemActionsSpinner.style.display = 'none';
        listItemStatusText.style.display = 'initial';
        listItemActions.style.visibility = 'visible';
      });
    }


    //--------------------------------------------------------------------------
    /**
     * @param string slug
     * @param string action
     */
    //--------------------------------------------------------------------------
    function processMenuActionLinkClick(slug, action) {

      const menuActionsSpinner = document.querySelector(`#menuActionsSpinner_${slug}`);
      const menuStatusText = document.querySelector(`#menuStatusText_${slug}`);
      const menuActions = document.querySelector(`#menuActions_${slug}`);

      // Set UI
      menuActionsSpinner.style.display = 'initial';
      menuStatusText.style.display = 'none';
      menuActions.style.visibility = 'hidden';

      const itemsToProcess = 1;

      const formData = new FormData();

      formData.append('siteKey', siteKey);
      formData.append('contentType', contentType);
      formData.append('slug', slug);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if(action === 'generate') {

        formData.append('doAsync', 'generateStaticFiles');

      }
      else if(action === 'delete') {

        formData.append('doAsync', 'deleteStaticFiles');
      }

      apiGetData(formData).then(function(totalProcessed) {

        totalProcessed = parseInt(totalProcessed);

        if(totalProcessed === itemsToProcess) {

          if(action === 'generate') {

            menuStatusText.innerHTML = 'File generated';
            menuActions.innerHTML = `<a class="menuRegenerateLink" data-slug="${slug}" href="#">REGENERATE</a> | <a class="menuDeleteLink" data-slug="${slug}" href="#">DELETE</a>`;
          }
          else if(action === 'delete') {

            menuStatusText.innerHTML = 'File deleted';
            menuActions.innerHTML = `<a class="menuGenerateLink" data-slug="${slug}" href="#">GENERATE</a>`;
          }
        }
        else {

          if(action === 'generate') {

            menuStatusText.innerHTML = 'Error: File not generated';
          }
          else if(action === 'delete') {

            menuStatusText.innerHTML = 'Error: File not deleted';
          }
        }

        // Set UI
        menuActionsSpinner.style.display = 'none';
        menuStatusText.style.display = 'initial';
        menuActions.style.visibility = 'visible';
      });
    }


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    function processBulkActionFormSubmit() {

      // Set UI
      const listBulkActionFormSubmitButton = document.querySelector('#listBulkActionFormSubmitButton');
      listBulkActionFormSubmitButton.classList.add('disabled'); // Disable button
      const originalButtonText = listBulkActionFormSubmitButton.innerHTML;
      listBulkActionFormSubmitButton.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"> <span class="sr-only">Working...</span> </div> WORKING...';

      const bulkAction = document.querySelector('#listBulkActionSelect').value;

      if(bulkAction != '') {

        let items = [];

        let listItemCheckboxes = document.getElementsByClassName('listItemCheckbox');

        for(let i = 0; i < listItemCheckboxes.length; i++) {

          if(listItemCheckboxes[i].checked === true) {

            // Create a new object...
            let item = {};

            //... with the following properties and values
            item.contentType = contentType;
            item.id = listItemCheckboxes[i].dataset.id;
            item.slug = listItemCheckboxes[i].dataset.slug;

            items.push(item);
          }
        }

        if(items.length > 0) {

          const itemsToProcess = items.length;
          items = JSON.stringify(items);

          // Debug
          //console.log(items);

          const formData = new FormData();

          formData.append('siteKey', siteKey);
          formData.append('contentType', contentType);
          formData.append('items', items);
          formData.append('csrfPreventionToken', csrfPreventionToken);

          let alertSuccessTitle = '';
          let alertErrorTitle = '';

          if(bulkAction === 'generate') {

            formData.append('doAsync', 'generateStaticFiles');
          }
          else if(bulkAction === 'delete') {

            formData.append('doAsync', 'deleteStaticFiles');
          }

          apiGetData(formData).then(function(totalProcessed) {

            totalProcessed = parseInt(totalProcessed);

            if(totalProcessed === itemsToProcess) {

              if(bulkAction === 'generate') {

                alertSuccessTitle = 'STATIC FILE(S) GENERATED';
              }
              else if(bulkAction === 'delete') {

                alertSuccessTitle = 'STATIC FILE(S) DELETED';
              }

              Swal.fire({
                title: alertSuccessTitle,
                text: `${totalProcessed} of ${itemsToProcess} items successfully processed.`,
                icon: 'success',
                confirmButtonText: 'OK'
              });
            }
            else {

              Swal.fire({
                title: 'ERROR ENCOUNTERED',
                text: `${totalProcessed} of ${itemsToProcess} items processed.`,
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }

            buildList();

            // Set UI
            listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button
            listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
          });
        }
        else {

          Swal.fire({
            title: 'NO ITEMS SELECTED',
            text: 'Please select at least one item to perform this action.',
            icon: 'warning',
            confirmButtonText: 'OK'
          });

          // Set UI
          listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button
          listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
        }
      }
      else {

        Swal.fire({
          title: 'NO BULK ACTION SELECTED',
          text: 'Please select an action to perform on the checked items.',
          icon: 'warning',
          confirmButtonText: 'OK'
        });

        // Set UI
        listBulkActionFormSubmitButton.classList.remove('disabled'); // Re-enable button
        listBulkActionFormSubmitButton.innerHTML = originalButtonText; // Restore original button text
      }
    }


    //--------------------------------------------------------------------------
    /**
     *
     */
    //--------------------------------------------------------------------------
    function buildMenuItems() {

      // Set UI
      loadingSpinner.classList.remove('invisible'); // Show loading spinner
      const list = document.querySelector('#list');
      list.classList.add('invisible'); // Hide list
      const menusTable = document.querySelector('#menusTable');
      menusTable.classList.add('invisible'); // Hide menus table
      const noItemsFoundText = document.querySelector('#noItemsFoundText');
      noItemsFoundText.classList.add('invisible'); // Hide text

      let rowsHtml = '';
      let staticFileColumnHtml = '';

      const menusTableBody = document.querySelector('#menusTableBody');

      // Get all static files if available...

      const formData = new FormData();

      formData.append('siteKey', siteKey);
      formData.append('doAsync', 'getStaticFilesInfo');
      formData.append('contentType', 'menu');
      formData.append('csrfPreventionToken', csrfPreventionToken);

      apiGetData(formData).then(function(staticFiles) {

        const menuSlugs = [primaryMenuSlug, footerMenuSlug];

        // For each menu...
        for(let i = 0; i < menuSlugs.length; i++) {

          const formData = new FormData();

          formData.append('siteKey', siteKey);
          formData.append('doAsync', 'getMenuItems');
          formData.append('menuSlug', menuSlugs[i]);
          formData.append('csrfPreventionToken', csrfPreventionToken);

          apiGetData(formData).then(function(menuItems) {

            // Set UI
            loadingSpinner.classList.add('invisible'); // Hide loading spinner
            noItemsFoundText.classList.remove('invisible'); // Show text by default (unless at least menu exists on CMS)

            // If the menu exists in CMS
            if(menuItems.length > 0) {

              staticFileColumnHtml = `<div class="staticFileColumnContent" id="menuActionsSpinner_${menuSlugs[i]}" style="display: none;"><div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Working...</span></div> Working...</div>
                                      <div class="staticFileColumnContent" id="menuStatusText_${menuSlugs[i]}">Not found</div>
                                      <div class="staticFileColumnContent" id="menuActions_${menuSlugs[i]}"><a class="menuGenerateLink" data-slug="${menuSlugs[i]}" href="#">GENERATE</a></div>`;

              for(let j = 0; j < staticFiles.length; j++) {

                let fileSlug = staticFiles[j].fileName.replace('.html', ''); // Remove .html from file name

                // If static file exists for the menu...
                if(fileSlug === menuSlugs[i]) {

                  staticFileColumnHtml = `<div class="staticFileColumnContent" id="menuActionsSpinner_${menuSlugs[i]}" style="display: none;"><div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Working...</span></div> Working...</div>
                                          <div class="staticFileColumnContent" id="menuStatusText_${menuSlugs[i]}">Generated ${staticFiles[j].fileModified}</div>
                                          <div class="staticFileColumnContent" id="menuActions_${menuSlugs[i]}"><a class="menuRegenerateLink" data-slug="${menuSlugs[i]}" href="#">REGENERATE</a> | <a class="menuDeleteLink" data-slug="${menuSlugs[i]}" href="#">DELETE</a></div>`;
                  break;
                }
              }

              rowsHtml = rowsHtml + `

              <tr>
                <td class="small">
                  ${menuSlugs[i].replace('-', ' ')}
                </td>
                <td class="small">
                  ${menuSlugs[i]}
                </td>
                <td class="small">
                  ${staticFileColumnHtml}
                </td>
              </tr>
              `;

              menusTableBody.innerHTML = rowsHtml;

              // Set UI
              loadingSpinner.classList.add('invisible'); // Hide loading spinner
              noItemsFoundText.classList.add('invisible'); // Hide text
              menusTable.classList.remove('invisible'); // Show menus table
            }

          });
        }
      });
    }


    //--------------------------------------------------------------------------
    /**
     * @param array params
     */
    //--------------------------------------------------------------------------
    function buildList(params = []) {

      // Set UI
      loadingSpinner.classList.remove('invisible'); // Show loading spinner
      const list = document.querySelector('#list');
      list.classList.add('invisible'); // Hide list
      const menusTable = document.querySelector('#menusTable');
      menusTable.classList.add('invisible'); // Hide menus table
      const noItemsFoundText = document.querySelector('#noItemsFoundText');
      noItemsFoundText.classList.add('invisible'); // Hide text

      const contentTypeFirstLetterCapped = contentType.charAt(0).toUpperCase() + contentType.substring(1); // Capitalise first letter of word (e.g. from "page" to "Page")
      const listTableBody = document.querySelector('#listTableBody');

      const formData = new FormData();

      formData.append('siteKey', siteKey);
      formData.append('doAsync', `get${contentTypeFirstLetterCapped}s`);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      if(params['perPage'] !== undefined) {

        formData.append('perPage', parseInt(params['perPage']));
      }
      else {

        formData.append('perPage', parseInt(sfmListItemsPerPage));
      }

      if(params['pageNumber'] !== undefined) {

        formData.append('pageNumber', parseInt(params['pageNumber']));
      }
      else {

        formData.append('pageNumber', parseInt(sessionStorage.getItem('currentLinkNumber')));
      }

      if(params['orderBy'] !== undefined) {

        formData.append('orderBy', params['orderBy']);
      }

      if(params['order'] !== undefined) {

        formData.append('order', params['order']);
      }

      // Get items (pages or posts) from CMS
      apiGetData(formData).then(function(items) {

        // Page(s) or post(s) found on CMS
        if(items.length > 0) {

          const formData = new FormData();

          formData.append('siteKey', siteKey);
          formData.append('doAsync', 'getStaticFilesInfo');
          formData.append('contentType', contentType);
          formData.append('csrfPreventionToken', csrfPreventionToken);

          // Get all static files info (if available)
          apiGetData(formData).then(function(staticFiles) {

            // Debug
            //console.log(staticFiles);

            let rowsHtml = '';
            let staticFileColumnHtml = '';

            for(let i = 0; i < items.length; i++) {

              const cmsContentModified = new Date(items[i].modified).toLocaleString('en-GB', {timeZone: 'UTC'});

              staticFileColumnHtml = `<div class="staticFileColumnContent" id="listItemActionsSpinner_${items[i].slug}" style="display: none;"><div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Working...</span></div> Working...</div>
                                      <div class="staticFileColumnContent" id="listItemStatusText_${items[i].slug}">Not found</div>
                                      <div class="staticFileColumnContent" id="listItemActions_${items[i].slug}"><a class="listItemGenerateLink" data-id="${items[i].id}" data-slug="${items[i].slug}" href="#">GENERATE</a></div>`;

              for(let j = 0; j < staticFiles.length; j++) {

                let fileSlug = staticFiles[j].fileName.replace('.html', ''); // Remove .html from file name

                const regex = new RegExp('^.*(?=' + staticPostsPageFileNumberSeparator + '[0-9])');
                const postsPageSlug = fileSlug.match(regex);

                // If static file exists for the item...
                if(fileSlug === items[i].slug || (postsPageSlug !== null && postsPageSlug[0] === items[i].slug)) {

                  staticFileColumnHtml = `<div class="staticFileColumnContent" id="listItemActionsSpinner_${items[i].slug}" style="display: none;"><div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Working...</span></div> Working...</div>
                                          <div class="staticFileColumnContent" id="listItemStatusText_${items[i].slug}">Generated ${staticFiles[j].fileModified}</div>
                                          <div class="staticFileColumnContent" id="listItemActions_${items[i].slug}"><a class="listItemViewLink" href="/${items[i].slug}/" target="_blank">VIEW</a> | <a class="listItemRegenerateLink" data-id="${items[i].id}" data-slug="${items[i].slug}" href="#">REGENERATE</a> | <a class="listItemDeleteLink" data-id="${items[i].id}" data-slug="${items[i].slug}" href="#">DELETE</a></div>`;

                  break;
                }
              }

              rowsHtml = rowsHtml + `

              <tr>
                <th scope="row">
                  <input class="listItemCheckbox" type="checkbox" data-id="${items[i].id}" data-slug="${items[i].slug}">
                </th>
                <td class="small">
                  ${items[i].title.rendered}
                </td>
                <td class="small">
                  ${items[i].slug}
                </td>
                <td class="small">
                  ${cmsContentModified}<br><a href="${cmsBaseUrl}${items[i].slug}" target="_blank">VIEW ON WP</a>
                </td>
                <td class="small">
                  ${staticFileColumnHtml}
                </td>
              </tr>
              `;
            }

            // Debug
            //console.log(rowsHtml);

            listTableBody.innerHTML = rowsHtml;

            // Set UI
            loadingSpinner.classList.add('invisible'); // Hide loading spinner
            list.classList.remove('invisible'); // Show list
            listItemsBulkSelectCheckbox.checked = false; // Uncheck bulk select checkbox
          });
        }
        // No page(s) or post(s) found on CMS
        else {

          // Set UI
          loadingSpinner.classList.add('invisible'); // Hide loading spinner
          noItemsFoundText.classList.remove('invisible'); // Show text
        }

      });
    }


    //--------------------------------------------------------------------------
    /**
     * @param array params
     */
    //--------------------------------------------------------------------------
    function buildPagination(params = []) {

      const contentTypeFirstLetterCapped = contentType.charAt(0).toUpperCase() + contentType.substring(1); // Capitalise first letter of word (e.g. from "page" to "Page")
      const formData = new FormData();

      formData.append('siteKey', siteKey);
      formData.append('doAsync', `getTotal${contentTypeFirstLetterCapped}s`);
      formData.append('csrfPreventionToken', csrfPreventionToken);

      // Get the total number of published items from CMS
      apiGetData(formData).then(function(response) {

        const totalPublishedItems = parseInt(response);
        const perPage = parseInt(sfmListItemsPerPage);
        const currentLinkNumber = parseInt(sessionStorage.getItem('currentLinkNumber'));

        if(params['perPage'] !== undefined) {

          perPage = params['perPage'];
        }

        if(params['pageNumber'] !== undefined) {

          currentLinkNumber = params['pageNumber'];
        }

        const totalLinks = Math.ceil(totalPublishedItems / perPage);

        // Only display pagination if there are more than one pagination pages
        if(totalLinks > 1 && totalPublishedItems > perPage) {

          const listPagination = document.querySelector('#listPagination');

          let previousLinkHtml = '';
          let numberedLinksHtml = '';
          let nextLinkHtml = '';
          let paginationHtml = '';

          if(currentLinkNumber > 1) {

            previousLinkHtml = `
              <li class="page-item">
                <a class="page-link listPaginationLink" data-pagination-link-number="${(currentLinkNumber - 1)}" href="#">&laquo;</a>
              </li>
            `;
          }

          for(let i = 0; i < totalLinks; i++) {

            if((i + 1) === currentLinkNumber) {

              numberedLinksHtml = numberedLinksHtml + `
                <li class="page-item active" aria-current="page">
                  <a class="page-link listPaginationLink" data-pagination-link-number="${(i + 1)}" href="#">${(i + 1)}</a>
                </li>
              `;
            }
            else {

              numberedLinksHtml = numberedLinksHtml + `
                <li class="page-item">
                  <a class="page-link listPaginationLink" data-pagination-link-number="${(i + 1)}" href="#">${(i + 1)}</a>
                </li>
              `;
            }
          }

          if(currentLinkNumber !== totalLinks) {

            nextLinkHtml = `
              <li class="page-item">
                <a class="page-link listPaginationLink" data-pagination-link-number="${(currentLinkNumber + 1)}" href="#">&raquo;</a>
              </li>
            `;
          }

          paginationHtml = previousLinkHtml + numberedLinksHtml + nextLinkHtml;

          // Debug
          //console.log(paginationHtml);

          listPagination.innerHTML = paginationHtml;

          // Pagination info text...

          const listPaginationInfo = document.querySelector('#listPaginationInfo');
          let fromPaginationItem = (currentLinkNumber * perPage) + 1 - perPage;
          let toPaginationItem = currentLinkNumber * perPage;

          if(currentLinkNumber === totalLinks) {

            toPaginationItem = (currentLinkNumber * perPage) - ((currentLinkNumber * perPage) - totalPublishedItems);
          }

          listPaginationInfo.innerHTML = `Showing ${fromPaginationItem} - ${toPaginationItem} of ${totalPublishedItems} items`;
        }
        // Clear out any HTML, if there were previously any
        else {

          listPagination.innerHTML = '';
          listPaginationInfo.innerHTML = '';
        }

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

          // ...
        }

      }).then(function(data) {

        return data;
      }).catch(function(error) {

        console.log('Error: ' + error);

      });

    }

  })();
});
