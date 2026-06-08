<?php

namespace Sayed\SayedDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sayed-dashboard:install
                            {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Sayed Dashboard package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Installing Sayed Dashboard...');
        $this->newLine();

        // Publish configuration
        $this->publishConfiguration();
        
        // Publish assets
        $this->publishAssets();
        
        // Run migrations
        $this->runMigrations();
        
        // Create default admin role and user
        $this->createAdminUser();
        
        // Publish views (optional)
        if ($this->confirm('Do you want to publish the dashboard views for customization?')) {
            $this->publishViews();
        }
        
        $this->newLine();
        $this->info('✅ Sayed Dashboard installed successfully!');
        $this->newLine();
        $this->info('📍 You can now access your dashboard at: /dashboard');
        $this->info('📧 Admin email: admin@example.com');
        $this->info('🔑 Admin password: password');
        $this->newLine();
        
        $this->warn('⚠️  Please change the default admin password after first login!');
    }

    /**
     * Publish configuration file.
     */
    protected function publishConfiguration()
    {
        $this->info('📝 Publishing configuration...');
        
        $this->call('vendor:publish', [
            '--provider' => 'Sayed\SayedDashboard\SayedDashboardServiceProvider',
            '--tag' => 'sayed-dashboard-config',
            '--force' => $this->option('force'),
        ]);
        
        $this->info('✅ Configuration published to config/sayed-dashboard.php');
    }

    /**
     * Publish assets.
     */
    protected function publishAssets()
    {
        $this->info('🎨 Publishing assets...');
        
        $this->call('vendor:publish', [
            '--provider' => 'Sayed\SayedDashboard\SayedDashboardServiceProvider',
            '--tag' => 'sayed-dashboard-assets',
            '--force' => $this->option('force'),
        ]);
        
        $this->info('✅ Assets published to public/vendor/sayed-dashboard');
    }

    /**
     * Publish views.
     */
    protected function publishViews()
    {
        $this->info('📄 Publishing views...');
        
        $this->call('vendor:publish', [
            '--provider' => 'Sayed\SayedDashboard\SayedDashboardServiceProvider',
            '--tag' => 'sayed-dashboard-views',
            '--force' => $this->option('force'),
        ]);
        
        $this->info('✅ Views published to resources/views/vendor/sayed-dashboard');
    }

    /**
     * Run migrations.
     */
    protected function runMigrations()
    {
        $this->info('🗄️  Running migrations...');
        
        $this->call('migrate');
        
        $this->info('✅ Migrations completed');
    }

    /**
     * Create admin user.
     */
    protected function createAdminUser()
    {
        $this->info('👤 Creating admin user...');
        
        // Get admin details from user input
        $name = $this->ask('Enter admin name', 'Admin User');
        $email = $this->ask('Enter admin email', 'admin@example.com');
        $password = $this->secret('Enter admin password');
        
        if (empty($password)) {
            $password = 'password';
            $this->warn('Using default password: password');
        }
        
        // Create the user using your User model
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        
        $user = $userModel::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
        
        // Assign admin role (we'll create this later)
        // For now, just set a flag or create a simple role system
        $user->is_admin = true;
        $user->save();
        
        $this->info('✅ Admin user created successfully!');
    }
}