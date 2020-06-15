<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">

    <!-- Pickr -->
    <link rel="stylesheet" href="/css/pickr/classic.min.css">

    <title><?php if(isset($themeConfig['themeName']) && !empty($themeConfig['themeName'])) { echo $themeConfig['themeName'];} ?> Theme Settings</title>

  </head>

  <body>

    <header>

    </header>

    <main>
      <div class="container">

        <div class="row">

          <div class="col-sm-2"></div>

          <div class="col-sm-8 mb-2">

            <h2 class="text-center m-5"><?php if(isset($themeConfig['themeName']) && !empty($themeConfig['themeName'])) { echo strtoupper($themeConfig['themeName']);} ?> THEME SETTINGS</h2>
            <p class="text-center"><?php if(isset($themeConfig['themeDescription']) && !empty($themeConfig['themeDescription'])) { echo $themeConfig['themeDescription'];} ?></p>

          </div>

          <div class="col-sm-2"></div>

        </div>

        <div class="row mt-5">

          <div class="col-sm-2">

          </div>

          <div class="col-sm-8">

            <h5 class="mt-5">GENERAL</h5>

            <hr class="mb-5">

            <form id="defaultThemeSettings">

              <div class="form-group">
                <label for="faviconUrl">FAVICON URL <small>(OPTIONAL)</small></label>
                <input type="url" class="form-control" id="faviconUrl" name="faviconUrl" aria-describedby="faviconUrlHelp" value="<?php if(isset($themeConfig['faviconUrl']) && !empty($themeConfig['faviconUrl'])) { echo $themeConfig['faviconUrl']; } ?>">
                <small id="faviconUrlHelp" class="form-text text-muted">Full URL to your favicon image file. E.g. https://example.com/img/favicon.png</small>
              </div>

              <div class="form-group">
                <label for="logoUrl">LOGO URL <small>(OPTIONAL)</small></label>
                <input type="url" class="form-control" id="logoUrl" name="logoUrl" aria-describedby="logoUrlHelp" value="<?php if(isset($themeConfig['logoUrl']) && !empty($themeConfig['logoUrl'])) { echo $themeConfig['logoUrl']; } ?>">
                <small id="logoUrlHelp" class="form-text text-muted">Full URL to your logo image file. E.g. https://example.com/img/logo.png</small>
              </div>

              <div class="form-group">
                <label for="mainContentSidePadding">MAIN CONTENT SIDE PADDING</label>
                <input type="number" min="0" max="100" class="form-control" id="mainContentSidePadding" name="mainContentSidePadding" aria-describedby="mainContentSidePaddingHelp" value="<?php if(isset($themeConfig['mainContentSidePadding'])) { echo $themeConfig['mainContentSidePadding']; } ?>" required>
                <small id="mainContentSidePaddingHelp" class="form-text text-muted">Percentage of padding relative to the viewport.</small>
              </div>

              <h5 class="mt-5">MENUS</h5>

              <hr class="mb-5">

              <div class="form-group">
                <label class="mb-3">PRIMARY MENU COLOUR</label>
                <div class="form-row mb-3">
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuBgColour" name="primaryMenuBgColour" aria-describedby="primaryMenuBgColourHelp" value="<?php if(isset($themeConfig['primaryMenuBgColour'])) { echo $themeConfig['primaryMenuBgColour']; } ?>" required>
                    <small>Background (Normal)</small>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuTextColour" name="primaryMenuTextColour" aria-describedby="primaryMenuTextColourHelp" value="<?php if(isset($themeConfig['primaryMenuTextColour'])) { echo $themeConfig['primaryMenuTextColour']; } ?>" required>
                    <small>Text (Normal)</small>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuItemHoverAndActiveBgColour" name="primaryMenuItemHoverAndActiveBgColour" aria-describedby="primaryMenuItemHoverAndActiveBgColourHelp" value="<?php if(isset($themeConfig['primaryMenuItemHoverAndActiveBgColour'])) { echo $themeConfig['primaryMenuItemHoverAndActiveBgColour']; } ?>" required>
                    <small>Background (Item hovered or active)</small>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuItemHoverAndActiveTextColour" name="primaryMenuItemHoverAndActiveTextColour" aria-describedby="primaryMenuItemHoverAndActiveTextColourHelp" value="<?php if(isset($themeConfig['primaryMenuItemHoverAndActiveTextColour'])) { echo $themeConfig['primaryMenuItemHoverAndActiveTextColour']; } ?>" required>
                    <small>Text (Item hovered or active)</small>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuSubItemHoverAndActiveBgColour" name="primaryMenuSubItemHoverAndActiveBgColour" aria-describedby="primaryMenuSubItemHoverAndActiveBgColourHelp" value="<?php if(isset($themeConfig['primaryMenuSubItemHoverAndActiveBgColour'])) { echo $themeConfig['primaryMenuSubItemHoverAndActiveBgColour']; } ?>" required>
                    <small>Background (Sub-item hovered or active)</small>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" id="primaryMenuSubItemHoverAndActiveTextColour" name="primaryMenuSubItemHoverAndActiveTextColour" aria-describedby="primaryMenuSubItemHoverAndActiveTextColourHelp" value="<?php if(isset($themeConfig['primaryMenuSubItemHoverAndActiveTextColour'])) { echo $themeConfig['primaryMenuSubItemHoverAndActiveTextColour']; } ?>" required>
                    <small>Text (Sub-item hovered or active)</small>
                  </div>
                </div>
              </div>

              <div class="form-group mt-5">
                <label class="mb-3">FOOTER MENU COLOUR</label>
                <div class="form-row mb-3">
                  <div class="col">
                    <input type="text" class="form-control" id="footerMenuBgColour" name="footerMenuBgColour" aria-describedby="footerMenuBgColourHelp" value="<?php if(isset($themeConfig['footerMenuBgColour'])) { echo $themeConfig['footerMenuBgColour']; } ?>" required>
                    <small>Background</small>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" id="footerMenuTextColour" name="footerMenuTextColour" aria-describedby="footerMenuTextColourHelp" value="<?php if(isset($themeConfig['footerMenuTextColour'])) { echo $themeConfig['footerMenuTextColour']; } ?>" required>
                    <small>Text (Normal or item hovered)</small>
                  </div>
                </div>
              </div>

              <div class="form-group my-5">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input switch" id="stickyFooter" <?php if($themeConfig['stickyFooter'] === 'true') { echo 'checked'; } ?>>
                  <input type="hidden" name="stickyFooter" value="<?php if(isset($themeConfig['stickyFooter'])) { echo $themeConfig['stickyFooter']; } ?>">
                  <label class="custom-control-label" for="stickyFooter">STICKY FOOTER</label>
                </div>
              </div>

              <h5 class="mt-5">PAGES AND POSTS</h5>

              <hr class="mb-5">

              <h6 class="my-5">TITLE DISPLAY OPTION</h6>

              <div class="form-group">
                <label for="homePageTitleDisplayOption">HOME PAGE</label>
                <select class="form-control" id="homePageTitleDisplayOption" name="homePageTitleDisplayOption" required>
                  <option value="1" <?php if($themeConfig['homePageTitleDisplayOption'] === '1') { echo 'selected'; } ?>>Above content</option>
                  <option value="2" <?php if($themeConfig['homePageTitleDisplayOption'] === '2') { echo 'selected'; } ?>>Inside <i>Featured Image</i></option>
                  <option value="3" <?php if($themeConfig['homePageTitleDisplayOption'] === '3') { echo 'selected'; } ?>>Top of page</option>
                  <option value="4" <?php if($themeConfig['homePageTitleDisplayOption'] === '4') { echo 'selected'; } ?>>None</option>
                </select>
                <small id="homePageTitleDisplayOptionHelp" class="form-text text-muted">The title location of the <i>Front page</i>.</small>
              </div>

              <div class="form-group">
                <label for="postsPageTitleDisplayOption">POSTS PAGE</label>
                <select class="form-control" id="postsPageTitleDisplayOption" name="postsPageTitleDisplayOption" required>
                  <option value="1" <?php if($themeConfig['postsPageTitleDisplayOption'] === '1') { echo 'selected'; } ?>>Above content</option>
                  <option value="2" <?php if($themeConfig['postsPageTitleDisplayOption'] === '2') { echo 'selected'; } ?>>Inside <i>Featured Image</i></option>
                  <option value="3" <?php if($themeConfig['postsPageTitleDisplayOption'] === '3') { echo 'selected'; } ?>>Top of page</option>
                  <option value="4" <?php if($themeConfig['postsPageTitleDisplayOption'] === '4') { echo 'selected'; } ?>>None</option>
                </select>
                <small id="postsPageTitleDisplayOptionHelp" class="form-text text-muted">The title location of the <i>Blog page</i> (i.e. the page that displays your latest posts).</small>
              </div>

              <div class="form-group">
                <label for="pageTitleDisplayOption">PAGES</label>
                <select class="form-control" id="pageTitleDisplayOption" name="pageTitleDisplayOption" required>
                  <option value="1" <?php if($themeConfig['pageTitleDisplayOption'] === '1') { echo 'selected'; } ?>>Above content</option>
                  <option value="2" <?php if($themeConfig['pageTitleDisplayOption'] === '2') { echo 'selected'; } ?>>Inside <i>Featured Image</i></option>
                  <option value="3" <?php if($themeConfig['pageTitleDisplayOption'] === '3') { echo 'selected'; } ?>>Top of page</option>
                  <option value="4" <?php if($themeConfig['pageTitleDisplayOption'] === '4') { echo 'selected'; } ?>>None</option>
                </select>
                <small id="pageTitleDisplayOptionHelp" class="form-text text-muted">The title location of a generic page.</small>
              </div>

              <div class="form-group">
                <label for="postTitleDisplayOption">POSTS</label>
                <select class="form-control" id="postTitleDisplayOption" name="postTitleDisplayOption" required>
                  <option value="1" <?php if($themeConfig['postTitleDisplayOption'] === '1') { echo 'selected'; } ?>>Above content</option>
                  <option value="2" <?php if($themeConfig['postTitleDisplayOption'] === '2') { echo 'selected'; } ?>>Inside <i>Featured Image</i></option>
                  <option value="3" <?php if($themeConfig['postTitleDisplayOption'] === '3') { echo 'selected'; } ?>>Top of page</option>
                  <option value="4" <?php if($themeConfig['postTitleDisplayOption'] === '4') { echo 'selected'; } ?>>None</option>
                </select>
                <small id="postTitleDisplayOptionHelp" class="form-text text-muted">The title location of a post.</small>
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

    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <!-- Polyfill (will be removed in the future) -->
      <script src="/js/polyfill/core-js/minified.js"></script>
      <script src="/js/polyfill/regenerator-runtime/runtime.js"></script>

    <?php endif; ?>

    <!-- Bootstrap -->
    <script src="/js/bootstrap/jquery-3.5.1.slim.min.js"></script>
    <script src="/js/bootstrap/popper.min.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="/js/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Pickr -->
    <script src="/js/pickr/pickr.min.js"></script>

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
