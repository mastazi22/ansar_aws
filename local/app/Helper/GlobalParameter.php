<?php

namespace App\Helper;
use Carbon\Carbon;

class GlobalParameter
{
    const RETIREMENT_AGE = 'retirement_age';
    const EMBODIMENT_PERIOD = 'embodiment_period';
    const REST_PERIOD = 'rest_period';
    const ALLOCATED_LEAVE = 'allocated_leave';
    const LAST_ANSAR_ID = "last_ansar_id";
    private $globalParameter;

    /**
     * GlobalParameter constructor.
     */
    public function __construct()
    {
        $this->globalParameter = \App\modules\HRM\Models\GlobalParameter::all();
    }

    public function getValue($type)
    {
        switch($type){
            case Self::RETIREMENT_AGE:
                return $this->globalParameter->where('param_name','retirement_age')->first()->param_value;
            case Self::EMBODIMENT_PERIOD:
                return $this->globalParameter->where('param_name','embodiment_period')->first()->param_value;
            case Self::REST_PERIOD:
                return $this->globalParameter->where('param_name','rest_period')->first()->param_value;
            case Self::ALLOCATED_LEAVE:
                return $this->globalParameter->where('param_name','allocated_leave')->first()->param_value;
            case Self::LAST_ANSAR_ID:
                return $this->globalParameter->where('param_name','last_ansar_id')->first()->param_value;

        }
    }
    public function getUnit($type)
    {
        switch($type){
            case Self::RETIREMENT_AGE:
                return $this->globalParameter->where('param_name','retirement_age')->first()->param_unit;
            case Self::EMBODIMENT_PERIOD:
                return $this->globalParameter->where('param_name','embodiment_period')->first()->param_unit;
            case Self::REST_PERIOD:
                return $this->globalParameter->where('param_name','rest_period')->first()->param_unit;
            case Self::ALLOCATED_LEAVE:
                return $this->globalParameter->where('param_name','allocated_leave')->first()->param_unit;

        }
    }
    public function getServiceEndedDate($joining_date){
        $unit = $this->getUnit($this::EMBODIMENT_PERIOD);
        $value = $this->getValue($this::EMBODIMENT_PERIOD);
        if (strcasecmp($unit, "Year") == 0) {
            $service_ending_period = $value;
            $service_ended_date = Carbon::parse($joining_date)->addYear($service_ending_period)->subDay(1);
        } elseif (strcasecmp($unit, "Month") == 0) {
            $service_ending_period = $value;
            $service_ended_date = Carbon::parse($joining_date)->addMonth($service_ending_period)->subDay(1);
        } elseif (strcasecmp($unit, "Day") == 0) {
            $service_ending_period = $value;
            $service_ended_date = Carbon::parse($joining_date)->addDay($service_ending_period)->subDay(1);
        }
        return $service_ended_date;
    }
    public function getActiveDate($rest_date){
        $unit = $this->getUnit($this::REST_PERIOD);
        $value = $this->getValue($this::REST_PERIOD);
        if (strcasecmp($unit, "Year") == 0) {
            $active_date = Carbon::parse($rest_date)->addYear($value);
        } elseif (strcasecmp($unit, "Month") == 0) {;
            $active_date = Carbon::parse($rest_date)->addMonth($value);
        } elseif (strcasecmp($unit, "Day") == 0) {
            $active_date = Carbon::parse($rest_date)->addDay($value);
        }
        return $active_date;
    }

}