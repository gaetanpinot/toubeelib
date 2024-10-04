<?php

require_once __DIR__ .'/../../vendor/autoload.php';
$drop='drop table if exists patient,praticien,status,specialite,rdv;';
$cspecialite='
create table specialite(
id varchar(5),
label varchar(50) not null,
description text not null,
primary key(id)
);
';
$cstatus='
create table status(
id int,
label varchar(50) not null,
primary key(id)
);
';
$cpraticien='
create table praticien(
id UUID,
rpps varchar(50) not null,
nom varchar(50) not null,
prenom varchar(50) not null,
adresse varchar(100) not null,
tel varchar(15) not null,
specialite varchar(5) not null,
primary key(id),
foreign key(specialite) references specialite(id)
);
';
$cpatient='
create table patient(
id UUID,
nom varchar(50) not null,
prenom varchar(50) not null,
dateNaissance date not null,
adresse varchar(100),
tel varchar(20),
mail varchar(100),
idMedcinTraitant UUID,
numSecuSociale varchar(50),
primary key(id)
);
';
$crdv='
create table rdv(
id UUID,
date timestamp,
patientId UUID,
praticienId UUID,
status int,
primary key(id),
foreign key(patientId) references patient(id),
foreign key(praticienId) references praticien(id),
foreign key(status) references status(id)
);
';




$co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
$res=$co->exec($drop);
$res=$co->exec($cspecialite);
$res=$co->exec($cstatus);
$res=$co->exec($cpraticien);
$res=$co->exec($cpatient);
$res=$co->exec($crdv);


// specialite
// id varchar(5),
// label varchar(50) not null,
// description text not null,
// primary key(id)

$spe = [
	'A' => [
		'ID' => 'A',
		'label' => 'Dentiste',
		'description' => 'Spécialiste des dents'
	],
	'B' => [
		'ID' => 'B',
		'label' => 'Ophtalmologue',
		'description' => 'Spécialiste des yeux'
	],
	'C' => [
		'ID' => 'C',
		'label' => 'Généraliste',
		'description' => 'Médecin généraliste'
	],
	'D' => [
		'ID' => 'D',
		'label' => 'Pédiatre',
		'description' => 'Médecin pour enfants'
	],
	'E' => [
		'ID' => 'E',
		'label' => 'Médecin du sport',
		'description' => 'Maladies et trausmatismes liés à la pratique sportive'
	],
];
$query='insert into specialite (id, label, description) values (:ID, :label, :description);';
$insert= $co->prepare($query);
foreach($spe as $s){
	$insert->execute($s);
}




//creation des patients
// id UUID,
// nom varchar(50),
// prenom varchar(50),
// dateNaissance date,
// adresse varchar(100),
// tel varchar(15),
$faker = Faker\Factory::create();
$patientIds=[];
	$query="insert into patient (id, nom, prenom, dateNaissance, adresse, tel) values (:id,:nom,:prenom,:date,:adresse,:tel);";
	$insert = $co->prepare($query);
// for($i=0;$i<40;$i++){
// 	$nom=$faker->name();
// 	$prenom=$faker->name();
// 	$date=$faker->date();
// 	$adresse=$faker->address();
// 	$tel=$faker->phoneNumber();
// 	$id=$faker->uuid();
// 	$insert->execute(['id'=>$id,'nom'=>$nom,'prenom'=>$prenom,'date'=>$date,'adresse'=>$adresse,'tel'=>$tel]);
// 	$patientIds[]=$id;
// }

var_dump($res);
// foreach($res as $r){
// echo $r['quoi']." ".$r['feur'];
// }
