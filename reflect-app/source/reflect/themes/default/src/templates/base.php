<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php if(isset($cmsContent['title'])) { echo $cmsContent['title'] . ' - '; } ?><?php echo $config['siteName']; ?></title>

    <link rel="shortcut icon" href="<?php if(isset($themeConfig['faviconUrl']) && !empty($themeConfig['faviconUrl'])) { echo $themeConfig['faviconUrl']; } ?>">

    <!-- WordPress -->
    <link rel="stylesheet" href="/css/wordpress/block-library/style.min.css?reflect-version=1.0.0-beta.16">
    <link rel="stylesheet" href="/css/wordpress/block-library/theme.min.css?reflect-version=1.0.0-beta.16">

    <!-- Underscores -->
    <link rel="stylesheet" href="/css/underscores/style.css?reflect-version=1.0.0-beta.16">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css?reflect-version=1.0.0-beta.16">

    <!-- Addons -->
    <?php if(count($addons) > 0): ?>
      <?php foreach($addons as $addon): ?>
        <?php if(trim($addon['css']) != ''): ?>
          <?php echo '<!-- ' . $addon['name'] . ' -->' . PHP_EOL; ?>
          <style>
          <?php echo $addon['css'] . PHP_EOL; ?>
          </style>
        <?php endif; ?>
      <?php endforeach; unset($addon); ?>
    <?php endif; ?>

    <!-- Theme CSS (Note: We put our CSS here rather than on separate .css files in order take advantage of PHP preprocessing)-->
    <style>

      /* ------- General ------- */

      body {

        <?php if($themeConfig['stickyFooter'] === 'true'): ?>
        padding-bottom: 7rem; /* Compensate for footer height. Note that this value should always be greater than the height of the footer */
        <?php endif; ?>

        padding-left: <?php echo $themeConfig['mainContentSidePadding']; ?>vw;
        padding-right: <?php echo $themeConfig['mainContentSidePadding']; ?>vw;
      }


      /* ------- Primary Menu ------- */

      /* Background */
      #primaryMenu, #primaryMenu .dropdown-menu {

        background-color: <?php echo $themeConfig['primaryMenuBgColour']; ?>;
      }

      /* Text */
      #primaryMenu a {

        color: <?php echo $themeConfig['primaryMenuTextColour']; ?>;
      }

      /* Background - item hovered or active */
      .nav-item:hover, .navbar-nav .active a {

        background-color: <?php echo $themeConfig['primaryMenuItemHoverAndActiveBgColour']; ?>;
      }

      /* Text - item hovered or active */
      #primaryMenu a:hover, #primaryMenu .active a {

        color: <?php echo $themeConfig['primaryMenuItemHoverAndActiveTextColour']; ?>;
      }

      /* Background - sub-item hovered or active */
      .dropdown-item:hover, .dropdown-menu .active {

        background-color: <?php echo $themeConfig['primaryMenuSubItemHoverAndActiveBgColour']; ?>;
      }

      /* Text - sub-item hovered or active */
      #primaryMenu .dropdown-menu a:hover, #primaryMenu .dropdown-menu .active {

        color: <?php echo $themeConfig['primaryMenuSubItemHoverAndActiveTextColour']; ?>;
      }

      /* On a smaller screen device... */
      @media only screen and (max-width: 992px) {

        /* Background - item hovered or active */
        .nav-item:hover, .navbar-nav .active a {

          background-color: transparent;
        }

        /* Background - sub-item hovered or active */
        .dropdown-item:hover, .dropdown-menu .active {

          background-color: transparent;
        }
      }


      /* ------- Content ------- */

      /* WP Gutenberg Block - Cover */
      .wp-block-cover {

        /* Take up the entire width of the viewport (thus breaking out of Bootstrap's constraints) */
        width: 100vw;
        position: relative;
        left: calc(-1 * (100vw - 100%) / 2);

        min-height: 80vh; /* You can control the height of the cover by changing this value */
      }

      /* WP Gutenberg Block - Video */
      .wp-block-video {

        /* Take up the entire width of the viewport (thus breaking out of Bootstrap's constraints) */
        width: 100vw;
        position: relative;
        left: calc(-1 * (100vw - 100%) / 2);

        min-height: 80vh; /* You can control the height of the cover by changing this value */
      }

      /* WP Gutenberg Block - Latest Posts */
      .wp-block-latest-posts {

        /* Center the post title */
        text-align: center;
      }
      .wp-block-latest-posts li {

        margin: 25px;
      }


      /* WP page or post featured media */
      #featuredMedia {

        /* Take up the entire width of the viewport (thus breaking out of Bootstrap's constraints) */
        width: 100vw;
        position: relative;
        left: calc(-1 * (100vw - 100%) / 2);

        min-height: 80vh; /* You can control the height of the featured media by changing this value */
        padding-top: 40vh; /* This should be half of min-height */
      }

      #featuredMedia h1 {

        font-size: 4rem;
      }

      .pageOrPostTitle h1 {

        padding: 80px 0 60px 0;
        font-size: 4rem;
      }


      /* ------- Footer Menu ------- */

      footer {

        /* Take up the entire width of the viewport (thus breaking out of Bootstrap's constraints) */
        width: 100vw;
        position: relative;
        left: calc(-1 * (100vw - 100%) / 2);

        color: <?php echo $themeConfig['footerMenuTextColour']; ?>;
        background-color: <?php echo $themeConfig['footerMenuBgColour']; ?>;
      }

      #footerMenu a {

        color: <?php echo $themeConfig['footerMenuTextColour']; ?>;
      }

    </style>

  </head>

  <body>

    <div class="container-fluid">

      <header>

        <?php if(isset($primaryMenu)) { require_once __DIR__ . '/partials/primary-menu.php'; } else { echo '<!--{{ primary-menu }}-->'; } ?>

      </header>


      <?php if(isset($mainContentFile) && file_exists(__DIR__ . '/' . $mainContentFile)) { require_once __DIR__ . '/' . $mainContentFile; } else { echo '<!--{{ main }}-->'; } ?>


      <footer class="footer mt-auto py-2 <?php if($themeConfig['stickyFooter'] === 'true') { echo 'fixed-bottom'; } ?>">

          <?php if(isset($footerMenu)) { require_once __DIR__ . '/partials/footer-menu.php'; } else { echo '<!--{{ footer-menu }}-->'; } ?>

          <div id="siteCopyrightStatement">
            <p class="text-center"><small><?php echo $config['siteCopyrightStatement']; ?></small></p>
          </div>

      </footer>

    </div>


    <span id="csrfPreventionToken" data-csrf-prevention-token="<?php if(isset($_SESSION['csrfPreventionToken'])) { echo $_SESSION['csrfPreventionToken']; } ?>"></span>


    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <!-- Polyfill (will be removed in the future) -->
      <script src="/js/polyfill/core-js/minified.js?reflect-version=1.0.0-beta.16"></script>
      <script src="/js/polyfill/regenerator-runtime/runtime.js?reflect-version=1.0.0-beta.16"></script>
      <script src="/js/polyfill/unfetch/index.js?reflect-version=1.0.0-beta.16"></script>

    <?php endif; ?>

    <!-- Bootstrap -->
    <script src="/js/bootstrap/bootstrap.bundle.min.js?reflect-version=1.0.0-beta.16"></script>

    <!-- SweetAlert2 -->
    <script src="/js/sweetalert2/sweetalert2.all.min.js?reflect-version=1.0.0-beta.16"></script>

    <!-- Theme -->
    <?php if($config['olderBrowsersSupport'] === 'true'): ?>
      <script>
        <?php require_once __DIR__ . '/../js/transpiled/theme.js' ?>
      </script>
    <?php else: ?>
      <script>
        <?php require_once __DIR__ . '/../js/theme.js' ?>
      </script>
    <?php endif; ?>

    <!-- Addons -->
    <?php if(count($addons) > 0): ?>
      <?php foreach($addons as $addon): ?>
        <?php if(trim($addon['js']) != ''): ?>
          <?php echo '<!-- ' . $addon['name'] . ' -->' . PHP_EOL; ?>
          <script>
          <?php echo $addon['js'] . PHP_EOL; ?>
          </script>
        <?php endif; ?>
      <?php endforeach; unset($addon); ?>
    <?php endif; ?>

  </body>
</html>
