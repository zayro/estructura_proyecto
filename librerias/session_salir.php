
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>cerrar</title>
</head>
<body>
  <script>
  localStorage.removeItem('firebase:session::estructuraproyecto');
  localStorage.removeItem('session');
</script>
<?php
session_start();
session_destroy();
header('Location: ../public_html/index.html');
?>
</body>
</html>

