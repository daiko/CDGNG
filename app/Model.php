<?php
namespace CDGNG;

/**
 * Class Model
 *
 * @author Loris Puech
 * @author Florestan Bredow <florestan.bredow@daiko.fr>
 *
 * @version GIT: $Id$
 */
class Model
{
    private $config;
    public $calendars;
    public $actions;
    public $modes;
    public $users;

    /**
     * Constructeur
     *
     * @param string $configPath Path to config file
     */
    public function __construct($config, $actions, $modes, $calendars, $users)
    {
        $this->config = $config;
        $this->actions = $actions;
        $this->modes = $modes;
        $this->calendars = $calendars;
        $this->users = $users;
    }
}
