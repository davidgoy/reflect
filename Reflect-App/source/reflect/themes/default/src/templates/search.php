<main role="main" class="container-fluid">

  <div id="mainContent">
    <div class="row">
      <div class="col mx-auto">
        <div id="searchResultsContent">

          <h2 class="text-center m-5">SEARCH RESULTS</h2>

          <p class="text-center">Found <b><?php echo count($cmsContent['results']); ?></b> pages/posts containing the search terms "<b><?php echo $cmsContent['searchTerms'] ?></b>".</p>

          <?php if(count($cmsContent['results']) > 0): ?>

          <br><br>

          <div class="list-group list-group-flush">

          <?php foreach($cmsContent['results'] as $result): ?>

            <a class="text-center list-group-item list-group-item-action" href="//<?php echo str_replace($config['cmsProtocol'] . '://' . $config['cmsDomain'], $_SERVER['SERVER_NAME'], $result['url']); ?>">
              <b><?php echo $result['title']; ?></b>
              <br>
              <?php echo str_replace($config['cmsProtocol'] . '://' . $config['cmsDomain'], $_SERVER['SERVER_NAME'], $result['url']); ?>
            </a>

          <?php endforeach; unset($result); ?>

          </div>

          <br><br>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

</main>
