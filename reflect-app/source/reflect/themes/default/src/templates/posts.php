<main role="main" class="container-fluid">

  <?php if($themeConfig['postsPageTitleDisplayOption'] === '3'): ?>

  <div class="pageOrPostTitle">

    <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

  </div>

  <?php endif; ?>

  <div id="mainContent" class="wp-embed-responsive">

    <?php if(isset($cmsContent['featuredMedia'])): ?>

      <div id="featuredMedia" style="background: url('<?php echo $cmsContent['featuredMedia']; ?>') no-repeat center/cover;">

        <?php if($themeConfig['postsPageTitleDisplayOption'] === '2'): ?>
        <h1 class="text-center display-5 text-light"><?php echo $cmsContent['title']; ?></h1>
        <?php endif; ?>

      </div>

    <?php endif; ?>

    <?php if(isset($cmsPosts) && count($cmsPosts) > 0): ?>

      <?php if($themeConfig['postsPageTitleDisplayOption'] === '1'): ?>

      <div class="pageOrPostTitle">

        <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

      </div>

      <?php endif; ?>

    <div class="row">

      <?php foreach($cmsPosts as $cmsPost): ?>

      <div class="col-sm-4">
        <div class="card text-center my-2">
          <?php if(isset($cmsPost['_embedded']['wp:featuredmedia'])): ?>
          <a href="//<?php echo "{$_SERVER['SERVER_NAME']}/{$cmsPost['slug']}/"; ?>">
            <img src="<?php echo $cmsPost['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['medium']['source_url']; ?>" class="card-img-top">
          </a>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><a href="//<?php echo "{$_SERVER['SERVER_NAME']}/{$cmsPost['slug']}/"; ?>"><?php echo $cmsPost['title']['rendered']; ?></a></h5>
            <div class="text-center clearfix mt-2 mb-3">
              <p><small>
                <?php if($cmsPost['date'] !== $cmsPost['modified']): ?>
                <b>Updated</b>: <?php echo date('g:i A\, l jS F Y', strtotime($cmsPost['modified'])); ?>
                <?php else: ?>
                <b>Published</b>: <?php echo date('g:i A\, l jS F Y', strtotime($cmsPost['date'])); ?>
                <?php endif; ?>
              </small></p>
              <hr>
            </div>
            <span class="card-text"><?php echo $cmsPost['excerpt']['rendered']; ?></span>
            <a href="//<?php echo "{$_SERVER['SERVER_NAME']}/{$cmsPost['slug']}/"; ?>" class="btn btn-sm btn-outline-secondary" role="button">Read More</a>
          </div>
        </div>
      </div>

      <?php endforeach; unset($cmsPost); ?>

    </div>

    <?php if($numOfPaginationLinks > 1): ?>

    <div id="postsPagination" class="row mt-5">

      <div class="col mx-auto">

        <p class="text-center">Showing items <?php echo $pageFirstItem; ?> to <?php echo $pageLastItem; ?> of <?php echo $paginationTotalPosts; ?>.</p>

        <nav class="d-flex justify-content-center" aria-label="Posts pagination">
          <ul class="pagination">

            <?php if($pageFirstItem != 1): ?>

            <li class="page-item">
              <a class="page-link" href="<?php echo $postsPageUrl; ?>?page=<?php echo $paginationPageNumber - 1; ?>" aria-label="Previous">&laquo;</a>
            </li>

            <?php endif; ?>

            <?php for($i = 1; $i <= $numOfPaginationLinks; $i++): ?>

            <li class="page-item <?php if($i == $paginationPageNumber) { echo 'active'; } ?>">
              <a class="page-link" href="<?php echo $postsPageUrl; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>

            <?php endfor; ?>

            <?php if($pageLastItem != $paginationTotalPosts): ?>

            <li class="page-item">
              <a class="page-link" href="<?php echo $postsPageUrl; ?>?page=<?php echo $paginationPageNumber + 1; ?>" aria-label="Next">&raquo;</a>
            </li>

          <?php endif; ?>

          </ul>
        </nav>

      </div>

    </div>

    <?php endif; ?>

  <?php else: ?>

  <div class="row">

    <div class="col">

      <h5 class="text-center m-5">No posts to display</h5>

    </div>
  </div>

  <?php endif; ?>

  </div>

</main>
