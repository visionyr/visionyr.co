<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedFirstAdmin();

        if (Member::count() === 0) {
            Member::factory()->count(12)->create();
        }
    }

    /**
     * Create the first CMS account.
     *
     * No password is hard-coded here: this repository is public, so a default
     * committed to it would be a default everybody knows. Set SEED_ADMIN_PASSWORD
     * to choose one, otherwise a random password is generated and printed once.
     */
    protected function seedFirstAdmin(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@visionyr.com');
        $chosen = env('SEED_ADMIN_PASSWORD');
        $password = $chosen ?: Str::password(16);

        // firstOrCreate, not updateOrCreate: re-seeding must never reset the
        // password of an admin who is already using the CMS.
        $admin = AdminUser::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Visionyr Admin',
                'password' => $password,
                'is_active' => true,
            ],
        );

        if (! $admin->wasRecentlyCreated) {
            return;
        }

        if ($chosen) {
            $this->command?->info("Created admin {$email} with the password from SEED_ADMIN_PASSWORD.");

            return;
        }

        $this->command?->warn("Created admin {$email}");
        $this->command?->warn("Password: {$password}");
        $this->command?->warn('Save it now — it will not be shown again.');
    }
}
