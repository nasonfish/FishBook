<?php
global $_CONFIG;
$_CONFIG = array();

$_CONFIG['main:title'] = 'Concert Choir Management Program';

include('../libs/Choir.class.php');
global $manager;
$manager = new Choir;
include('Peregrine/Peregrine.php');
$peregrine = new Peregrine();
$peregrine->init();
