<h1 style="text-align:left"> Calendário </h1>
<link rel='stylesheet' type='text/css' href='calendario/fullcalendar.css' />
<script type='text/javascript' src='calendario/jquery-ui-custom.js'></script>
<script type='text/javascript' src='calendario/jquery-1.4.2'></script>
<script type='text/javascript' src='calendario/fullcalendar.min.js'></script>
<script type='text/javascript'>
var val = 0;
var aux = '';
var tipo = '';
	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			events: "load_eventos.php",
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
				event.title = "CLICKED!";
				 //$('#calendar').fullCalendar('updateEvent', event);
				 $('#calendar').fullCalendar('rerenderEvents');				
			},
			
			dayClick: function(date, allDay, jsEvent, view) {
				var dia,mes,ano,hora,minuto;
				
				dia = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
				mes = ((date.getMonth()+1) < 10) ? '0' + (date.getMonth()+1) : (date.getMonth()+1);
				ano = date.getFullYear();
				hora = (date.getHours() < 10) ? '0' + date.getHours() : date.getHours();
				minuto = (date.getMinutes() < 10) ? '0' + date.getMinutes() : date.getMinutes();
				
				if (allDay) {
					//alert('Clicked on the entire day: ' + date);
					
					window.location.href='index.php?i=29';    
				
				
				}else{
					//alert('Clicked on the slot: ' + date);
					// window.location.href='?i=frm_agenda&data='+dia+'/'+mes+'/'+ano+'&hora='+hora+':'+minuto;
				window.location.href='index.php?i=7';    
				}

				/*alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
				alert('Current view: ' + view.name);
				// change the day's background color just for fun
				$(this).css('background-color', 'red');*/			
			},
				//eventMouseover: function( event, jsEvent, view ) { 
				//	alert(event.title);
				//},
				
				
			allDaySlot: false,

			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
		});
		
		function update(){
			 $('#calendar').fullCalendar('refetchEvents');	
			 //alert('teste');
			 window.setTimeout(update, 10000);
		}
		
		window.setTimeout(update, 1000);								
		
		$('#btnpesq').click(function(){
							  pesquisa($('#pesq').val(),$('#cbopesq').val());
							  });
		
		$('#btnagendar').click(function(){						
				window.location.href='index.php?i=18';
		});
		
	});
	
	function pesquisa(valor,tipo){
			/*$('#calendar').fullCalendar('clientEvents',function(event,value){
				return event.id == value;
			});*/
			//$('#calendar').fullCalendar('removeEvents');
			//alert('conteudo '+valor);
			//if(valor == '')
			//	valor = 0;
			//alert('p1='+valor+' p2='+tipo);
			if (val == 1){
				$('#calendar').fullCalendar('removeEventSource','load_eventos.php?pesq='+aux+'&cbopesq='+auxcbo);						
			}
			else{
				$('#calendar').fullCalendar('removeEventSource','load_eventos.php');
				val = 1;					
			}
			$('#calendar').fullCalendar('addEventSource', 'load_eventos.php?pesq='+valor+'&cbopesq='+tipo); 	
			
			aux = valor;
			auxcbo = tipo;			
			//update();
			//alert(valor);
	}
		
				
</script>
<style type='text/css'>

	body {
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>

<!-- <div id='loading' style='display:none'>loading...</div>
			
    <div style="text-align:left"><h1>Controle &raquo; Calend&aacute;rio</h1></div>
    <div id="frm_pesq" style="clear:both; height:50px;" >  
    
        <input type="text" name="pesq" id="pesq" style=" float:left; padding:8px; border:#CCCCCC solid 1px; width:200px; font-style:italic; background:url(calendario/img/icon_lupa.jpg) no-repeat; padding-left:25px; "  /> 
        <select name="cbopesq" id="cbopesq" style="float:left; width:150px; height:35px; margin-left:5px; border:#999999 solid 1px; " > 
            <option value="1">Tipo Profissional Saúde </option>
            <option value="2">Profissional de Saúde (nome) </option>
            <option value="3">Paciente</option>
        </select>
        <input type="submit" id="btnpesq" value="Buscar" style="margin-left:5px; padding:8px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer" /> 
        <input type="submit" id="btnagendar" value="Agendar" style="margin-left:5px; padding:8px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer" /> 
    </div>  -->

<div id='calendar'>    </div>