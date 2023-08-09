<?php

/**
 * @author Caio Chami
 * @since 2020-09-16
 * 
 * migrates patient to logged user county based on neighborhoodId 
 */

 require "../conecta.php";

use App\Objects\Auth;
use App\Objects\Patient;



 if(!count($_POST)) {
	http_response_code(422);
	die('invalid data');
 }

 if(!$userId) {
	http_response_code(403);
	die('unauthorized');
 }

 $msg = new \Plasticbrain\FlashMessages\FlashMessages();

 $redirectBack = '/' . FOLDER_NAME . '/index.php?' . $_POST['query_string'];

 $patientId = $_POST['patient_id'] ?? null;

 $user = Auth::user($connection);

 if(!$user) {
	http_response_code(403);
	die('unauthorized');
 }

 if(!$patientId) {
	http_response_code(422);
	die('patient id is required');
 }
 
 $patient = Patient::find($connection, $patientId);

 if(!$patient) {
	http_response_code(404);
	die('patient not found');
 }

 if($patient->migrate((int) $user->county()->neighborhoods()->first()->id)){
	 $msg->success('Paciente #' . $patient->id . ' migrado com sucesso', $redirectBack);
 }
 else{
	$msg->error('Não foi possível migrar o paciente #' . $patient->id , $redirectBack);
 }

 