<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css?version=1.0.0-beta.10">

    <style>
      <?php if(isset($pageCssFile)) {require_once __DIR__ . '/../css/' . $pageCssFile;} ?>
    </style>

    <title>Reflect <?php if(isset($pageTitle)) { echo ' - ' . $pageTitle; } ?></title>

  </head>

  <body>

    <?php require_once __DIR__ . '/partials/header.php'; ?>
    <?php require_once __DIR__ . '/' . $mainContentFile; ?>
    <?php require_once __DIR__ . '/partials/footer.php'; ?>


    <span id="csrfPreventionToken" data-csrf-prevention-token="<?php if(isset($_SESSION['csrfPreventionToken'])) { echo $_SESSION['csrfPreventionToken']; } ?>"></span>


    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <!-- Polyfill (will be removed in the future) -->
      <script src="/js/polyfill/core-js/minified.js"></script>
      <script src="/js/polyfill/regenerator-runtime/runtime.js"></script>
      <script src="/js/polyfill/unfetch/index.js"></script>

    <?php endif; ?>

    <!-- Bootstrap -->
    <script src="/js/bootstrap/jquery-3.5.1.slim.min.js?version=1.0.0-beta.10"></script>
    <script src="/js/bootstrap/bootstrap.bundle.min.js?version=1.0.0-beta.10"></script>

    <!-- SweetAlert2 -->
    <script src="/js/sweetalert2/sweetalert2.all.min.js?version=1.0.0-beta.10"></script>

    <?php if($config['olderBrowsersSupport'] === 'true'): ?>

      <script>
      <?php require_once __DIR__ . '/../js/transpiled/' . 'main.js'; ?>
      <?php if(isset($pageJsFile)) { require_once __DIR__ . '/../js/transpiled/' . $pageJsFile; } ?>
      </script>

    <?php else: ?>

      <script>
      <?php require_once __DIR__ . '/../js/' . 'main.js'; ?>
      <?php if(isset($pageJsFile)) { require_once __DIR__ . '/../js/' . $pageJsFile; } ?>
      </script>

    <?php endif; ?>

  </body>
</html>
