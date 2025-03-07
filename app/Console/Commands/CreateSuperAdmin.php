<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'create:super-admin';

    protected $description = 'Creates a super admin for the back-office';


    public function handle()
    {
        $this->output->title("Creating super admin...");


        User::create([
            'email' => 'superadmin@test.fr',
            'password' => Hash::make('1234'),
            'role' => 'super admin'
        ]);


        $this->output->newLine();
        $this->output->success('Done.');
        return Command::SUCCESS;
    }
}
