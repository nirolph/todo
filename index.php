<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once 'vendor/autoload.php';

use Domain\Context\Task\Entity\Task;
new Task();