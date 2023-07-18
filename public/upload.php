<?php
session_start();

header('Content-type:application/json;charset=utf-8');

try {
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    //$filepath = sprintf('files/%s_%s', uniqid(), $_FILES['file']['name']);

	$path_parts = pathinfo($_FILES["file"]["name"]);
	$extension = $path_parts['extension'];
	$filename=uniqid().".".$extension;


	
	//Upload documenti:
	//$from=="allegati" -> da lente o cliccando sui servizi
	//$from=="allegati_cantieri" -> 
	
	$from=$_POST['from'];
	  
	if ($from=="allegati") {
		$ref_user=$_POST['ref_user'];
		$periodo=$_POST['periodo'];
		$id_categoria=$_POST['id_categoria'];
		$id_attivita=$_POST['id_attivita'];
		$id_settore=$_POST['id_settore'];
		
		$sub="allegati/$ref_user";
		@mkdir($sub);
		$sub="allegati/$ref_user/$periodo";
		@mkdir($sub);
		$sub="allegati/$ref_user/$periodo/$id_categoria";
		@mkdir($sub);
		$sub="allegati/$ref_user/$periodo/$id_categoria/$id_attivita";
		@mkdir($sub);
		$sub="allegati/$ref_user/$periodo/$id_categoria/$id_attivita/$id_settore";
		@mkdir($sub);
	}	
	
	if ($from=="allegati_cantiere") {
		$id_cantiere=$_POST['id_cantiere'];
		$sub="allegati/cantieri/$id_cantiere";
		@mkdir($sub);
	}

	if ($from=="allegati_aziende") {
		$id_azienda=$_POST['id_azienda'];
		//eventuali caratteri contenuti da eliminare
		$id_azienda = trim(preg_replace('/\s+/', ' ', $id_azienda));
		
		$azienda=$_POST['azienda'];
		$azienda = trim(preg_replace('/\s+/', ' ', $azienda));
		if (strlen($id_azienda)==0) $id_azienda = hash("md5", $azienda);
		$sub="allegati/aziende/$id_azienda";
		@mkdir($sub);
	}
	if ($from=="allegati_utili") {
		$sub="allegati/documenti_utili";
		@mkdir($sub);
	}
	
	$filepath = "$sub/".$filename;
    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }


	
    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path' => $filepath,
		'filename' =>$filename,
		'from' =>$from
	]);

} catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}