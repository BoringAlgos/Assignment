<?php

namespace App\Models;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

class JobState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default('appointment_set')
            ->allowTransition('appointment_set', 'vehicle_submitted')
            ->allowTransition('vehicle_submitted', 'job_started')
            ->allowTransition('job_started', 'job_completed')
            ->allowTransition('job_completed', 'vehicle_delivered');
    }
}
