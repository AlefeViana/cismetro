
<script language="javascript">
var my_div = null;
var newDiv = null;

ar_campo1 = new Array(0);

function addElement()
        {
        //adicionar mais um elemento
        y = ar_campo1.push("");

        //indicar o nome do campo como array[novo elemento] 
        newDiv = document.createElement("div");
        newDiv.innerHTML = "<input type='text' name='campo"+y+"'>";

        // adicionar o campo ao formulário
        my_div = document.getElementById("org_div1");

        document.form1.insertBefore(newDiv, my_div);
        }

function readElement()
{
        for(x=0; x < ar_campo1.length; x++)
        {
                w = x+1; 
                campo = "document.form1.campo" + w + ".value";
                eval("document.write(" + campo +")");
                document.write("<br>");
        }
} 
</script>

<form name="form1">
<div id='org_div1'></div>
<input type="button" onClick="addElement()" value="Adicionar Elemento"><br><br>
</form>


<script>
// limpa combo "c1" e adiciona 3 elementos
document.myform.c2.options[0]=new Option("Sports", "sportsvalue", true, false)
 function adiciona(){
document.myform.c1.options.length=0
document.myform.c2.options[0]=new Option("Sports", "sportsvalue", true, false)
document.myform.c2.options[1]=new Option("Music", "musicvalue", false, false)
document.myform.c2.options[2]=new Option("Movies", "moviesvalue", false, false)
}
</script>

<body>

<form name="myform">
<select  name="c1" onchange="adiciona()">
<option>elemento 1</option>
        <option>elemento 2</option>
<option>elemento 3</option>
</select>
</form>