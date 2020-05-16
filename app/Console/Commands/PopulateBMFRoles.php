<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class PopulateBMFRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates roles available in the BudgetMyFunds Application';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Populating roles... 🚀');
        $roles = ['Owner', 'Collaborator', 'Admin', 'Super admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'role' => $role
            ]);
        }

        $this->info('Roles successfully seeded... 🔥');
        return 0;
    }
}
