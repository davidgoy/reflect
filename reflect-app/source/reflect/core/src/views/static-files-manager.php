<main>
  <div class="container">

    <div class="row">

      <div class="col">

        <h2 class="text-center m-5">STATIC FILES MANAGER</h2>
        <p class="text-center">The static site generator for your Reflect site.</p>

      </div>

    </div>

    <div class="row mt-5">

      <div class="col mx-5 px-sm-5">

        <p id="selectListToDisplayText" class="text-center mb-3">Select a list to display...</p>

        <div class="d-flex justify-content-center">

          <div class="btn-group" role="group" aria-label="">

            <button id="getMenusButton" class="btn btn-lg btn-outline-dark">&nbsp;&nbsp;&nbsp;MENUS&nbsp;&nbsp;&nbsp;</button>

            <button id="getPageListButton" class="btn btn-lg btn-outline-dark">&nbsp;&nbsp;&nbsp;PAGES&nbsp;&nbsp;&nbsp;</button>

            <button id="getPostListButton" class="btn btn-lg btn-outline-dark">&nbsp;&nbsp;&nbsp;POSTS&nbsp;&nbsp;&nbsp;</button>

          </div>

          <input type="hidden" id="siteKey" value="<?php echo $config['siteKey']; ?>">
          <input type="hidden" id="sfmListItemsPerPage" value="<?php echo $config['sfmListItemsPerPage']; ?>">
          <input type="hidden" id="cmsBaseUrl" value="<?php echo $config['cmsProtocol'] . '://' . $config['cmsDomain'] . '/'; ?>">
          <input type="hidden" id="staticPostsPageFileNumberSeparator" value="<?php echo $config['staticPostsPageFileNumberSeparator']; ?>">
          <input type="hidden" id="cmsPostsPageSlug" value="<?php echo $config['cmsPostsPageSlug']; ?>">
          <input type="hidden" id="primaryMenuSlug" value="<?php echo $config['primaryMenuSlug']; ?>">
          <input type="hidden" id="footerMenuSlug" value="<?php echo $config['footerMenuSlug']; ?>">

        </div>

      </div>

    </div>

    <div class="row mt-5">

      <div class="col mx-5 px-sm-5">

        <h2 id="textHeading" class="text-center m-5"></h2>

        <div id="loadingSpinner" class="invisible text-center">

            <div class="spinner-border text-secondary" role="status">

            </div>

        </div>

        <p id="noItemsFoundText" class="text-center invisible"></p>

        <div id="menusTable" class="invisible table-responsive">

          <table class="table table-hover table-bordered">

            <thead>
              <tr class="table-dark">

                <th scope="col">
                  NAME
                </th>

                <th scope="col">
                  SLUG
                </th>

                <th scope="col">
                  STATIC FILE
                </th>

              </tr>
            </thead>

            <tbody id="menusTableBody">

            </tbody>

          </table>

        </div>

        <div id="list" class="invisible" style="margin-top: -9.8em;">

          <form id="listBulkActionForm">
            <div class="row align-items-center">
              <div class="col my-1">
                <select id="listBulkActionSelect" name="listBulkActionSelect" class="form-select">
                  <option value="" selected>Bulk action...</option>
                  <option value="generate">Generate</option>
                  <option value="delete">Delete</option>
                </select>
              </div>
              <input type="hidden" name="siteKey" value="<?php echo $config['siteKey']; ?>">
              <div class="col-auto my-1">
                <button id="listBulkActionFormSubmitButton" type="submit" class="btn btn-sm btn-primary">APPLY</button>
              </div>
            </div>
          </form>


          <div id="listTable" class="table-responsive">

            <table class="table table-hover table-bordered table-sm">

              <thead>

                <tr class="table-dark">

                  <th scope="col">
                    <input class="form-check-input" id="listItemsBulkSelectCheckbox" type="checkbox">
                  </th>

                  <th scope="col">
                    TITLE
                  </th>

                  <th scope="col">
                    SLUG
                  </th>

                  <th scope="col">
                    LAST MODIFIED
                  </th>

                  <th scope="col">
                    STATIC FILE
                  </th>

                </tr>

              </thead>

              <tbody id="listTableBody">

              </tbody>

            </table>

          </div>

          <div class="row my-5">

            <div class="col">

              <nav class="d-flex justify-content-center" aria-label="Page navigation">
                <ul id="listPagination" class="pagination">

                </ul>
              </nav>

              <p class="text-center mb-3" id="listPaginationInfo"></p>

            </div>

          </div>

        </div>

      </div>

    </div>

    <div class="row">

      <div class="col mx-5 px-sm-5">

        <hr class="mb-5">

        <form id="sfmForm">

          <div class="row my-5">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input switch" id="staticMode" <?php if($config['staticMode'] === 'true') { echo 'checked'; } ?>>
              <input type="hidden" name="staticMode" value="<?php if(isset($config['staticMode'])) { echo $config['staticMode']; } ?>">
              <label class="custom-control-label" for="staticMode">STATIC MODE</label>
            </div>
          </div>

          <div class="row mb-4">
            <label class="form-label" for="documentRoot">DOCUMENT ROOT</label>
            <input type="text" class="form-control" id="documentRoot" name="documentRoot" aria-describedby="documentRootHelp" value="<?php if(isset($config['documentRoot'])  && !empty($config['documentRoot'])) { echo $config['documentRoot']; } ?>" required>
            <small id="documentRootHelp" class="form-text text-muted">The root folder (i.e. web root) of your Reflect site. (e.g. <i>public_html</i> on some shared hosting platforms)</small>
          </div>

          <div class="row mb-4">
            <label class="form-label" for="staticFolderName">STATIC FILES FOLDER NAME</label>
            <input type="text" class="form-control" id="staticFolderName" name="staticFolderName" aria-describedby="staticFolderNameHelp" value="<?php if(isset($config['staticFolderName']) && !empty($config['staticFolderName'])) { echo $config['staticFolderName']; } ?>" required>
            <small id="staticFolderNameHelp" class="form-text text-muted">The name of the folder that will be created inside the document root to store pre-generated static files.</small>
          </div>

          <input type="hidden" name="siteKey" value="<?php echo $config['siteKey']; ?>">

          <div class="text-center my-5">
            <a class="btn btn-secondary" href="//<?php echo $_SERVER['SERVER_NAME']; ?>">HOME</a>
            <button type="submit" class="btn btn-primary">SAVE</button>
          </div>

        </form>

      </div>

    </div>
  </div>
</main>
