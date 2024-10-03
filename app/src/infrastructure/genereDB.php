<?php

$drop='drop table if exists patient,praticien,status,specialite,rdv;';
$cpatient='
create table patient(
id UUID,
nom varchar(50),
prenom varchar(50),
dateNaissance date,
adresse varchar(100),
tel varchar(15),
primary key(id)
);
';
$cspecialite='
create table specialite(
id varchar(5),
label varchar(50),
description text,
primary key(id)
);
';
$cstatus='
create table status(
id int,
label varchar(50),
primary key(id)
);
';
$cpraticien='
create table praticien(
id UUID,
nom varchar(50),
prenom varchar(50),
adresse varchar(100),
tel varchar(15),
specialite varchar(5),
primary key(id),
foreign key(specialite) references specialite(id)
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
$res=$co->exec($cpatient);
$res=$co->exec($cspecialite);
$res=$co->exec($cstatus);
$res=$co->exec($cpraticien);
$res=$co->exec($crdv);

var_dump($res);
// foreach($res as $r){
// echo $r['quoi']." ".$r['feur'];
// }
