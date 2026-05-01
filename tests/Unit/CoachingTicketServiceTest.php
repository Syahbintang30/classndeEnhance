<?php

namespace Tests\Unit;

use App\Models\CoachingTicket;
use App\Models\Package;
use App\Models\Setting;
use App\Models\UserPackage;
use App\Models\User;
use App\Services\CoachingTicketService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CoachingTicketServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('coaching_tickets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('settings');

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('price')->default(125000);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->timestamps();
        });

        Schema::create('coaching_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_used')->default(false);
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->timestamp('purchased_at')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    public function test_beginner_package_gets_one_free_ticket(): void
    {
        $package = Package::forceCreate([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'price' => 75000,
        ]);

        $user = User::withoutEvents(function () use ($package) {
            return User::forceCreate([
                'name' => 'Beginner User',
                'email' => 'beginner+' . uniqid() . '@example.test',
                'password' => 'secret',
                'package_id' => $package->id,
            ]);
        });

        $created = CoachingTicketService::grantFreeOnRegister($user);

        $this->assertSame(1, $created);
        $this->assertDatabaseCount('coaching_tickets', 1);
        $this->assertDatabaseHas('coaching_tickets', [
            'user_id' => $user->id,
            'source' => 'free_on_register',
        ]);
    }

    public function test_intermediate_variants_get_two_free_tickets_and_top_up(): void
    {
        $beginner = Package::forceCreate([
            'name' => 'Beginner',
            'slug' => 'beginner',
            'price' => 75000,
        ]);

        $intermediate = Package::forceCreate([
            'name' => 'Upgrade Intermediate',
            'slug' => 'upgrade-intermediate',
            'price' => 50000,
        ]);

        $user = User::withoutEvents(function () use ($beginner) {
            return User::forceCreate([
                'name' => 'Upgrade User',
                'email' => 'upgrade+' . uniqid() . '@example.test',
                'password' => 'secret',
                'package_id' => $beginner->id,
            ]);
        });

        $firstGrant = CoachingTicketService::grantFreeOnRegister($user);

        $this->assertSame(1, $firstGrant);
        $this->assertSame(1, CoachingTicket::where('user_id', $user->id)->where('source', 'free_on_register')->count());

        $user->package_id = $intermediate->id;
        $user->save();

        $topUp = CoachingTicketService::grantFreeOnRegister($user);

        $this->assertSame(1, $topUp);
        $this->assertSame(2, CoachingTicket::where('user_id', $user->id)->where('source', 'free_on_register')->count());
    }

    public function test_configured_intermediate_package_id_gets_two_free_tickets(): void
    {
        $customIntermediate = Package::forceCreate([
            'name' => 'Custom Intermediate',
            'slug' => 'custom-intermediate',
            'price' => 130000,
        ]);

        Setting::set('intermediate_package_id', $customIntermediate->id);

        $user = User::withoutEvents(function () use ($customIntermediate) {
            return User::forceCreate([
                'name' => 'Configured Intermediate User',
                'email' => 'configured+' . uniqid() . '@example.test',
                'password' => 'secret',
                'package_id' => $customIntermediate->id,
            ]);
        });

        $created = CoachingTicketService::grantFreeOnRegister($user);

        $this->assertSame(2, $created);
        $this->assertSame(2, CoachingTicket::where('user_id', $user->id)->where('source', 'free_on_register')->count());
    }

    public function test_intermediate_purchase_history_without_current_package_id_gets_two_free_tickets(): void
    {
        $intermediate = Package::forceCreate([
            'name' => 'Intermediate',
            'slug' => 'intermediate',
            'price' => 125000,
        ]);

        $user = User::withoutEvents(function () {
            return User::forceCreate([
                'name' => 'History Intermediate User',
                'email' => 'history+' . uniqid() . '@example.test',
                'password' => 'secret',
                'package_id' => null,
            ]);
        });

        UserPackage::forceCreate([
            'user_id' => $user->id,
            'package_id' => $intermediate->id,
            'purchased_at' => now(),
            'source' => 'midtrans',
        ]);

        $created = CoachingTicketService::grantFreeOnRegister($user);

        $this->assertSame(2, $created);
        $this->assertSame(2, CoachingTicket::where('user_id', $user->id)->where('source', 'free_on_register')->count());
    }
}