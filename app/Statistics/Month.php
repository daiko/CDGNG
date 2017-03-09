<?php
namespace CDGNG\Statistics;

class Month extends Statistic
{
    public $export = "month";

    protected function getData($calendar)
    {
        return $calendar->getData('month');
    }

    protected function getSlotName()
    {
        return('Mois (YYYY/MM)');
    }
}
