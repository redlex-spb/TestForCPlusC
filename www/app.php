<?php

require_once ("TransactionCommissions.php");

use CPlusC\TestRefactoring\TransactionCommissions;

$calc =  new TransactionCommissions($argv[1]);
$calc->startCalculation();