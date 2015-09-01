<?php
$checks = array();
/* Check PHP Version */
if(phpversion() >= 5.4){
  $checks['PHP']['passed']      = TRUE;
  $checks['PHP']['min']         = '5.4';
  $checks['PHP']['cur']         = phpversion();
} else {
  $checks['PHP']['passed']      = FALSE;
  $checks['PHP']['min']         = '5.4';
  $checks['PHP']['cur']         = phpversion();
}
/* Check PDO MySQL Extension */
if(extension_loaded('pdo_mysql')){
  $checks['PDO']['passed']      = TRUE;
  $checks['PDO']['min']         = 'MySQL';
  $checks['PDO']['cur']         = 'MySQL';
} elseif(extension_loaded('pdo_mysql') == FALSE && extension_loaded('pdo') == TRUE){
  $checks['PDO']['passed']      = FALSE;
  $checks['PDO']['min']         = 'MySQL';
  $checks['PDO']['cur']         = 'No MySQL';
} else {
  $checks['PDO']['passed']      = FALSE;
  $checks['PDO']['min']         = 'MySQL';
  $checks['PDO']['cur']         = 'No PDO';
}
/* Check if PHP ImageMagick is installed */
if(extension_loaded('Imagick')){
  $checks['Imagick']['passed']  = TRUE;
  $checks['Imagick']['min']     = '';
  $checks['Imagick']['cur']     = 'Imagick';
} else {
  $checks['Imagick']['passed']  = False;
  $checks['Imagick']['min']     = 'Imagick';
  $checks['Imagick']['cur']     = '';
}




/* Give the above checks as table output to the screen */
echo '<table width="300" border="1"><tr><th>Minimal</th><th>Yours</th><th>Passed</th></tr>';
foreach($checks as $key=>$data){
  echo '<tr><td>'.$key.' '.$data['min'].'</td><td>'.$data['cur'].'</td>';
  echo '<td>';
  if($data['passed'] == FALSE){
    echo 'NO';
  } elseif($data['passed'] == TRUE){
    echo 'YES';
  }
  echo '</td></tr>';
}
echo '</table>';

?>
