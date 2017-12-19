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

$file = '<ct>body::"Be gone ,$trangevar"; title::"Hello, $tranger";</ct>';

preg_match("/$tag_e.*$etag_e/i",$file,$matches);

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
  if (strpos($comm, "::") !== false) {
    $commsplit = explode("::",$comm);
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

echo $print;