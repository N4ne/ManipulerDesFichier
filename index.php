<?php include('inc/head.php'); ?>

<?php


if(isset( $_GET['todelete'])){
    $todelete = $_GET['todelete'];

    if (is_dir($todelete)) {
        rmdir($todelete);
        if (count(scandir($todelete)) <= 2 ) {
            rmdir($todelete);
        } else {
            echo 'Il faut vider le dossier pour pouvoir le supprimer !';
        }
    } else {
        unlink($todelete);
    }

}

?>
<?php
$mon_repertoire = 'files'; // nom du repertoire à lister
// début de la fonction
function lister_repertoire($mon_repertoire)
{
  $pointeur = dir($mon_repertoire);
  chdir($pointeur->path);
  echo '<BR><BR>Répertoire courant : '.$pointeur->path.' ';
    // boucle
    while($fichier = $pointeur->read()) {
      $files = realpath($fichier);
      $url = explode("/", $files);
      $url = array_diff_key($url, [0, 1, 2, 3, 4]);
      $url = implode( "/", $url);
      if(is_dir($fichier) AND $fichier != '.' AND $fichier != '..'){
          lister_repertoire($fichier);
          echo '<a href="index.php?todelete='.$url.'">  <button type="button" class="btn btn-danger">DeleteDirectory</button></a>';
          echo "<br>";
      } else if ($fichier != '.' AND $fichier != '..') {
          echo "<BR><a href= " .'?file='.$url." > $fichier</a>";
          echo "<br>";
          echo '<a href="index.php?todelete='.$url.'">  <button type="button" class="btn btn-danger">Delete</button></a>';
      }

      }
      $pointeur->close();
      chdir('..');
}
    // fin de la fonction
    // il s'agit bien d'un répertoire
    if(is_dir($mon_repertoire))
    {
      lister_repertoire($mon_repertoire);
    }
    else echo '<BR><B>Le repertoire n\'existe pas !!</B>' ;
?>

<?php

if (isset($_POST['contenu']) && pathinfo($_GET['file'], PATHINFO_EXTENSION) != 'jpg') {
    $fichier = $_GET['file'];
    $file = fopen($fichier, "w");
    fwrite($file, $_POST['contenu']);
    fclose($file);
}

if (isset($_GET["file"]) && pathinfo($_GET['file'], PATHINFO_EXTENSION) != 'jpg') {
    $fichier = $_GET['file'];
    $contenu = file_get_contents($fichier);
?>

<form method="POST" action="">
  <textarea name="contenu" style="width: 100%;height: 200px;">
    <?php echo $contenu; ?>
  </textarea>
  <input type="hidden" name="file" value="<?= $_GET['file'] ?>">
  <input type="submit" value="modifier">
</form>

    <?php
      }
    ?>
<?php include('inc/foot.php'); ?>