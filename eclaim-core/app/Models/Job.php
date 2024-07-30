<?php

// app/Models/Job.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\Models\States\JobState;

class Job extends Model
{
    use HasStates;

    protected $stateClass = JobState::class;

    // Add other model configurations and relationships
}
