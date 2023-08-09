<?php 

require "../vendor/autoload.php";

@session_start();
$msg = new \Plasticbrain\FlashMessages\FlashMessages();

if($_SESSION['CdUsuario']) { $msg->info('Você está autenticado como usuário '.$_SESSION['NmUsuario'].' .', "index.php" ); };

?>
<!DOCTYPE html>
<html>
<head>
	<link id="favicon" rel="icon" type="image/png" sizes="64x64" href="ico_sitcon.png">
	<title>Iconsorcio | Gestão em Consórcio de Saúde </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>

<body class="text-center" style="background-image: linear-gradient(to right, white, #52ACEA, #145480)">
	<div class="container">
	    <div class="row justify-content-center">
			<div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 my-4">
				<div class="card">
					<div class="card-body">
					    					   
					    
						<form method="POST" action="login_valida.php">
			     	
							
							<img class="img-fluid" src="img/iconsorcio_logo_azul.png" >
							
							<hr>

							<?php $invalid = $msg->hasErrors() ? true : false; $msg->hasMessages() ? $msg->display() : "" ; ?>						

							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><span class="fa fa-user"></span>
								</div>
								<input autofocus type="text" class="form-control <?php echo $invalid ? "is-invalid" : "" ?>" name="usuario" placeholder="Usuário" aria-label="Username" aria-describedby="basic-addon1" required>
								<div class="invalid-feedback">
							     	As credenciais fornecidas são inválidas
								</div>
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon2"><i class="fa fa-key"></i></span>
								</div>
								<input type="password" class="form-control <?php echo $invalid ? "is-invalid" : "" ?>" name="senha" placeholder="Senha" aria-label="Password" aria-describedby="basic-addon2" required>
								<div class="invalid-feedback">
									As credenciais fornecidas são inválidas
								</div>
							</div>
							
							<!-- <a href="login_credenciando.php">Credenciamento</a> -->
							
							&nbsp;
							<a href="http://iconsorciosaude3.com.br/suporte/recsenha.php" class="" target="_blank" tabindex="-1">Recuperar Senha</a>

							<br>
							<br>
					
						
							<button type="submit" class="btn btn-block btn-lg btn-primary">Entrar</button>
						</form>
					</div>
				</div>
															
				
				
				
				
			</div><!--input-->
		</div><!--row-->
	</div>
	<!-- <div class="div-question-circle"><a href="#question"><i class="fa fa-question-circle question-circle" aria-hidden="true"></i></a></div>
	<div id="question" class="quest">	
		<a href="#fechar" title="Fechar" class="fechar">x</a>
		<h2><img src="http://iconsorciosaude3.com.br/suporte/img/logo.png" alt="" style="width: 200px;"></h2>
		<p>Av. Zita Soares de Oliveira, 212 <br/>Sala 602, Centro, Ipatinga-MG</p>
		<p>Contato: (31)3822-4656 - atendimento@sitcon.com.br</p>
		<p>Suporte: <a href="http://suporte.sitcon.com.br" target="_blank">suporte.sitcon.com.br</a></p>
	</div> -->

</body>
<style type="text/css">
    
    body {
		padding-top: 4rem;
	}

	.link-group {
	    float: right;
	    margin-top: -8px;
	}
	.link {
	    color: #61058e;
	}
	.question-circle{
		color: white;
		font-size: 25px;
	}
	.question-circle:hover{
		color: orange;
		cursor: help;
	}
	.div-question-circle{
		position: absolute;
		right: 30px;
		top:30px;
	}
	.quest{
		opacity: 0;
		pointer-events: none;
	}
	.quest:target {
		opacity: 1;
		pointer-events: auto;
	}
	#question {
		width: 250px;
	    position: absolute;
	    padding: 15px 20px;
	    background: #fff;
	    right: 43px;
	    top: 45px;
	    border-radius: 10px;
	    transition: opacity 400ms ease-in;
	    z-index: 3;
	}
	.fechar {
		position: absolute;
		width: 30px;
		right: -15px;
		top: -20px;
		text-align: center;
		line-height: 30px;
		margin-top: 5px;
		background: #ff4545;
		border-radius: 50%;
		font-size: 16px;
		color: #8d0000;
	}
</style>
</html>

<?php session_destroy(); ?>