<main>
  <div class="container">
    <div class="row">
      <div class="col text-center mx-auto">
        <h2 class="m-5">AUTHENTICATION REQUIRED</h2>
        <p>To proceed, please enter your site key:</p>
      </div>
    </div>

    <div class="row">

      <div class="col-sm-2">

      </div>

      <div class="col-sm-8">

        <form id="authentication">
          <div class="form-group">
            <input type="password" autocomplete="off" class="form-control" id="siteKey" name="siteKey" aria-describedby="siteKeyHelp" placeholder="ENTER SITE KEY" value="">
            <input type="hidden" id="targetAction" name="targetAction" value="<?php echo $targetAction; ?>">
            <input type="hidden" id="themeFolderName" name="themeFolderName" value="<?php if(isset($params['themeFolderName'])) { echo $params['themeFolderName']; } ?>">
            <input type="hidden" id="addonFolderName" name="addonFolderName" value="<?php if(isset($params['addonFolderName'])) { echo $params['addonFolderName']; } ?>">
          </div>

          <div class="text-center my-5">
            <a class="btn btn-secondary" href="//<?php echo $_SERVER['SERVER_NAME']; ?>">CANCEL</a>
            <button id="authenticateButton" type="submit" class="btn btn-primary">
              <span id="authenticateButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              <span class="sr-only">Authenticating...</span>
              <span id="authenticateButtonText">AUTHENTICATE</span>
            </button>
          </div>

        </form>

      </div>

      <div class="col-sm-2">

      </div>

    </div>
  </div>
</main>
