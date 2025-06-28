<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test authentication functionality';

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
        $this->info('Testing authentication functionality...');
        
        // Test user creation
        try {
            $user = User::create([
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'test@farmshop.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);
            
            $this->info('✅ User created successfully: ' . $user->email);
            $this->info('✅ Username: ' . $user->username);
            $this->info('✅ Email verified: ' . ($user->email_verified_at ? 'Yes' : 'No'));
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating user: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('✅ Authentication test completed successfully!');
        return 0;
    }
}
