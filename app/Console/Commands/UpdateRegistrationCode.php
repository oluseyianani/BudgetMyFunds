<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RegistrationCode;

class UpdateRegistrationCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrationCode:update {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks registration code for a phone as verified on successful registration';

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
        $this->info('Updating registration code...');
        $data = $this->argument('data');

        $registrationCode = RegistrationCode::where([
            'phone' => $data['phone'], 'code' => $data['code']
            ])->first();

        // This ideally should not happen in production
        // But it helps for testing
        if (collect($registrationCode)->isEmpty()) {
            $this->info('No registration code found');
            return 1;
        }

        $registrationCode->update(['isVerified' => true]);
        $this->info('Registration code updated.');

        return 0;
    }
}
