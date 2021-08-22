<main>
  <div class="container">
    <div class="row">

      <div class="col">
        <h2 class="text-center m-5">SETUP</h2>
        <p class="text-center mb-5">Set a site key to protect access to this site's admin pages (e.g. <i>Settings</i> page).</p>
      </div>

    </div>

    <div class="row">

      <div class="col mx-5 px-sm-5">

        <form id="setup" class="mx-5 px-sm-5">

          <div class="row mb4">

            <div class="input-group">
              <input id="siteKey" name="siteKey" type="text" class="form-control" aria-describedby="siteKeyHelp" value="<?php if(isset($siteKey)) { echo $siteKey; } ?>" readonly>
              <div class="input-group-append">
                <button id="regenerateSiteKey" type="button" class="btn btn-secondary">REGENERATE SITE KEY</button>
              </div>
            </div>
            <small id="siteKeyHelp" class="form-text text-muted">You will be asked to enter this site key every time you attempt to access any admin pages on your Reflect site.</small>

          </div>

          <div class="text-center my-5">
            <p><b>Make sure to copy and store your site key somewhere safe before hitting SAVE!</b><br>Otherwise you will need to have access to your server to retrieve your site key.</p>
            <button type="submit" class="btn btn-primary">SAVE</button>
          </div>

        </form>

      </div>

    </div>
  </div>
</main>
