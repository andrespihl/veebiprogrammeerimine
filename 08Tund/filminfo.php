<?php
  require("functions.php");
  require("common.php");
  require("header.php");
?>
<h2>Eesti filmid</h2>
<p>Praegu meie andmebaasis on järgmised filmid:</p><hr>


<?php
  echo $filmInfoHTML;
  require("footer.php");
?>
</body>
</html>