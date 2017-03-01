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

session_start();

$users = new PhpFiles\Users('data/users.php');
$users->read();

$actions = new PhpFiles\Actions('data/actions.php');
$actions->read();

$modes = new PhpFiles\Modes('data/modes.php');
$modes->read();

$calendars = new PhpFiles\Calendars('data/calendars.php');
$calendars->read();

$config = new PhpFiles\Config('config.php');
$config->read();

$model = new Model($config, $actions, $modes, $calendars, $users);

$controller = new Controller($model);
$controller->run($_POST, $_GET, $_SESSION);
