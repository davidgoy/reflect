<main>
  <div class="container">
    <div class="row">

      <div class="col-sm-2">

      </div>

      <div class="col-sm-8">
        <h2 class="text-center m-5">SETTINGS</h2>
        <p class="text-center">Core Reflect app settings.</p>
      </div>

      <div class="col-sm-2">

      </div>

    </div>

    <div class="row mt-5">

      <div class="col-sm-2">

      </div>

      <div class="col-sm-8">

        <form id="settings">

          <h5 class="mt-5">WORDPRESS</h5>

          <hr class="mb-5">

          <label>URL</label>
          <div class="form-row">
            <div class="form-group">
              <select class="form-control" id="cmsProtocol" name="cmsProtocol" required>
                <option value="https" <?php if(isset($config['cmsProtocol']) && $config['cmsProtocol'] === 'https') { echo 'selected'; } ?>>https://</option>
                <option value="http" <?php if(isset($config['cmsProtocol']) && $config['cmsProtocol'] === 'http') { echo 'selected'; } ?>>http://</option>
              </select>
            </div>
            <div class="form-group ml-2">
              <input type="text" class="form-control" id="cmsDomain" name="cmsDomain" aria-describedby="cmsDomainHelp" value="<?php if(isset($config['cmsDomain']) && !empty($config['cmsDomain'])) { echo $config['cmsDomain']; } ?>" required>
              <small id="cmsDomainHelp" class="form-text text-muted">The domain of your WordPress site from which Reflect will fetch its content.</small>
            </div>
          </div>

          <div class="form-group">
            <label for="cmsHomePageSlug">HOME PAGE SLUG</label>
            <input type="text" class="form-control" id="cmsHomePageSlug" name="cmsHomePageSlug" aria-describedby="cmsHomePageSlugHelp" value="<?php if(isset($config['cmsHomePageSlug']) && !empty($config['cmsHomePageSlug'])) { echo $config['cmsHomePageSlug']; } ?>" required>
            <small id="cmsHomePageSlugHelp" class="form-text text-muted">The slug of the page in your WordPress site that you have set as the <i>Front page</i>.</small>
          </div>

          <div class="form-group">
            <label for="cmsPostsPageSlug">POSTS PAGE SLUG</label>
            <input type="text" class="form-control" id="cmsPostsPageSlug" name="cmsPostsPageSlug" aria-describedby="cmsPostsPageSlugHelp" value="<?php if(isset($config['cmsPostsPageSlug']) && !empty($config['cmsPostsPageSlug'])) { echo $config['cmsPostsPageSlug']; } ?>">
            <small id="cmsPostsPageSlugHelp" class="form-text text-muted">The slug of the page in your WordPress site that you have set as the <i>Blog page</i> (i.e. the page that displays your latest posts).</small>
          </div>

          <div class="form-group">
            <label for="primaryMenuSlug">SLUG FOR PRIMARY MENU</label>
            <input type="text" class="form-control" id="primaryMenuSlug" name="primaryMenuSlug" aria-describedby="primaryMenuSlugHelp" value="<?php if(isset($config['primaryMenuSlug']) && !empty($config['primaryMenuSlug'])) { echo $config['primaryMenuSlug']; } ?>" required>
            <small id="primaryMenuSlugHelp" class="form-text text-muted">If the name of your menu in your WordPress site is <i>Primary Menu</i>, then the slug would be <i>primary-menu</i>.</small>
          </div>

          <div class="form-group">
            <label for="footerMenuSlug">SLUG FOR FOOTER MENU</label>
            <input type="text" class="form-control" id="footerMenuSlug" name="footerMenuSlug" aria-describedby="footerMenuSlugHelp" value="<?php if(isset($config['footerMenuSlug']) && !empty($config['footerMenuSlug'])) { echo $config['footerMenuSlug']; } ?>" required>
            <small id="footerMenuSlugHelp" class="form-text text-muted">If the name of your menu in your WordPress site is <i>Footer Menu</i>, then the slug would be <i>footer-menu</i>.</small>
          </div>


          <h5 class="mt-5">GENERAL</h5>

          <hr class="mb-5">

          <div class="form-group">
            <label for="siteTheme">REFLECT THEME</label>
            <select class="form-control" id="siteTheme" name="siteTheme" required>
              <?php for($i = 0; $i < count($themesAvailable); $i++): ?>
              <option value="<?php echo $themesAvailable[$i]; ?>" <?php if($themesAvailable[$i] === $config['siteTheme']) { echo 'selected'; } ?>><?php echo ucwords(strtolower($themesAvailable[$i])); ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="siteName">SITE NAME</label>
            <input type="text" class="form-control" id="siteName" name="siteName" aria-describedby="siteNameHelp" value="<?php if(isset($config['siteName']) && !empty($config['siteName'])) { echo $config['siteName']; } ?>" required>
            <small id="siteNameHelp" class="form-text text-muted">E.g. My Awesome Reflect Site.</small>
          </div>

          <div class="form-group">
            <label for="siteCopyrightStatement">COPYRIGHT STATEMENT <small>(OPTIONAL)</small></label>
            <input type="text" class="form-control" id="siteCopyrightStatement" name="siteCopyrightStatement" aria-describedby="siteCopyrightStatementHelp" value="<?php if(isset($config['siteCopyrightStatement']) && !empty($config['siteCopyrightStatement'])) { echo $config['siteCopyrightStatement']; } ?>">
            <small id="siteCopyrightStatementHelp" class="form-text text-muted">The copyright statement for this site. This will be displayed at the bottom of every page.</small>
          </div>

          <div class="form-group">
            <label for="settingsPageSlug">SLUG FOR REFLECT SETTINGS PAGE</label>
            <input type="text" class="form-control" id="settingsPageSlug" name="settingsPageSlug" aria-describedby="settingsPageSlugHelp" value="<?php if(isset($config['settingsPageSlug']) && !empty($config['settingsPageSlug'])) { echo $config['settingsPageSlug']; } ?>" required>
            <small id="settingsPageSlugHelp" class="form-text text-muted">The unique slug for accessing Reflect's <i>Settings</i> page.</small>
          </div>

          <div class="form-group">
            <label for="sfmPageSlug">SLUG FOR STATIC FILES MANAGER PAGE</label>
            <input type="text" class="form-control" id="sfmPageSlug" name="sfmPageSlug" aria-describedby="sfmPageSlugHelp" value="<?php if(isset($config['sfmPageSlug']) && !empty($config['sfmPageSlug'])) { echo $config['sfmPageSlug']; } ?>" required>
            <small id="sfmPageSlugHelp" class="form-text text-muted">The unique slug for accessing Reflect's <i>Static Files Manager</i> page.</small>
          </div>

          <div class="form-group">
            <label for="postsPerPage">POSTS PER PAGE</label>
            <input type="number" min="1" max="100" class="form-control" id="postsPerPage" name="postsPerPage" aria-describedby="postsPerPageHelp" value="<?php if(isset($config['postsPerPage']) && !empty($config['postsPerPage'])) { echo $config['postsPerPage']; } ?>" required>
            <small id="postsPerPageHelp" class="form-text text-muted">Maximum number of posts to display in a Posts page.</small>
          </div>

          <div class="form-group my-5">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input switch" id="useTranspiledJs" <?php if($config['useTranspiledJs'] === 'true') { echo 'checked'; } ?>>
              <input type="hidden" name="useTranspiledJs" value="<?php if(isset($config['useTranspiledJs'])) { echo $config['useTranspiledJs']; } ?>">
              <label class="custom-control-label" for="useTranspiledJs">INTERNET EXPLORER 11 BROWSER SUPPORT</label>
            </div>
          </div>

          <?php if(count($addonsAvailable) > 0): ?>

          <input type="hidden" name="addonsToLoad[]" value=""> <!-- Hidden input to make sure that "addonsToLoad[]" is set for PHP to process -->

          <div class="card my-4">
            <div class="card-header">
              REFLECT ADDONS TO LOAD
              <br>
              <small>Enable any addons that your Reflect site needs.</small>
            </div>
            <div class="card-body">

              <div class="form-group">

                <?php for($i = 0; $i < count($addonsAvailable); $i++): ?>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="<?php echo $addonsAvailable[$i]; ?>" id="addon<?php echo $i; ?>" name="addonsToLoad[]" <?php if(in_array($addonsAvailable[$i], $addonsToLoad)) { echo 'checked'; } ?>>
                  <label class="form-check-label">
                    <?php echo ucwords(strtolower(str_replace('-', ' ', $addonsAvailable[$i]))); ?> <span id="addon<?php echo $i; ?>Settings"><a href="<?php echo '//' . $_SERVER['SERVER_NAME'] . '/' . $config['settingsPageSlug'] . '/addons/' . $addonsAvailable[$i] . '/'; ?>" target="_blank"><?php if(!empty($config['cmsDomain'])): ?>&nbsp;&nbsp;&nbsp;Edit Settings<?php endif; ?></a></span>
                  </label>
                </div>

                <?php endfor; ?>

              </div>

            </div>
          </div>

          <?php endif; ?>

          <h5 class="mt-5">UNDER MAINTENANCE</h5>

          <hr class="mb-5">

          <div class="form-group my-5">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input switch" id="underMaintenanceMode" <?php if($config['underMaintenanceMode'] === 'true') { echo 'checked'; } ?>>
              <input type="hidden" name="underMaintenanceMode" value="<?php if(isset($config['underMaintenanceMode'])) { echo $config['underMaintenanceMode']; } ?>">
              <label class="custom-control-label" for="underMaintenanceMode">UNDER MAINTENANCE MODE</label>
            </div>
          </div>

          <div class="form-group">
            <label for="cmsUnderMaintenancePageSlug">SLUG FOR UNDER MAINTENANCE PAGE <small>(REQUIRED FOR UNDER MAINTENANCE MODE)</small></label>
            <input type="text" class="form-control" id="cmsUnderMaintenancePageSlug" name="cmsUnderMaintenancePageSlug" aria-describedby="cmsUnderMaintenancePageSlugHelp" value="<?php if(isset($config['cmsUnderMaintenancePageSlug']) && !empty($config['cmsUnderMaintenancePageSlug'])) { echo $config['cmsUnderMaintenancePageSlug']; } ?>">
            <small id="cmsUnderMaintenancePageSlugHelp" class="form-text text-muted">You can create a page on your WordPress site to be used as your Reflect site's maintenance page.</small>
          </div>

          <div class="form-group">
            <label for="underMaintenanceExemptedIp">EXEMPTED IP ADDRESS <small>(OPTIONAL)</small></label>
            <input type="text" class="form-control" id="underMaintenanceExemptedIp" name="underMaintenanceExemptedIp" aria-describedby="underMaintenanceExemptedIpHelp" value="<?php if(isset($config['underMaintenanceExemptedIp']) && !empty($config['underMaintenanceExemptedIp'])) { echo $config['underMaintenanceExemptedIp']; } ?>">
            <small id="underMaintenanceExemptedIpHelp" class="form-text text-muted">The IP address that will be allowed to freely view this Reflect site while it is on <i>Under Maintenance</i> mode. For your info, we have detected the IP address that you are currently using to view this page is &nbsp;&nbsp;<span class="badge badge-dark"><?php echo $_SERVER['REMOTE_ADDR']; ?></span>&nbsp;&nbsp; If this is the IP address from which you will be viewing your Reflect site while it is in <i>Under Maintenance</i> mode, then please enter this IP address here.</small>
          </div>

          <div class="form-group">
            <label for="underMaintenanceAffectedSlugs">SLUGS TO TARGET <small>(OPTIONAL)</small></label>
            <input type="text" class="form-control" id="underMaintenanceAffectedSlugs" name="underMaintenanceAffectedSlugs" aria-describedby="underMaintenanceAffectedSlugsHelp" value="<?php if(isset($config['underMaintenanceAffectedSlugs']) && !empty($config['underMaintenanceAffectedSlugs'])) { echo $config['underMaintenanceAffectedSlugs']; } ?>">
            <small id="underMaintenanceAffectedSlugsHelp" class="form-text text-muted">Page and post slugs that should be exclusively targetted for redirection to the maintenance page when on <i>Under Maintenance</i> mode. <b>Separate each slug by comma.</b> Leave blank if you have already entered any slugs in the <i>SLUGS TO EXCLUDE</i> option below.</small>
          </div>

          <div class="form-group">
            <label for="underMaintenanceExemptedSlugs">SLUGS TO EXCLUDE <small>(OPTIONAL)</small></label>
            <input type="text" class="form-control" id="underMaintenanceExemptedSlugs" name="underMaintenanceExemptedSlugs" aria-describedby="underMaintenanceExemptedSlugsHelp" value="<?php if(isset($config['underMaintenanceExemptedSlugs']) && !empty($config['underMaintenanceExemptedSlugs'])) { echo $config['underMaintenanceExemptedSlugs']; } ?>">
            <small id="underMaintenanceExemptedSlugsHelp" class="form-text text-muted">Page and post slugs that should be exempted from being redirected to the maintenance page when on <i>Under Maintenance</i> mode. <b>Separate each slug by comma.</b> Leave blank if you have already entered any slugs in the <i>SLUGS TO TARGET</i> option above.</small>
          </div>

          <input type="hidden" name="siteKey" value="<?php echo $config['siteKey']; ?>">

          <div class="text-center">
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
