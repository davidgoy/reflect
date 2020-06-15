<main>
  <div class="container">
    <div class="row">

      <div class="col-sm-2">

      </div>

      <div class="col-sm-8 mb-2">
        <h2 class="text-center m-5">SETUP</h2>
        <p class="text-center mb-5">Set a site key to protect access to this site's admin pages (e.g. <i>Settings</i> page).</p>
      </div>

      <div class="col-sm-2">

      </div>

    </div>

    <div class="row">

      <div class="col-sm-2">

      </div>

      <div class="col-sm-8">

        <form id="setup">

          <label for="siteKey">SITE KEY</label>
          <div class="input-group mb-3">
            <input id="siteKey" name="siteKey" type="text" class="form-control" aria-describedby="siteKeyHelp" value="<?php if(isset($siteKey)) { echo $siteKey; } ?>" readonly>
            <div class="input-group-append">
              <button id="regenerateSiteKey" type="button" class="btn btn-secondary">REGENERATE</button>
            </div>
          </div>
          <small id="siteKeyHelp">You will be asked to enter this site key every time you attempt to access any admin pages on your Reflect site.</small>

          <p class="text-center mt-5"><span class="badge badge-warning">Attention</span> <b>Make sure to copy and store your site key somewhere safe before hitting SAVE!</b><br>Otherwise you will need to have access to your server to retrieve your site key.</p>

          <div class="text-center my-5">
            <button type="submit" class="btn btn-primary">SAVE</button>
          </div>
        </form>

      </div>

      <div class="col-sm-2">

      </div>

    </div>
  </div>
</main>
