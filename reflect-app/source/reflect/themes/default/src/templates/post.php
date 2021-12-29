<main role="main" class="container-fluid">

  <?php if($themeConfig['postTitleDisplayOption'] === '3'): ?>

  <div class="pageOrPostTitle">

    <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

  </div>

  <?php endif; ?>

  <div id="mainContent" class="wp-embed-responsive">

    <?php if(isset($cmsContent['featuredMedia'])): ?>

      <div id="featuredMedia" style="background: url('<?php echo $cmsContent['featuredMedia']; ?>') no-repeat center/cover;">

        <?php if($themeConfig['postTitleDisplayOption'] === '2'): ?>
        <h1 class="text-center display-5 text-light"><?php echo $cmsContent['title']; ?></h1>
        <?php endif; ?>

      </div>

    <?php endif; ?>

    <div class="text-center my-5">

      <?php if($themeConfig['postTitleDisplayOption'] === '1'): ?>

      <div class="pageOrPostTitle">

        <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

      </div>

      <?php endif; ?>

      <p><small>
        <b>Published</b>: <?php echo date('g:i A\, l jS F Y', strtotime($cmsContent['date'])); ?>
        <?php if($cmsContent['date'] !== $cmsContent['modified']): ?>
         | <b>Updated</b>: <?php echo date('g:i A\, l jS F Y', strtotime($cmsContent['modified'])); ?>
        <?php endif; ?>
      </small></p>
    </div>

    <?php echo $cmsContent['body']; ?>

  </div>

</main>
