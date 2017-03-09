<?php
namespace CDGNG\Statistics;

class Year extends Statistic
{
    public $export = "year";

    protected function getData($calendar)
    {
        return $calendar->getData('year');
    }

    protected function getSlotName()
    {
        return('Année');
    }
}
