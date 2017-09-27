<?php
namespace CDGNG\Statistics;

use CDGNG\Csv;

abstract class Statistic
{
    public $export = "All";

    public $length = 0;
    public $title = "";

    public $dtstart;
    public $dtend;
    public $codes;

    public $calendars = array();

    abstract protected function getData($calendar);
    abstract protected function getSlotName();

    public function __construct($dtstart, $dtend, $codes)
    {
        $this->dtstart = $dtstart;
        $this->dtend = $dtend;
        $this->codes = $codes;
    }

    public function add($calendar)
    {
        $this->extendTitle($calendar->name);
        $calendar->parse($this->dtstart, $this->dtend, $this->codes);
        $this->calendars[$calendar->name] = array(
            'calendar' => $calendar,
            'data' => $this->getData($calendar),
        );
        $this->length += $calendar->length;
    }

    public function exportAsCsv()
    {
        $csv = new Csv();
        $csv->insert(
            array(
                'Nom',
                $this->getSlotName(),
                'Actions',
                'Modalités',
                'Temps(Min)'
            )
        );

        foreach ($this->calendars as $calName => $calendar) {
            foreach ($calendar['data'] as $slotName => $slot) {
                if ($slotName === 'duration') {
                    continue;
                }
                foreach ($slot['actions'] as $actionName => $action) {
                    if ($actionName === 'duration') {
                        continue;
                    }
                    foreach ($action as $modeName => $mode) {
                        if ($modeName === 'duration') {
                            continue;
                        }
                        $csv->insert(
                            array(
                                $calName,
                                $slotName,
                                $actionName,
                                $modeName,
                                round($mode / 60, 2)
                            )
                        );
                    }
                }
            }
        }
        return $csv;
    }

    private function extendTitle($name)
    {
        if ($this->title !== "") {
            $this->title .= '+';
        }
        $this->title .= $name;
    }
}
