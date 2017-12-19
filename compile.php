<?php
$delimiter = "::";
$eol = ";";
$var = '$';
$tag = "<ct>";
$etag = "</ct>";
$stopErrorHandlerActivation = false; //Stop error handler from throwing exceptions (Still shows error messages)

$print = ""; //Leave it alone

$vari = ['$trangevar' => 'MEMER_Y','$tranger' => "New Boyo 1",];

function errorHandle($type,$critical,$line,$near) {
  $type = strtolower($type);$errors = [
"badsyntax" => "Error: Invalid Syntax on Line: ",
];if (!isset($errors[$type])) {echo "ErrorHandler Broke";throw new exception("Error Handling Machine Broke!");}if (!isset($near) or $near=="") {$exep = $errors[$type] . (string)$line;} else {$exep = $errors[$type] . (string)$line . ", near: " . "\"" . (string)$near . "\"";}global $stopErrorHandlerActivation;echo $exep;if ($critical and !$stopErrorHandlerActivation) {throw new exception("Error: " . $type);}}

$tag_e = str_replace("/","\/",$tag);
$etag_e = str_replace("/","\/",$etag);

if (isset($argv[1])) {
	$file = file_get_contents($argv[1]);
} else {
	echo "No File Specified, Please Use 'PHP compile.php <script to compile> <output file>'";
	throw new exception("No File!!!");
}

preg_match("/$tag_e.*$etag_e/i",$file,$matches);

if (sizeof($matches) > 0) {

$code = $matches[0];

$file = str_replace($code,"", $file);

$code = str_ireplace([$tag,$etag],"",$code);

$commands = explode(";",$code);

$commandLookup = [
  "body" => "<body>*</body>",
  "title" => "<title>*</title>"
  ];

$i = 0;
foreach ($commands as $comm) {
  $i++;
  if (strpos($comm, $delimiter) !== false) {
    $commsplit = explode($delimiter,$comm);
    $commsplit[0] = str_replace(" ","",$commsplit[0]);
    $commsplit[1] = str_replace("'","",$commsplit[1]);
    $commsplit[1] = str_replace("\"","",$commsplit[1]);
    if (isset($commandLookup[$commsplit[0]])) {
      $newCommand = str_replace("*",$commsplit[1],$commandLookup[$commsplit[0]]);
      unset($matches);
      $returnValue = preg_match_all('/\\$[a-zA-Z0-9]*/', $newCommand, $matches);
      $i_=0;
      global $vari;
      foreach($matches as $ey) {
        foreach($ey as $pm) {
        $newCommand = str_replace($pm,$vari[$pm],$newCommand);
        
        $i++;
        }
      }
      $print .= $newCommand;
    } else {
      errorHandle("badsyntax",true,$i,"$commsplit[0]");
    }
}
}

if (isset($argv[2])) {
	$putfile = $argv[2];
} else {
	$putfile = "compiled-MYOL.php";
}

file_put_contents($putfile,$print);

$ver = file_get_contents("version");

echo "\nCongrats, Your Code Compiled Successfully!\n";
echo "Compiler: MYOL-PHP: $ver\n";
} else {
	echo "\nNo code to compile!";
}