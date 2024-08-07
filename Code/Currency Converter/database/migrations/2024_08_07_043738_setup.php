<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Setup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $tablesCreated = [];

            // Create the 'users' table if it does not exist
            if (!Schema::hasTable('users')) {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('username')->unique();
                    $table->string('password');
                    $table->rememberToken(); // Adds the remember_token column
                    $table->string('plain_password');
                    $table->json('roles');
                });
                $tablesCreated[] = 'users';

                // Insert initial data into the 'users' table if no rows exist
                if (DB::table('users')->count() === 0) {
                    DB::table('users')->insert([
                        ['username' => 'admin', 'password' => Hash::make('admin'), 'plain_password' => 'admin', 'roles' => json_encode(['ROLE_ADMIN'])],
                        ['username' => 'user', 'password' => Hash::make('user'), 'plain_password' => 'user', 'roles' => json_encode(['ROLE_USER'])],
                        ['username' => 'Sam', 'password' => Hash::make('Sam'), 'plain_password' => 'Sam', 'roles' => json_encode(['ROLE_ADMIN'])],
                        ['username' => 'System', 'password' => Hash::make('System'), 'plain_password' => 'System', 'roles' => json_encode(['ROLE_USER'])],
                        ['username' => 'Ram', 'password' => Hash::make('Ram'), 'plain_password' => 'Ram', 'roles' => json_encode(['ROLE_USER'])],
                    ]);
                }
            }

            // Create the 'authorized_ip' table if it does not exist
            if (!Schema::hasTable('authorized_ip')) {
                Schema::create('authorized_ip', function (Blueprint $table) {
                    $table->id();
                    $table->string('ip_address');
                });
                $tablesCreated[] = 'authorized_ip';

                // Insert initial data into the 'authorized_ip' table if no rows exist
                if (DB::table('authorized_ip')->count() === 0) {
                    DB::table('authorized_ip')->insert([
                        ['ip_address' => '192.168.1.1'],
                        ['ip_address' => '10.0.0.1'],
                        ['ip_address' => '172.16.0.1'],
                        ['ip_address' => '192.168.0.2'],
                        ['ip_address' => '10.1.1.1'],
                    ]);
                }
            }

            // Output success message
            if (count($tablesCreated) > 0) {
                $tables = implode(', ', $tablesCreated);
                echo "Tables created and initial data inserted successfully: $tables.\n";
                Log::info("Migration 'Setup' completed successfully. Tables created: $tables.");
            } else {
                echo "No tables were created or data inserted; they may already exist.\n";
                Log::info("Migration 'Setup' completed. No new tables or data inserted.");
            }

        } catch (\Exception $e) {
            // Output error message and log the error
            $errorMessage = "Migration 'Setup' failed: " . $e->getMessage();
            echo $errorMessage . "\n";
            Log::error($errorMessage);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            $tablesDropped = [];

            // Drop the 'users' table if it exists
            if (Schema::hasTable('users')) {
                Schema::dropIfExists('users');
                $tablesDropped[] = 'users';
            }

            // Drop the 'authorized_ip' table if it exists
            if (Schema::hasTable('authorized_ip')) {
                Schema::dropIfExists('authorized_ip');
                $tablesDropped[] = 'authorized_ip';
            }

            // Drop the 'personal_access_tokens' table if it exists
            if (Schema::hasTable('personal_access_tokens')) {
                Schema::dropIfExists('personal_access_tokens');
                $tablesDropped[] = 'personal_access_tokens';
            }

            // Output success message
            if (count($tablesDropped) > 0) {
                $tables = implode(', ', $tablesDropped);
                echo "Tables dropped successfully: $tables.\n";
                Log::info("Migration 'Setup' rollback completed successfully. Tables dropped: $tables.");
            } else {
                echo "No tables were dropped; they may not exist.\n";
                Log::info("Migration 'Setup' rollback completed. No tables were dropped.");
            }

        } catch (\Exception $e) {
            // Output error message and log the error
            $errorMessage = "Migration 'Setup' rollback failed: " . $e->getMessage();
            echo $errorMessage . "\n";
            Log::error($errorMessage);
        }
    }
}
