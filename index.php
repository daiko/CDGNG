<?php
/**
 * index file
 *
 * @author Loris Puech
 * @author Florestan Bredow <florestan.bredow@daiko.fr>
 *
 * @version GIT: $Id$
 *
 */
namespace CDGNG;

if (!is_dir('vendor')) {
    print('Vous devez executer "composer install".');
    exit;
}
$loader = require __DIR__ . '/vendor/autoload.php';

include "./data/actions.php";
include "./data/modalites.php";

$configuration = new PhpConfig('config.php');
$configuration->read();

$model = new Model($configuration, $GLOBALS['actions'], $GLOBALS['modalites']);

$controller = new Controller($model);
$controller->run($_POST);
