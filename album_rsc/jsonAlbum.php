<?php
// Classes
require_once('Photo.php');
//--------------------------------------------------
// Fonctions
function echoJson($objJson){
	header('Content-Type:application/json;charset=utf-8');
	echo json_encode($objJson);
}
//------------------------------------------------------------
function traiterFichierPhoto($id,$tmpPhoto,$dimMax,$urlDestination){
	$tabSize=getimagesize($tmpPhoto);
	$largeur=$tabSize[0];
	$hauteur=$tabSize[1];
	$idSource=imageCreateFromJpeg($tmpPhoto);
	if($largeur/$hauteur<1){// Si verticale, hauteurMax s'impose.
		if($hauteur>$dimMax){
			sauverFichierPhoto($idSource,$largeur,$hauteur,round($largeur*$dimMax/$hauteur),$dimMax,$urlDestination);
		}
	}
	else if($largeur>$dimMax){// Sinon largeurMax s'impose.
		sauverFichierPhoto($idSource,$largeur,$hauteur,$dimMax,round($hauteur*$dimMax/$largeur),$urlDestination);
	}
	else copy($tmpPhoto,$urlDestination);// Pas de redimensionnement, simple copie.
}
//------------------------------------------------------------
function sauverFichierPhoto($idSource,$largeur,$hauteur,$largeurRedim,$hauteurRedim,$urlDestination){
	$idDestination=imageCreateTrueColor($largeurRedim,$hauteurRedim);
	imageCopyResampled($idDestination,$idSource,0,0,0,0,$largeurRedim,$hauteurRedim,$largeur,$hauteur);
	imageJpeg($idDestination,$urlDestination,80);
	imageDestroy($idSource);
	imageDestroy($idDestination);
}
//--------------------------------------------------
// Chemin des ressources serveur
const RSC='../album_rsc/';
// Connexion DB: $connexion
const DB_SERVEUR='localhost';
const DB_BASE='form_album';
const DB_ID='root';
const DB_MDP='';
try{
	$connexion=new PDO('mysql:host='.DB_SERVEUR.';dbname='.DB_BASE,DB_ID,DB_MDP);
}catch(PDOException $e){
	echo "<pre>ERREUR : connexion DB impossible\n{$e->getMessage()}\n</pre>";
	exit;
}
// UTF-8
$req="SET NAMES utf8";
$connexion->exec($req);
//--------------------------------------------------
// Variables REQUEST
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
$titre=isset($_REQUEST['titre'])?$_REQUEST['titre']:'';
//--------------------------------------------------
// Lister
if($action=='lister'){
	$objJson=new stdClass();
	$objJson->action=$action;
	$req="SELECT id,titre,UNIX_TIMESTAMP(dateMaj)*1000 AS dateMaj FROM photo ORDER BY id DESC";
	$jeu=$connexion->query($req);
	$objJson->photos=[];
	while($photo=$jeu->fetchObject('Photo')){
		$photo->urlV=RSC."photos/photo_{$photo->id}_v.jpg";
		$photo->urlP=RSC."photos/photo_{$photo->id}_p.jpg";
		$objJson->photos[]=$photo;
	}
	echoJson($objJson);
}
//--------------------------------------------------
// Valider
if($action=='valider'){
	// Update en base.
	$req="UPDATE photo SET titre={$connexion->quote($titre)} WHERE id={$id}";
	$connexion->exec($req);
	// Réponse JSON.
	$req="SELECT id,titre,UNIX_TIMESTAMP(dateMaj)*1000 AS dateMaj FROM photo WHERE id={$id}";
	$jeu=$connexion->query($req);
	$photo=$jeu->fetchObject('Photo');
	$photo->urlV=RSC."photos/photo_{$photo->id}_v.jpg";
	$photo->urlP=RSC."photos/photo_{$photo->id}_p.jpg";
	$objJson=new stdClass();
	$objJson->action=$action;
	$objJson->photo=$photo;
	echoJson($objJson);
}
//--------------------------------------------------
// Supprimer
if($action=='supprimer'){
	// Suppression en base.
	$req="DELETE FROM photo WHERE id={$id}";
	//$connexion->exec($req); // DEBUG : Commenter pour tester sans supprimer.
	// Suppression des fichiers.
	//@unlink("photos/photo_{$id}_v.jpg");
	//@unlink("photos/photo_{$id}_p.jpg");
	// Réponse JSON.
	$objJson=new stdClass();
	$objJson->action=$action;
	$objJson->id=$id;
	echoJson($objJson);
}
//--------------------------------------------------
// Uploader
if($action=='uploader'){
	$objJson=new stdClass();
	$objJson->action=$action;
	$objJson->uploadOk=0;
	$tmpPhoto=$_FILES['fichier']['tmp_name'];
	if(is_uploaded_file($tmpPhoto) && $_FILES['fichier']['size']>0 && $_FILES['fichier']['size']<10*1024*1024){
		$objJson->uploadOk=1;
		// Sauvegarder la photo en base.
		$req="INSERT INTO photo (titre) VALUES('Sans titre')";
		$connexion->exec($req);
		$id=$connexion->lastInsertId();
		// Sauvegarder la vignette et la photo en fichiers.
		traiterFichierPhoto($id,$tmpPhoto,150,"photos/photo_{$id}_v.jpg");
		traiterFichierPhoto($id,$tmpPhoto,450,"photos/photo_{$id}_p.jpg");
		// Réponse JSON.
		$req="SELECT id,titre,UNIX_TIMESTAMP(dateMaj)*1000 AS dateMaj FROM photo WHERE id={$id}";
		$jeu=$connexion->query($req);
		$photo=$jeu->fetchObject('Photo');
		$photo->urlV=RSC."photos/photo_{$photo->id}_v.jpg";
		$photo->urlP=RSC."photos/photo_{$photo->id}_p.jpg";
		$objJson->photo=$photo;
	}
	echoJson($objJson);
}
?>