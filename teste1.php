<?php

 
 if ($_GET['excel'] == 'S') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=arquivoxls.xls");
header("Pragma: no-cache");
header("Expires: 0");
}

if ($_GET['word'] == 'S') {
	
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=arquivodoc.doc");
header("Pragma: no-cache");
header("Expires: 0");
}


?>


<table width="200" border="0">
  <tr>
    <td colspan="3">ASDADASDSA</td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center">SADSDASD</td>
  </tr>
  <tr>
    <td>A</td>
    <td>A</td>
    <td>A</td>
  </tr>
</table>


