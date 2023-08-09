<script type="text/javascript" language="javascript"> 
  $(document).ready(function() {
  $("#frm1").validate({
	  
	});		
});
</script>    

<h1>Totais de procedimentos por Status</h1>
<form action="graficos/graf.procedimentos.php" method="post" target="_blank" id="frm1" >
<?php
include "filtro_forn_mun_proc.php";
?>
    <label style="clear:both"> Data de In&iacute;cio  *
          <input type="text" name="dtinicio" id="dtinicio" class="required" readonly/></label>    	
        <label> Data de T&eacute;rmino *
          <input type="text" name="dttermino" id="dttermino" class="required" readonly/></label> 
    <div id="btns"> 
    	<input type="submit" value="Gerar">
    </div>    		
</form>