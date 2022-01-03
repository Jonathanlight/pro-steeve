<!DOCTYPE html>
<html lang="en">
<head>
  <title>Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <form action="index.php" method="post">
    <div class="form-group">
      <label for="comment">Comment:</label>
      <textarea name="parole" class="form-control" rows="5" id="comment"></textarea>
    </div>
    <button type="submit" class="btn btn-default">Parle</button>
  </form>
</div>

<?php
  if (isset($_POST['parole']) && !empty($_POST['parole'])) {
    $varm = $_POST['parole'];
    $msg = "say '".$varm."'";
    echo exec('ls -la');
    //echo shell_exec($msg);
  }
 ?>
</body>
</html>
