<?php
namespace CDGNG\Statistics;

class Week extends Statistic
{
    public $export = "week";

    protected function getData($calendar)
    {
        return $calendar->getData('week');
    }

    protected function getSlotName()
    {
        return('Semaine (YYYY/SS)');
    }
}
