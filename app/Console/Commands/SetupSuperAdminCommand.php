<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SetupSuperAdminCommand extends Command
{
    protected $signature = 'admin:setup-super-admin 
                            {--email=sergyo.vutegha@gmail.com : Email of the super admin user}
                            {--name=Serge : Name of the super admin user}
                            {--force : Force update if user already exists}';
    
    protected $description = 'Setup super admin role and assign it to specified user';

    public function handle()
    {
        $this->info('🔧 Setting up Super Admin...');
        $this->line('');

        // Step 1: Create or update super admin role
        $this->info('1. Creating/updating super admin role...');
        $superAdminRole = $this->createOrUpdateSuperAdminRole();
        
        // Step 2: Create or update super admin user
        $this->info('2. Creating/updating super admin user...');
        $superAdminUser = $this->createOrUpdateSuperAdminUser();
        
        // Step 3: Assign role to user
        $this->info('3. Assigning super admin role...');
        $this->assignSuperAdminRole($superAdminUser, $superAdminRole);
        
        // Step 4: Verify setup
        $this->info('4. Verifying setup...');
        $this->verifySetup($superAdminUser, $superAdminRole);
        
        $this->line('');
        $this->info('✅ Super Admin setup completed successfully!');
        
        return 0;
    }

    private function createOrUpdateSuperAdminRole()
    {
        $roleName = 'super admin';
        
        // Create or find the super admin role
        $role = Role::firstOrCreate(['name' => $roleName]);
        
        if ($role->wasRecentlyCreated) {
            $this->line("   ✅ Created new role: {$roleName}");
        } else {
            $this->line("   ✅ Found existing role: {$roleName}");
        }
        
        // Get all permissions
        $allPermissions = Permission::all();
        $this->line("   📊 Found {$allPermissions->count()} permissions in system");
        
        // Assign all permissions to super admin role
        $role->syncPermissions($allPermissions);
        $this->line("   🔑 Assigned all {$allPermissions->count()} permissions to super admin role");
        
        // Display some key permissions
        $keyPermissions = $allPermissions->take(5);
        $this->line("   📋 Key permissions include:");
        foreach ($keyPermissions as $permission) {
            $this->line("      • {$permission->name}");
        }
        if ($allPermissions->count() > 5) {
            $this->line("      • ... and " . ($allPermissions->count() - 5) . " more");
        }
        
        return $role;
    }

    private function createOrUpdateSuperAdminUser()
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $force = $this->option('force');
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            if (!$force && !$this->confirm("User {$email} already exists. Update it?")) {
                $this->line("   ⏭️ Skipping user update");
                return $user;
            }
            
            // Update existing user
            $user->update([
                'name' => $name,
                'email_verified_at' => $user->email_verified_at ?? Carbon::now(),
                'active' => true,
            ]);
            
            $this->line("   ✅ Updated existing user: {$email}");
        } else {
            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(env('SEED_USER_PASSWORD') ?: \Illuminate\Support\Str::random(32)), // Default password
                'email_verified_at' => Carbon::now(),
                'active' => true,
            ]);
            
            $this->line("   ✅ Created new user: {$email}");
            $this->line("   Password: taken from SEED_USER_PASSWORD, or random - use the password reset page to set one.");
        }
        
        // Verify user attributes
        $this->line("   📧 Email verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
        $this->line("   🟢 Active status: " . ($user->active ? 'Yes' : 'No'));
        
        return $user;
    }

    private function assignSuperAdminRole(User $user, Role $role)
    {
        // Remove all existing roles and permissions
        $oldRoles = $user->roles->pluck('name')->toArray();
        $oldPermissions = $user->permissions->pluck('name')->toArray();
        
        if (!empty($oldRoles)) {
            $user->syncRoles([]);
            $this->line("   🧹 Removed existing roles: " . implode(', ', $oldRoles));
        }
        
        if (!empty($oldPermissions)) {
            $user->syncPermissions([]);
            $this->line("   🧹 Removed existing direct permissions: " . count($oldPermissions) . " permissions");
        }
        
        // Assign only super admin role
        $user->assignRole($role);
        $this->line("   ✅ Assigned super admin role to {$user->email}");
        
        // Verify permissions through role
        $rolePermissions = $user->getPermissionsViaRoles();
        $this->line("   🔑 User now has {$rolePermissions->count()} permissions via super admin role");
    }

    private function verifySetup(User $user, Role $role)
    {
        $issues = [];
        
        // Check email verification
        if (!$user->hasVerifiedEmail()) {
            $issues[] = "Email not verified";
        }
        
        // Check active status
        if (!$user->active) {
            $issues[] = "User not active";
        }
        
        // Check role assignment
        if (!$user->hasRole('super admin')) {
            $issues[] = "Super admin role not assigned";
        }
        
        // Check permissions count
        $totalPermissions = Permission::count();
        $userPermissions = $user->getAllPermissions()->count();
        if ($userPermissions !== $totalPermissions) {
            $issues[] = "Permission count mismatch: {$userPermissions}/{$totalPermissions}";
        }
        
        if (empty($issues)) {
            $this->line("   ✅ All checks passed!");
            $this->line("   👤 User: {$user->name} ({$user->email})");
            $this->line("   🎭 Role: super admin");
            $this->line("   🔑 Permissions: {$userPermissions}");
            $this->line("   📧 Email verified: Yes");
            $this->line("   🟢 Active: Yes");
        } else {
            $this->warn("   ⚠️ Issues found:");
            foreach ($issues as $issue) {
                $this->warn("      • {$issue}");
            }
        }
    }
}
