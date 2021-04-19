<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css?version=1.0.0-beta.12">

    <title><?php if(isset($addonConfig['addonName']) && !empty($addonConfig['addonName'])) { echo $addonConfig['addonName'];} ?> Addon Settings</title>

  </head>

  <body>

    <header>

    </header>

    <main>
      <div class="container">
        <div class="row">

          <div class="col-sm-2">

          </div>

          <div class="col-sm-8 mb-2">
            <h2 class="text-center m-5"><?php if(isset($addonConfig['addonName']) && !empty($addonConfig['addonName'])) { echo strtoupper($addonConfig['addonName']);} ?> ADDON SETTINGS</h2>
            <p class="text-center"><?php if(isset($addonConfig['addonDescription']) && !empty($addonConfig['addonDescription'])) { echo $addonConfig['addonDescription'];} ?></p>
          </div>

          <div class="col-sm-2">

          </div>

        </div>

        <div class="row mt-5">

          <div class="col-sm-2">

          </div>

          <div class="col-sm-8">

            <form id="reflectFormMailerAddonSettings">

              <h5 class="mt-5">GENERAL OPTIONS</h5>

              <hr class="mb-5">

              <div class="form-group mb-5">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input switch" id="runOnHomePage" <?php if($addonConfig['runOnHomePage'] === 'true') { echo 'checked'; } ?>>
                  <input type="hidden" name="runOnHomePage" value="<?php if(isset($addonConfig['runOnHomePage'])) { echo $addonConfig['runOnHomePage']; } ?>">
                  <label class="custom-control-label" for="runOnHomePage">RUN ON HOME PAGE</label>
                </div>
              </div>

              <div class="form-group">
                <label for="slugsToRun">SLUGS TO RUN <small>(OPTIONAL)</small></label>
                <input type="text" class="form-control" id="slugsToRun" name="slugsToRun" aria-describedby="slugsToRunHelp" value="<?php if(isset($addonConfig['slugsToRun']) && !empty($addonConfig['slugsToRun'])) { echo $addonConfig['slugsToRun']; } ?>">
                <small id="slugsToRunHelp" class="form-text text-muted">Enter the slug for each page you want the addon to run. <b>Separate each slug by comma.</b> Leave blank if you have already entered any slugs in the <i>SLUGS TO EXCLUDE</i> option below or if you wish to allow the addon to run on every page.</small>
              </div>

              <div class="form-group">
                <label for="slugsToExclude">SLUGS TO EXCLUDE <small>(OPTIONAL)</small></label>
                <input type="text" class="form-control" id="slugsToExclude" name="slugsToExclude" aria-describedby="slugsToExcludeHelp" value="<?php if(isset($addonConfig['slugsToExclude']) && !empty($addonConfig['slugsToExclude'])) { echo $addonConfig['slugsToExclude']; } ?>">
                <small id="slugsToExcludeHelp" class="form-text text-muted">Enter the slug for each page you DON'T want the addon to run. <b>Separate each slug by comma.</b> Leave blank if you have already entered any slugs in the <i>SLUGS TO RUN</i> option above or if you wish to allow the addon to run on every page.</small>
              </div>

              <div class="form-group">
                <label for="formNamesToTarget">FORMS TO TARGET <small>(OPTIONAL)</small></label>
                <input type="text" class="form-control" id="formNamesToTarget" name="formNamesToTarget" aria-describedby="formNamesToTargetHelp" value="<?php if(isset($addonConfig['formNamesToTarget']) && !empty($addonConfig['formNamesToTarget'])) { echo $addonConfig['formNamesToTarget']; } ?>">
                <small id="formNamesToTargetHelp" class="form-text text-muted">Enter the name of each form you want to target. <b>Separate each name by comma.</b> Leave blank if you have already entered any form names in the <i>FORMS TO EXCLUDE</i> option below or if you wish to target all forms.</small>
              </div>

              <div class="form-group">
                <label for="formNamesToExclude">FORMS TO EXCLUDE <small>(OPTIONAL)</small></label>
                <input type="text" class="form-control" id="formNamesToExclude" name="formNamesToExclude" aria-describedby="formNamesToExcludeHelp" value="<?php if(isset($addonConfig['formNamesToExclude']) && !empty($addonConfig['formNamesToExclude'])) { echo $addonConfig['formNamesToExclude']; } ?>">
                <small id="formNamesToExcludeHelp" class="form-text text-muted">Enter the name of each form you DON'T want to target. <b>Separate each name by comma.</b> Leave blank if you have already entered any form names in the <i>FORMS TO TARGET</i> option above or if you wish to target all forms.</small>
              </div>

              <h5 class="mt-5">EMAIL HEADER SETTINGS</h5>

              <hr class="mb-5">

              <div class="form-group">
                <label for="recipientName"><i>TO</i> NAME</label>
                <input type="text" class="form-control" id="recipientName" name="recipientName" aria-describedby="recipientNameHelp" value="<?php if(isset($addonConfig['recipientName'])) { echo $addonConfig['recipientName']; } ?>" required>
                <small id="recipientNameHelp" class="form-text text-muted"></small>
              </div>

              <div class="form-group">
                <label for="recipientEmailAddress"><i>TO</i> EMAIL ADDRESS</label>
                <input type="email" class="form-control" id="recipientEmailAddress" name="recipientEmailAddress" aria-describedby="recipientEmailAddressHelp" value="<?php if(isset($addonConfig['recipientEmailAddress'])) { echo $addonConfig['recipientEmailAddress']; } ?>" required>
                <small id="recipientEmailAddressHelp" class="form-text text-muted">The email address that will be receiving user submitted form data.</small>
              </div>

              <div class="form-group">
                <label for="fromName"><i>FROM</i> NAME</label>
                <input type="text" class="form-control" id="fromName" name="fromName" aria-describedby="fromNameHelp" value="<?php if(isset($addonConfig['fromName']) && !empty($addonConfig['fromName'])) { echo $addonConfig['fromName']; } else { echo $config['siteName']; } ?>" required>
                <small id="fromNameHelp" class="form-text text-muted"></small>
              </div>

              <div class="form-group">
                <label for="fromEmailAddress"><i>FROM</i> EMAIL ADDRESS</label>
                <input type="email" class="form-control" id="fromEmailAddress" name="fromEmailAddress" aria-describedby="fromEmailAddressHelp" value="<?php if(isset($addonConfig['fromEmailAddress']) && !empty($addonConfig['fromEmailAddress'])) { echo $addonConfig['fromEmailAddress']; } ?>" required>
                <small id="fromEmailAddressHelp" class="form-text text-muted"><span class="badge bg-warning text-dark">Warning</span> To avoid triggering your email server's spam filter, please use a real email address.</small>
              </div>

              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input switch" id="attachFormDataToEmail" <?php if($addonConfig['attachFormDataToEmail'] === 'true') { echo 'checked'; } ?>>
                  <input type="hidden" name="attachFormDataToEmail" value="<?php if(isset($addonConfig['attachFormDataToEmail'])) { echo $addonConfig['attachFormDataToEmail']; } ?>">
                  <label class="custom-control-label" for="attachFormDataToEmail">ATTACH DATA AS CSV FILE TO EMAIL</label>
                </div>
              </div>

              <input type="hidden" name="siteKey" value="<?php echo $config['siteKey']; ?>">

              <div class="text-center my-5">
                <a class="btn btn-secondary" href="//<?php echo $_SERVER['SERVER_NAME']; ?>">HOME</a>
                <button type="submit" class="btn btn-primary">SAVE</button>
              </div>

            </form>

          </div>

          <div class="col-sm-2">

          </div>

        </div>
      </div>
    </main>


    <footer class="footer mt-auto py-3">
      <div class="container">

        <p class="clearfix text-center text-muted"><small><?php echo $config['siteCopyrightStatement']; ?></small></p>

      </div>
    </footer>


    <span id="csrfPreventionToken" data-csrf-prevention-token="<?php if(isset($_SESSION['csrfPreventionToken'])) { echo $_SESSION['csrfPreventionToken']; } ?>"></span>


    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <!-- Polyfill (will be removed in the future) -->
      <script src="/js/polyfill/core-js/minified.js"></script>
      <script src="/js/polyfill/regenerator-runtime/runtime.js"></script>
      <script src="/js/polyfill/unfetch/index.js"></script>

    <?php endif; ?>

    <!-- Bootstrap -->
    <script src="/js/bootstrap/jquery-3.5.1.slim.min.js?version=1.0.0-beta.12"></script>
    <script src="/js/bootstrap/bootstrap.bundle.min.js?version=1.0.0-beta.12"></script>

    <!-- SweetAlert2 -->
    <script src="/js/sweetalert2/sweetalert2.all.min.js?version=1.0.0-beta.12"></script>

    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <script>
        <?php require_once __DIR__ . '/../js/transpiled/settings.js'; ?>
      </script>

    <?php else: ?>

      <script>
        <?php require_once __DIR__ . '/../js/settings.js'; ?>
      </script>

    <?php endif; ?>

  </body>
</html>
