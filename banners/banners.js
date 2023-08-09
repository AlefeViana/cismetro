$(document).ready(function(){
	$("#carrega").css("display","none");
	qtd_banners = $( ".banners li" ).size();
	bposicao = 0;
	//console.log( "qtd"+qtd_banners );	
	buttonTipo(bposicao);
	$('.btn_nao').click(function(event) {
		ccdBanner = $( ".banners li" ).eq( bposicao ).data('banner');
		alcanceBanner(ccdBanner, 0);
		buttonTipo(bposicao+1);
	});
	$('.btn_desejo').click(function(event) {
		ccdBanner = $( ".banners li" ).eq( bposicao ).data('banner');
		alcanceBanner(ccdBanner, 1);
		buttonTipo(bposicao+1);
	});

});

function buttonTipo(bposicaow) {
		tipo = $( ".banners li" ).eq( bposicaow ).data('tipo');
	if (tipo=="I") {
		$('.btn_desejo').hide();
		$('.btn_nao').css('margin-top','13px');
		$('.btn_nao').val('Não exibir novamente!');
	}else if(tipo=="P"){
		$('.btn_desejo').show();
		$('.btn_nao').css('margin-top','0');
		$('.btn_nao').val('Não tenho interesse!');
	}
}

function ocultarBanner(posicao) {
	$( ".banners li" ).eq( posicao ).css( "display", "none" );
	if (bposicao+1 == qtd_banners) {
		$('#c').css({
			'opacity': '0',
			'pointer-events': 'none'
		});
	}
}

function alcanceBanner(cdBanner, contato) {
	$("#carrega").css("display","inline-block");
	$('.btn_nao').prop('disabled','disabled');
	$('.btn_desejo').prop('disabled','disabled');
	var URL = "banners/post_alcance.php";
	$.ajax({
		type: 'POST',
		url: URL,
		dataType: 'json',
		data: { cdBanner:cdBanner, contato:contato}
	}).fail(function() {
		swal("Erro", "Não foi possível executar essa ação, tente novamente!", "warning");
	}).success(function (data) {
		if (data) {
			console.log( "ok" );
			if (contato) {
				swal({   
					title: "OK",
					text: "Entraremos em contato em breve!",
					type: "success",  
					closeOnConfirm: true,   
					showLoaderOnConfirm: false, 
	    	     }, 
	    	     function(){   
					ocultarBanner(bposicao);
					bposicao += 1;
	    	     });
			}else{
				ocultarBanner(bposicao);
				bposicao += 1;
			}
		}else{
			swal("Erro", "Não foi possível executar essa ação, tente novamente!", "warning");
		}
		$("#carrega").css("display","none");
		$('.btn_nao').removeProp('disabled');
		$('.btn_desejo').removeProp('disabled');
	});

}