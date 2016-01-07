<form method="GET" action="<?php echo $_SERVER[PHP_SELF]; ?>" class="search-category-form">
  <?php foreach ($_GET as $key => $val): ?>
    <?php if ($key != SCPLUGIN_QUERY_ARG): ?><input type="hidden" value="<?php echo $val; ?>" name="<?php echo $key; ?>" /><?php endif; ?>
  <?php endforeach; ?>
  <input class="search-input" placeholder="<?php echo $SCPLUGIN_OPTIONS['placeholder-text']; ?>" type="text" value="<?php echo $_GET[SCPLUGIN_QUERY_ARG]; ?>" name="<?php echo SCPLUGIN_QUERY_ARG; ?>" />
  <input class="submit" onclick="return jQuery(this).parent().find('.search-input').val().length > 1" type="submit" value="<?php echo $SCPLUGIN_OPTIONS['submit-text'] ?>" />
  <?php if (isset($_GET[SCPLUGIN_QUERY_ARG])): ?>
    <input class="clear" type="submit"
      onclick="jQuery(this).parent().find('.search-input').remove();"
      value="<?php echo $SCPLUGIN_OPTIONS['clear-text'] ?>" />
  <?php endif; ?>
</form>
