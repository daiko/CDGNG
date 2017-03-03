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
    public $calendars;
    public $actions;
    public $modes;
    public $users;

    /**
     * Constructeur
     *
     */
    public function __construct($actions, $modes, $calendars, $users)
    {
        $this->actions = $actions;
        $this->modes = $modes;
        $this->calendars = $calendars;
        $this->users = $users;
    }
}
