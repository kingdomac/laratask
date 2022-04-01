<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectDeadLineNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class ProjectDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laratask:project-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'notify when project is near to deadline (before 2 days)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $superAdmins = User::superAdmins()->get();
        return   Project::query()
            ->whereBetween('due_date', [Carbon::now()->addDays(2), Carbon::now()])
            ->each(function ($project) use ($superAdmins) {
                Notification::send($superAdmins, new ProjectDeadLineNotification($project));
            });
    }
}
