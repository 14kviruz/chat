<?php

/**
 * @author 
 * @license 
 * @package 
 * @version 
 */
define('VERSION', '0.1.1');

$timestart = microtime(TRUE);
$GLOBALS['status'] = array();

$unzipper = new Unzipper;
if (isset($_POST['dounzip'])) {
  // Compruebe si se seleccionó un archivo para descomprimir.
  $archive = isset($_POST['zipfile']) ? strip_tags($_POST['zipfile']) : '';
  $destination = isset($_POST['extpath']) ? strip_tags($_POST['extpath']) : '';
  $unzipper->prepareExtraction($archive, $destination);
}

if (isset($_POST['dozip'])) {
  $zippath = !empty($_POST['zippath']) ? strip_tags($_POST['zippath']) : '.';
  // Ej., zipfile resultante por ejemplo, zipper--2024-12-10--15-36.zip.
  $zipfile = 'zipper-' . date("Y-m-d--H-i") . '.zip';
  Zipper::zipDir($zippath, $zipfile);
}

$timeend = microtime(TRUE);
$time = round($timeend - $timestart, 4);

/**
 * Clase Unzipper
 */
class Unzipper
{
  public $localdir = '.';
  public $zipfiles = array();

  public function __construct()
  {
    // Lea el directorio y elija los archivos .zip, .rar y .gz.
    if ($dh = opendir($this->localdir)) {
      while (($file = readdir($dh)) !== FALSE) {
        if (
          pathinfo($file, PATHINFO_EXTENSION) === 'zip'
          || pathinfo($file, PATHINFO_EXTENSION) === 'gz'
          || pathinfo($file, PATHINFO_EXTENSION) === 'rar'
        ) {
          $this->zipfiles[] = $file;
        }
      }
      closedir($dh);

      if (!empty($this->zipfiles)) {
        $GLOBALS['status'] = array('info' => '.zip or .gz or .rar files found, ready for extraction');
      } else {
        $GLOBALS['status'] = array('info' => 'No .zip or .gz or rar files found. So only zipping functionality available.');
      }
    }
  }

  /**
   * Preparar y comprobar zipfile para la extracción. 
   *
   * @param string $archivo
   *   El nombre del archivo, incluida la extensión de archivo. Por ejemplo, my_archive.zip.
   * @param string $destino
   *   La ruta de destino relativa donde extraer archivos.
   */
  public function prepareExtraction($archive, $destination = '')
  {
    // Determinar rutas.
    if (empty($destination)) {
      $extpath = $this->localdir;
    } else {
      $extpath = $this->localdir . '/' . $destination;
      // Todo: mueva esto a la función de extracción.
      if (!is_dir($extpath)) {
        mkdir($extpath);
      }
    }
    // Solo se permite extraer los archivos locales existentes. 
    if (in_array($archive, $this->zipfiles)) {
      self::extract($archive, $extpath);
    }
  }

  /**
   * Comprueba la extensión de archivo y llama a las funciones extractoras adecuadas.
   *
   * @param string $archivo
   *   El nombre del archivo, incluida la extensión de archivo. 
   * @param string $destination
   *   El nombre del archivo, incluida la extensión de archivo.
   */
  public static function extract($archive, $destination)
  {
    $ext = pathinfo($archive, PATHINFO_EXTENSION);
    switch ($ext) {
      case 'zip':
        self::extractZipArchive($archive, $destination);
        break;
      case 'gz':
        self::extractGzipFile($archive, $destination);
        break;
      case 'rar':
        self::extractRarArchive($archive, $destination);
        break;
    }
  }

  /**
   * Descomprimir/extraer un archivo zip usando ZipArchive.
   *
   * @param $archivo
   * @param $destino
   */
  public static function extractZipArchive($archive, $destination)
  {
    // Compruebe si el servidor web admite la descompresión.
    if (!class_exists('ZipArchive')) {
      $GLOBALS['status'] = array('error' => 'Error: Your PHP version does not support unzip functionality.');
      return;
    }

    $zip = new ZipArchive;

    // Compruebe si el archivo es legible.
    if ($zip->open($archive) === TRUE) {
      // Compruebe si el destino es escribible
      if (is_writeable($destination . '/')) {
        $zip->extractTo($destination);
        $zip->close();
        $GLOBALS['status'] = array('success' => 'Files unzipped successfully');
      } else {
        $GLOBALS['status'] = array('error' => 'Error: Directory not writeable by webserver.');
      }
    } else {
      $GLOBALS['status'] = array('error' => 'Error: Cannot read .zip archive.');
    }
  }

  /**
   * Descomprimir un archivo .gz.
   *
   * @param string $archive
   *   El nombre del archivo, incluida la extensión de archivo. Por ejemplo, my_archive.zip..
   * @param string $destination
   *   La ruta de destino relativa donde extraer archivos.
   */
  public static function extractGzipFile($archive, $destination)
  {
    // Compruebe si zlib está habilitado
    if (!function_exists('gzopen')) {
      $GLOBALS['status'] = array('error' => 'Error: Your PHP has no zlib support enabled.');
      return;
    }

    $filename = pathinfo($archive, PATHINFO_FILENAME);
    $gzipped = gzopen($archive, "rb");
    $file = fopen($destination . '/' . $filename, "w");

    while ($string = gzread($gzipped, 4096)) {
      fwrite($file, $string, strlen($string));
    }
    gzclose($gzipped);
    fclose($file);

    // Compruebe si el archivo fue extraído.
    if (file_exists($destination . '/' . $filename)) {
      $GLOBALS['status'] = array('success' => 'File unzipped successfully.');

      // Si tuviéramos un archivo tar.gz, extraigamos ese archivo tar.
      if (pathinfo($destination . '/' . $filename, PATHINFO_EXTENSION) == 'tar') {
        $phar = new PharData($destination . '/' . $filename);
        if ($phar->extractTo($destination)) {
          $GLOBALS['status'] = array('success' => 'Extracted tar.gz archive successfully.');
          // Eliminar.tar.
          unlink($destination . '/' . $filename);
        }
      }
    } else {
      $GLOBALS['status'] = array('error' => 'Error unzipping file.');
    }
  }

  /**
   * Descomprimir/extraer un archivo Rar usando RarArchive.
   *
   * @param string $archive
   *    El nombre del archivo, incluida la extensión de archivo. Por ejemplo, my_archive.zip.
   * @param string $destination
   *   La ruta de destino relativa donde extraer archivos.
   */
  public static function extractRarArchive($archive, $destination)
  {
    // Compruebe si el servidor web admite la descompresión.
    if (!class_exists('RarArchive')) {
      $GLOBALS['status'] = array('error' => 'Error: Your PHP version does not support .rar archive functionality. <a class="info" href="http://php.net/manual/en/rar.installation.php" target="_blank">How to install RarArchive</a>');
      return;
    }
    // Compruebe si el archivo es legible.
    if ($rar = RarArchive::open($archive)) {
      // Compruebe si el destino es escribible
      if (is_writeable($destination . '/')) {
        $entries = $rar->getEntries();
        foreach ($entries as $entry) {
          $entry->extract($destination);
        }
        $rar->close();
        $GLOBALS['status'] = array('success' => 'Files extracted successfully.');
      } else {
        $GLOBALS['status'] = array('error' => 'Error: Directory not writeable by webserver.');
      }
    } else {
      $GLOBALS['status'] = array('error' => 'Error: Cannot read .rar archive.');
    }
  }
}

/**
 * 
 *

 * @author 
 */
class Zipper
{
  /**
   *
   *
   * @param string 
   *   
   *
   * @param ZipArchive
   *  
   *
   * @param int 
   *   .
   */
  private static function folderToZip($folder, &$zipFile, $exclusiveLength)
  {
    $handle = opendir($folder);

    while (FALSE !== $f = readdir($handle)) {
      // Compruebe la ruta local/parental o el archivo de cremallera en sí y omita.
      if ($f != '.' && $f != '..' && $f != basename(__FILE__)) {
        $filePath = "$folder/$f";
        // Eliminar prefijo de la ruta del archivo antes de agregar a zip..
        $localPath = substr($filePath, $exclusiveLength);

        if (is_file($filePath)) {
          $zipFile->addFile($filePath, $localPath);
        } elseif (is_dir($filePath)) {
          // Add sub-directory.
          $zipFile->addEmptyDir($localPath);
          self::folderToZip($filePath, $zipFile, $exclusiveLength);
        }
      }
    }
    closedir($handle);
  }

  /**
  
   *
   * 
   *
   * @param string $sourcePath
   *   
   *
   * @param string 
   *   
   */
  public static function zipDir($sourcePath, $outZipPath)
  {
    $pathInfo = pathinfo($sourcePath);
    $parentPath = $pathInfo['dirname'];
    $dirName = $pathInfo['basename'];

    $z = new ZipArchive();
    $z->open($outZipPath, ZipArchive::CREATE);
    $z->addEmptyDir($dirName);
    if ($sourcePath == $dirName) {
      self::folderToZip($sourcePath, $z, 0);
    } else {
      self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
    }
    $z->close();

    $GLOBALS['status'] = array('success' => 'Successfully created archive ' . $outZipPath);
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>File Unzipper + Zipper</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style type="text/css">
    <!--
    body {
      font-family: Arial, sans-serif;
      line-height: 150%;
    }

    label {
      display: block;
      margin-top: 20px;
    }

    fieldset {
      border: 0;
      background-color: #EEE;
      margin: 10px 0 10px 0;
    }

    .select {
      padding: 5px;
      font-size: 110%;
    }

    .status {
      margin: 0;
      margin-bottom: 20px;
      padding: 10px;
      font-size: 80%;
      background: #EEE;
      border: 1px dotted #DDD;
    }

    .status--ERROR {
      background-color: red;
      color: white;
      font-size: 120%;
    }

    .status--SUCCESS {
      background-color: green;
      font-weight: bold;
      color: white;
      font-size: 120%
    }

    .small {
      font-size: 0.7rem;
      font-weight: normal;
    }

    .version {
      font-size: 80%;
    }

    .form-field {
      border: 1px solid #AAA;
      padding: 8px;
      width: 280px;
    }

    .info {
      margin-top: 0;
      font-size: 80%;
      color: #777;
    }

    .submit {
      background-color: #378de5;
      border: 0;
      color: #ffffff;
      font-size: 15px;
      padding: 10px 24px;
      margin: 20px 0 20px 0;
      text-decoration: none;
    }

    .submit:hover {
      background-color: #2c6db2;
      cursor: pointer;
    }
    -->
  </style>
</head>

<body>
  <p class="status status--<?php echo strtoupper(key($GLOBALS['status'])); ?>">
    Status: <?php echo reset($GLOBALS['status']); ?><br />
    <span class="small">Processing Time: <?php echo $time; ?> seconds</span>
  </p>
  <form action="" method="POST">
    <fieldset>
      <h1>Archive Unzipper</h1>
      <label for="zipfile">Select .zip or .rar archive or .gz file you want to extract:</label>
      <select name="zipfile" size="1" class="select">
        <?php foreach ($unzipper->zipfiles as $zip) {
          echo "<option>$zip</option>";
        }
        ?>
      </select>
      <label for="extpath">Extraction path (optional):</label>
      <input type="text" name="extpath" class="form-field" />
      <p class="info">Enter extraction path without leading or trailing slashes (e.g. "mypath"). If left empty current directory will be used.</p>
      <input type="submit" name="dounzip" class="submit" value="Unzip Archive" />
    </fieldset>

    <fieldset>
      <h1>Archive Zipper</h1>
      <label for="zippath">Path that should be zipped (optional):</label>
      <input type="text" name="zippath" class="form-field" />
      <p class="info">Enter path to be zipped without leading or trailing slashes (e.g. "zippath"). If left empty current directory will be used.</p>
      <input type="submit" name="dozip" class="submit" value="Zip Archive" />
    </fieldset>
  </form>
  <p class="version">Unzipper version: <?php echo VERSION; ?></p>
</body>

</html>




// Simulación de actualización en la base de datos (AJAX)
function updateUserToPremium(userId) {
    fetch('/update-premium-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ userId: userId, isPremium: true })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message); // Muestra el mensaje de éxito
    })
    .catch(error => {
        console.error('Error al actualizar el estado premium:', error);
    });
}
