<?php 
     // include 'banners/banners.php';   
    
      
      use voku\helper\Paginator;
      session_start();
      
?>
<style>
    .qts_alert{
        border: 0px;
        border-style: double;
        border-radius: 25px;
        /* background-color: orange; */
        color: #ea0e0e;
        text-size: 6px;
        font-size: 12px;
        font-weight: bold;
    }
    #carouselPagInicial {
        width: 100%;
    }

    .col-sm{
        margin-left: 2%;
        margin-right: -9%;
    }
    .list-group-item{
        cursor: pointer;
    }
    .modal-xl{
        max-width: 50% !important;
    }
    .bg-warning {
    background-color: #ff8300!important;
    }
        
/*Right*/
	.modal.right.fade .modal-dialog {
        right: -38%;
        width: 24%;
		-webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
		   -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
		     -o-transition: opacity 0.3s linear, right 0.3s ease-out;
		        transition: opacity 0.3s linear, right 0.3s ease-out;
	}
	
	.modal.right.fade.in .modal-dialog {
		right: 0;
	}

</style>
<script>
$(document).ready(function () {

    $('.open-cred').load("controle/alertas/alerta_credenciamento.php", function (resposta, status, request) {
        this;
        resposta = JSON.parse(resposta);
        console.log(resposta);
        (resposta.num_alerts > 0 )? $('.open-cred').html('Alertas de Credenciamento <div style="float: right"><label class="qts_alert">'+resposta.num_alerts+'</label><i class="fas fa-bell" style="color:orange; float:right"></i></div>') : $('.open-cred').html('Alertas de Credenciamento <i class="fas fa-bell" style="color:gray; float:right"></i>');
    });

     $('.open-agd').load("controle/alertas/alerta_marcacao.php", function (resposta, status, request) {
        this;
        resposta = JSON.parse(resposta);
        console.log(resposta);
        (resposta.num_alerts > 0 )? $('.open-agd').html('Alertas de Marcação <div style="float: right"><label class="qts_alert">'+resposta.num_alerts+'</label><i class="fas fa-bell" style="color:orange; float:right"></i></div>') : $('.open-agd').html('Alertas de Marcação <i class="fas fa-bell" style="color:gray; float:right"></i>');
    });

    $('.open-crt').load("controle/alertas/alerta_contrato.php", function (resposta, status, request) {
        this;
        resposta = JSON.parse(resposta);
        console.log(resposta);
        
        (resposta.num_alerts > 0 )? $('.open-crt').html('Alertas de Contrato <div style="float: right"><label class="qts_alert">'+resposta.num_alerts+'</label><i class="fas fa-bell" style="color:orange; float:right"></i></div>') : $('.open-crt').html('Alertas de Contrato <i class="fas fa-bell" style="color:gray; float:right;"></i>');
    }); 
    
    $('.carousel').carousel({ interval: 2500  })
    $('.noticia').click(function (e) { 
        let id = $(this).data('id');        
        axios.post('noticias/fetch.php' ,{ id: id })
        .then(res => {
            data = res.data[0];
            $('#modal').modal({show:true})
            $('#modal-label').text(data.title);
            let html = "";
            if(data.text){
                html += '<div class="text-left">'+data.text+'</div>';
            }
            if(data.storage.length){
                html += '<div class="text-center"><img src="/'+data.storage[0].path+'/'+data.storage[0].file_name+'" class="img-fluid mb-2" alt="Responsive image"></div>'
            }
            
            $('#modal-content').html(html).addClass('text-center')
        })
    });

   var forn = doc = cotaMa = cotaMe = agendas_list = '';

   $('.open-contrato').click(function (e) { 
        //e.preventDefault();
        $("#modal").modal({show:true});
        $("#modal-label").text('Alertas de Contrato');
        $.ajax({
            url: "controle/alertas/alerta_contrato.php",
            type: "POST",
            cache: false,
            dataType: "json",
            success: function (resposta) {
                console.log(resposta);
                $("#modal-content").html(resposta.msg); 
                forn = resposta.forn;
                doc  = resposta.doc;  
            }
        });
        
    });
        
    $('.open-cred').click(function (e) { 
        //e.preventDefault();
        $("#modal").modal({show:true});
        $("#modal-label").text('Alertas de Credenciamento');
        $.ajax({
            url: "controle/alertas/alerta_credenciamento.php",
            type: "POST",
            cache: false,
            dataType: "json",
            success: function (resposta) {
                console.log(resposta);
                $("#modal-content").html(resposta.msg); 
                forn_cred = resposta.forn;
                doc  = resposta.doc;
                
            }
        });
        
    });

    $(document).on('click','.irFP',function () { 
        window.open("./index.php?i=6");  
    });
    $(document).on('click','.irFP-imp',function () { 
        window.open("./index.php?i=6&imp=N");  
    });
    $(document).on('click','.irCTR',function () { 
        window.open("./index.php?i=154");  
    });
    $(document).on('click','.irCred',function () { 
        console.log($(this).data('forn'));
        if(typeof($(this).data('forn')) != 'undefined')
            window.open("./index.php?i=160&info="+$(this).data('forn'));
        else
            window.open("./index.php?i=160&info=fc");  
    });
    $(document).on('click','.irAGD',function () { 
        window.open("./index.php?i=151");  
    });
    $(document).on('click','.irAGDF',function () { 
        window.open("./index.php?i=168");  
    });
    
   
    $('.open-agd').click(function (e) { 
        //e.preventDefault();
        $("#modal").modal({show:true});
        $("#modal-label").text('Alertas de Marcação');
        $.ajax({
            url: "controle/alertas/alerta_marcacao.php",
            type: "POST",
            cache: false,
            dataType: "json",
            success: function (resposta) {
                //console.log(resposta);
                $("#modal-content").html(resposta.msg);
                cotaMe  = resposta.cotaMe; 
                cotaMa  = resposta.cotaMa; 
                agendas_list = resposta.agendas_list; 
            }
        });
        
    });
   

    $('.open-crt').click(function (e) { 
        //e.preventDefault();
        $("#modal").modal({show:true});
        $("#modal-label").text('Alertas de Contrato');
        $.ajax({
            url: "controle/alertas/alerta_contrato.php",
            type: "POST",
            cache: false,
            dataType: "json",
            success: function (resposta) {
                //console.log(resposta);
                $("#modal-content").html(resposta.msg);
            }
        });
        
    });

});
    $(document).on('click','.VerD',   function (){ $(".sub-list").html(doc); });
    $(document).on('click','.VerF',   function (){ $(".sub-list").html(forn_cred); });
    $(document).on('click','.VerCMa', function (){ $(".sub-list").html(cotaMa); });
    $(document).on('click','.VerCMe', function (){ $(".sub-list").html(cotaMe); });
    $(document).on('click','.VerAGD', function (){ $(".sub-list").html(agendas_list); });
</script>
<!-- TimeLineSitcon/PPG_junho01.jpg TimeLineSitcon/PPG_junho02.jpg  TimeLineSitcon/PPG_junho03.jpg-->
<!-- <?php include 'banners/banners.php'; ?> -->
<div class="container p-0 m-0">
  <div class="row">
    <div class="col-sm">
        <div class="table-responsive">
            <div class="card" style="width: 34rem;">
                <div class="card-header text-white bg-warning">
                <!-- <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_YETVW1.json"  background="transparent"  speed="1"  style="width: 50px; height: 50px;"  loop  autoplay></lottie-player> -->
                Alertas
                </div>
                <ul class="list-group list-group-flush">
                <?php if($_SESSION['CdTpUsuario'] == 1){ ?>
                    <li class="list-group-item open-agd">Alertas de Marcações</li>
                    <li class="list-group-item open-cred">Alertas de Credenciamento</li>
                    <li class="list-group-item open-crt">Alertas de Contrato</li>
                <?php }else if($_SESSION['CdTpUsuario'] == 3){ ?>
                    <li class="list-group-item open-agd">Alertas de Marcações</li>
                <?php }else if($_SESSION['CdTpUsuario'] == 5){ ?>
                    <li class="list-group-item open-agd">Alertas de Marcações</li>
                    <li class='list-group-item d-flex justify-content-between align-items-center open-cred'></li>
                <?php } ?>
                </ul>
            </div>
        </div>
        <p></p>
        <div class="table-responsive">
            <div class="card" style="width: 34rem;">
                <div class="card-header text-white bg-info">
                    <!-- <lottie-player src="https://assets2.lottiefiles.com/temp/lf20_UZoio4.json"  background="transparent"  speed="1"  style="width: 30px; height: 30px;"  loop autoplay></lottie-player> -->
                    Notícias - CISMETRO INFORMA!
                </div>
                    <ul class="list-group list-group-flush">
                        <?php
                            $sql = " SELECT * FROM tbnoticia "; 
                            $limsql = $sql;
                            $sql = mysqli_query($db,$sql);
                            $qtdreg = mysqli_num_rows($sql);
                            $pages = new Paginator(3, 'pag');
                            $pages->set_total($qtdreg);
                            $limsql .=" ORDER BY tbnoticia.`data` DESC, tbnoticia.hora DESC ".$pages->get_limit();;
                            //echo $limsql;
                            $query = mysqli_query($db,$limsql)or die(mysqli_error());

                            while( $lin = mysqli_fetch_array($query))
                            {
                                echo '<li class="list-group-item noticia"  ><i class="far fa-newspaper"></i>  '. $lin['titulo'] .'  <span style="font-size: 11px; color: #227cdae0;  font-style: italic;"> Autor : '.$lin['autor'].'</span></li>';
                            }
                        ?>
                    </ul>
                </div>
                <?php echo $pages->page_links("?i=&");	 ?>
            </div>
    </div>
    <div class="col-sm">
    <div class="card">
        <div class="card-header"><lottie-player src="https://assets2.lottiefiles.com/temp/lf20_UZoio4.json"  background="transparent"  speed="1"  style="width: 30px; height: 30px;"  loop autoplay></lottie-player><span>Sitcon News - Informa!</span></div>
        <div id="carouselPagInicial" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselPagInicial" data-slide-to="0" class="active"></li>
                <li data-target="#carouselPagInicial" data-slide-to="1"></li>
                <li data-target="#carouselPagInicial" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img class="d-block w-100"  src="TimeLineSitcon/PPG_junho02.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100"  src="TimeLineSitcon/PPG_junho01.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="TimeLineSitcon/PPG_junho03.jpg" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselPagInicial" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#carouselPagInicial" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Proximo</span>
            </a>
        </div>
        </div>
    </div>
  </div>
</div>

<hr>

<div class="modal right fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content sub-list">
    
    </div>
  </div>
</div>

<script>
console.log(123)
/* console.log(<?= json_encode($banners); ?>);
<?php if(count($banners)) {  $banner = $banners[0]; ?>

    let setup = {				
        html: '<div style="height: 713px;">'+<? echo json_encode($banner->body)?>+"</div>",
        width: 1200,
        showCancelButton: false,
        confirmButtonColor: '#93908d',
        confirmButtonText: 'Não exibir essa mensagem novamente'
    };

    <?php if($banner->type == "P"){ ?> 

        setup.title = '<?=$banner->header?>';
        setup.showCancelButton =  true;
        setup.confirmButtonColor= '#3085d6',
        setup.cancelButtonColor = '#d33',
        setup.confirmButtonText = 'Tenho interesse',
        setup.cancelButtonText = 'Não tenho interesse'

    <?php } ?>

    Swal.fire(setup).then((result) => {

        axios.post("./banners/like.php", { 
            banner_id: <?=$banner->id?>,
            connection_id: <?=CLIENTE?>,
            like: result.isConfirmed
        }).then(res => {
            $("#modal").modal({
                show:false, 
                keyboard: true
            })	
        })	
        .catch(error => {
            Toast.fire({ icon: 'error', title: 'Um erro ocorreu' })
        })		

    })


<?php } ?> */
</script>

