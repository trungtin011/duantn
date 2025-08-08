<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\User;
use App\Enums\UserRole;

class TestRoleUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:role-update {shop_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role update functionality for a specific shop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shopId = $this->argument('shop_id');
        
        $this->info("Testing role update for shop ID: {$shopId}");
        
        // Find the shop
        $shop = Shop::find($shopId);
        if (!$shop) {
            $this->error("Shop with ID {$shopId} not found!");
            return 1;
        }
        
        $this->info("Shop found: {$shop->shop_name}");
        $this->info("Owner ID: {$shop->ownerID}");
        
        // Find the user
        $user = User::find($shop->ownerID);
        if (!$user) {
            $this->error("User with ID {$shop->ownerID} not found!");
            return 1;
        }
        
        $this->info("User found: {$user->fullname}");
        $this->info("Current role: {$user->role->value}");
        $this->info("Role type: " . get_class($user->role));
        
        // Test role comparison
        $this->info("UserRole::CUSTOMER value: " . UserRole::CUSTOMER->value);
        $this->info("UserRole::SELLER value: " . UserRole::SELLER->value);
        
        $isCustomer = $user->role->value === UserRole::CUSTOMER->value;
        $isSeller = $user->role->value === UserRole::SELLER->value;
        
        $this->info("Is customer: " . ($isCustomer ? 'Yes' : 'No'));
        $this->info("Is seller: " . ($isSeller ? 'Yes' : 'No'));
        
        // Test role update
        if ($isCustomer) {
            $this->info("Updating role from customer to seller...");
            $user->update(['role' => UserRole::SELLER]);
            $this->info("Role updated successfully!");
            $this->info("New role: " . $user->fresh()->role->value);
        } elseif ($isSeller) {
            $this->info("Updating role from seller to customer...");
            $user->update(['role' => UserRole::CUSTOMER]);
            $this->info("Role updated successfully!");
            $this->info("New role: " . $user->fresh()->role->value);
        } else {
            $this->warn("User role is neither customer nor seller: {$user->role->value}");
        }
        
        // Test shop deletion scenario
        $this->info("\n=== Testing Shop Deletion Scenario ===");
        if ($isSeller) {
            $this->info("Simulating shop deletion - user role should change from seller to customer");
            $user->update(['role' => UserRole::CUSTOMER]);
            $this->info("Role updated to customer after shop deletion simulation");
        } else {
            $this->info("User is not a seller, no role change needed for shop deletion");
        }
        
        return 0;
    }
}
