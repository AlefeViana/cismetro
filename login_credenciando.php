<?php 
@session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Iconsorcio | Gestão em Consórcio de Saúde</title>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" integrity="sha256-+4KHeBj6I8jAKAU8xXRMXXlH+sqCvVCoK5GAFkmb+2I=" crossorigin="anonymous"></script>
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
</head>
<body class="text-center" style="background-image: linear-gradient(to right, white, #52ACEA, #145480)">
   <div class="container">

		<div class="row justify-content-center align-items-end">
			<div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 mt-4">
				<div class="card">
				     
					<div class="card-body">
					<form method="POST" action="./login_valida_cred.php">

					    <img class="img-fluid mb-3" src="img/iconsorcio_logo_azul_cred.png" >

						
						<hr>

					

						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><span class="fa fa-user"></span>
							</div>
							<input autofocus type="text" class="form-control cnpj" name="cnpj" placeholder="CNPJ" aria-label="CNPJ" aria-describedby="basic-addon1" required>
							
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon2"><i class="fa fa-envelope"></i></span>
							</div>
							<input type="email" class="form-control" name="email" placeholder="E-mail" aria-label="E-mail" aria-describedby="basic-addon2" required>
							
						</div>
								<a style="width: 100%; margin-bottom: 3%;" href="pg/credenciamento/EDITAL_DE_CREDENCIAMENTO_16_01_2023.pdf" target="_blank" class="btn btn-lg btn-success text-white"><i class="fas fa-file-download" aria-hidden="true"></i>Edital Credenciamento</a>
								<button type="submit" style="width: 100%;  margin-bottom: 3%;" class="btn btn-lg btn-warning text-white">Me Credenciar</button>
							    <a style="width: 100%;" href="./frm_login.php" class="btn btn-lg btn-primary text-white">Já sou credenciado!</a>

					</form>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>

<script>
$(".cnpj").mask("99.999.999/9999-99");
</script>

<?php session_destroy(); ?>