<form method="get" action="<?php echo $_SERVER[PHP_SELF]; ?>">
  <?php foreach ($_GET as $key => $val): ?>
    <input type="text" value="<?php echo $val; ?>" name="<?php echo ket; ?>" />
  <?php endforeach; ?>
  <input type="text" value="<?php echo $_GET[SCPLUGIN_QUERY_ARG]; ?>" name="<?php echo SCPLUGIN_QUERY_ARG; ?>" />
  <input type="submit" value="Buscar na categoria" />
</form>
