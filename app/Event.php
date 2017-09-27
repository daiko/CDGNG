<?php
namespace CDGNG;

/**
 * Class Event
 *
 * @author Florestan Bredow <florestan.bredow@daiko.fr>
 *
 * @version GIT: $Id$
 *
 */
class Event
{

    /*************************************************************************
     * STATES
     ************************************************************************/

    /**
     * Event's description from ical Class
     */
    private $event;

    /*************************************************************************
     * METHODS
     ************************************************************************/

    /**
     * Constructor
     *
     * @param array $event Event's description from ical Class
     *
     */
    public function __construct($event)
    {
        $this->load($event);
    }

    /**
     * Load event from ical class description array
     *
     * @param array $event Event's description from ical Class
     */
    private function load($event)
    {
        $this->event = $event;
        $this->standardize();
    }

    /**
     * Standardize description
     */
    private function standardize()
    {
        // Gestion des DTXXX qui ne sont pas des tableaux
        // Dans les calendriers édités avec google ce ne sont pas des tableaux
        // Alors que dans ceux avec thunderbird c'est le cas.
        // --> On met tout le monde d'accord avec des tableaux...

        if (!is_array($this->event["DTSTART"])) {
            $this->event["DTSTART"] = array(
                "unixtime"     => $this->event["DTSTART"],
                "TZID"         => "Europe/Paris",);
        }

        // Certains évènement n'ont pas de fin.
        if (isset($this->event["DTEND"])) {
            if (!is_array($this->event["DTEND"])) {
                //$ts = $events["DTEND"];
                $this->event["DTEND"] = array(
                    "unixtime"     => $this->event["DTEND"],
                    "TZID"         => "Europe/Paris",);
            }
        }
    }

    /**
     * check if this is a full day event
     *
     * @return true or false.
     */
    public function isFullDay()
    {
        if ($this->getLength() == 86400) {
            return true;
        }
        return false;
    }

    /**
     * check if event is valid
     *
     * @param array $events Array of events object to check superposed
     * @param array $error  writable to return error on unvalid event
     *
     * @return true or false.
     */
    public function isValid($events, $actions, $modes, &$error)
    {

        // Event without end
        if (!isset($this->event["DTEND"])) {
            $error = array(99, "Sans fin.");
            return false; //Ignored
        }

        // Event without summary
        if (!isset($this->event["SUMMARY"])) {
            $error = array(99, "Sans titre.");
            return false; //Ignored
        }

        // Recurcive event
        if (array_key_exists("RRULE", $this->event)) {
            $error = array(2, "Récursif.");
            return false;
        }

        $code = $this->getCode();

        // Uncoded event
        if ($code == false) {
            $error = array(0, "Sans code.");
            return false;
        }

        // bad code
        if (!$modes->isExist($code["mod"])) {
            $error = array(2, "Mauvais code (modalité).");
            return false;
        }

        // bad code
        if (!$actions->isExist($code["act"])) {
            $error = array(2, "Mauvais code (action).");
            return false;
        }

        // Event longer then 12 hours
        if ($this->getLength() >= 43200) {
            $error = array(1, "Trop long (+ de 12h)");
            return false;
        }

        // Superposed event.
        foreach ($events as $event) {
            if ($this->isOverlap($event)) {
                $error = array(2, "se superpose à ".$event->getSummary().".");
                return false;
            }
        }

        // Everything go right.
        return true;
    }

    /**
     * Check if two events are overlapping
     *
     * @param Event $event Event object like this
     */
    private function isOverlap($event)
    {
        if (!(($event->getEnd() <= $this->getStart())
            or ($this->getEnd() <= $event->getStart()) )
        ) {
            return true;
        }
        return false;
    }

    /**
     * Return Code of event
     *
     * @return array {"modalite" => "X", "action" => "ZZZ"}
     */
    public function getCode()
    {
        if (!preg_match('#\[(?<code>[0-9]+[A-z])\]#', $this->getSummary(), $tabMatches)) {
            return false;
        }

        $code = strtoupper($tabMatches['code']);

        //Unification de la syntaxe ( 4 charactères )
        if (strlen($code) == 2) {
            $code = "00" . $code;
        } elseif (strlen($code) == 3) {
            $code = "0" . $code;
        }

        return array(
            "mod" => substr($code, -1),
            "act" => substr($code, 0, -1),
        );
    }

    public function getSummary()
    {
        if (isset($this->event["SUMMARY"])) {
            return $this->event["SUMMARY"];
        }
        return "No Summary";
    }

    public function getStart()
    {
        return $this->event["DTSTART"]["unixtime"];
    }

    public function getEnd()
    {
        if (isset($this->event["DTEND"]["unixtime"])) {
            return $this->event["DTEND"]["unixtime"];
        }
        return $this->event["DTSTART"]["unixtime"];
    }

    public function getLength()
    {
        return $this->getEnd() - $this->getStart();
    }

    public function cutByDay()
    {
        $output = array();

        //definir le TS du jour 1
        $curDate = $this->getStart();
        while ($curDate < $this->getEnd()) {
            $date = getdate($curDate);
            $dayStart = mktime(0, 0, 0, $date["mon"], $date["mday"], $date["year"]);
            $dayEnd = mktime(23, 59, 59, $date["mon"], $date["mday"], $date["year"]);

            // L'évènement ne se termine pas ce jour.
            $output[$dayStart] = $this->getEnd() - $curDate;
            if ($dayEnd < $this->getEnd()) {
                $output[$dayStart] = $dayEnd - $curDate;
            }
            // Un jour de plus.
            $curDate = mktime(0, 0, 0, $date["mon"], $date["mday"] + 1, $date["year"]);
        }

        $this->getStart();

        return $output;
    }
}
