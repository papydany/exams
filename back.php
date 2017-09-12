<?php
global $ref, $newref, $HTTP_REFERER;
$ref = $HTTP_REFERER;
if (empty($ref)) {
$newref = "#";
}
else {
$newref = "$ref";
}
print $newref;

?>