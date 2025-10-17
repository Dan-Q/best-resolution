<?php
define('DEBUG', false);
define('X_ACCEL_REDIRECT', true);
define('BASE_IMAGE', 'base-image2.gif');

$width = intval($_GET['w']);
$height = intval($_GET['h']);

if(($width < 300) || ($width > 8000) || ($height <= 300) || ($height > 4500)) {
  header('Content-type: image/gif');
  if(X_ACCEL_REDIRECT) {
    header('X-Accel-Redirect: ./output/any.gif');
  } else {
    fpassthru(fopen('./output/any.gif', 'r'));
  }
  exit;
}

$tmpfile1 = "./tmp/{$width}x{$height}.1.gif";
$tmpfile2 = "./tmp/{$width}x{$height}.2.gif";
$filename = "./output/{$width}x{$height}.gif";

if(DEBUG) {
  echo '<ul>';
  echo "<li>Converting {$width}x{$height}...\n";
  echo "<li>Temp file: {$tmpfile1}\n";
  echo "<li>Output file: {$filename}\n";
}

shell_exec(
  "convert \
    base-image2.gif -font Z003-Medium-Italic -pointsize 13 -fill black -gravity northwest \
    -annotate +25+1 '{$width}' \
    -annotate +20+12 '× {$height}' \
    -layers optimize {$tmpfile1}"
);
if( ! file_exists($tmpfile1) ) {
  header('HTTP/1.1 500 Internal Server Error');
  echo "Error converting image: temp file 1 was not created.\n";
  exit;
}

shell_exec("convert -delay 24 -loop 0 base-image1.gif {$tmpfile1} {$tmpfile2}");
if( ! file_exists($tmpfile2) ) {
  header('HTTP/1.1 500 Internal Server Error');
  echo "Error converting image: temp file 2 was not created.\n";
  exit;
}

shell_exec("convert {$tmpfile2} -coalesce -fuzz 3% +dither -layers Optimize {$filename}");
if( ! file_exists($filename) ) {
  header('HTTP/1.1 500 Internal Server Error');
  echo "Error converting image: final image file was not created.\n";
  exit;
}

unlink($tmpfile1);
unlink($tmpfile2);

header('Content-type: ' . (DEBUG ? 'text/html' : 'image/gif'));
if(DEBUG) {
  echo "<li>Done! <a href=\"{$filename}\">view GIF</a></li>\n";
  exit;
}

if(X_ACCEL_REDIRECT && ! DEBUG) {
  header('X-Accel-Redirect: ' . $filename);
  exit;
}
fpassthru(fopen($filename, 'r'));
