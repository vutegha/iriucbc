<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MarkExpiredJobOffersTest extends TestCase
{
    public function test_only_active_offers_past_their_deadline_are_marked_expired(): void
    {
        Schema::create('job_offers', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('status')->default('draft');
            $t->date('application_deadline')->nullable();
            $t->timestamps();
        });

        DB::table('job_offers')->insert([
            ['title' => 'hier', 'status' => 'active', 'application_deadline' => now()->subDay()->toDateString()],
            ['title' => 'aujourdhui', 'status' => 'active', 'application_deadline' => now()->toDateString()],
            ['title' => 'demain', 'status' => 'active', 'application_deadline' => now()->addDay()->toDateString()],
            ['title' => 'sans date', 'status' => 'active', 'application_deadline' => null],
            ['title' => 'brouillon ancien', 'status' => 'draft', 'application_deadline' => now()->subMonth()->toDateString()],
        ]);

        $this->artisan('jobs:mark-expired')->assertSuccessful();

        $status = DB::table('job_offers')->pluck('status', 'title');
        $this->assertSame('expired', $status['hier']);
        $this->assertSame('active', $status['aujourdhui'], 'An offer stays open on its deadline day');
        $this->assertSame('active', $status['demain']);
        $this->assertSame('active', $status['sans date']);
        $this->assertSame('draft', $status['brouillon ancien']);
    }

    public function test_the_command_is_scheduled_daily(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains((string) $event->command, 'jobs:mark-expired'));

        $this->assertNotNull($event);
        $this->assertSame('5 0 * * *', $event->expression);
    }
}
