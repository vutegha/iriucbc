<?php

namespace Tests\Feature;

use App\Models\JobOffer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * An offer stays open for the whole of its deadline day and expires the day after.
 */
class JobOfferDeadlineTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Late in the day: the old comparison with the current time failed here.
        Carbon::setTestNow('2026-03-10 23:30:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function offer(?string $deadline): JobOffer
    {
        return (new JobOffer())->forceFill(['status' => 'active', 'application_deadline' => $deadline]);
    }

    public function test_is_expired_and_days_remaining(): void
    {
        $today = $this->offer('2026-03-10');
        $this->assertFalse($today->is_expired);
        $this->assertSame(0, $today->days_until_deadline);

        $tomorrow = $this->offer('2026-03-11');
        $this->assertFalse($tomorrow->is_expired);
        $this->assertSame(1, $tomorrow->days_until_deadline);

        $yesterday = $this->offer('2026-03-09');
        $this->assertTrue($yesterday->is_expired);
        $this->assertSame(-1, $yesterday->days_until_deadline);

        $none = $this->offer(null);
        $this->assertFalse((bool) $none->is_expired);
        $this->assertNull($none->days_until_deadline);
    }

    public function test_active_and_expired_scopes(): void
    {
        Schema::create('job_offers', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('status')->default('draft');
            $t->date('application_deadline')->nullable();
            $t->timestamps();
        });

        DB::table('job_offers')->insert([
            ['title' => 'hier', 'status' => 'active', 'application_deadline' => '2026-03-09'],
            ['title' => 'aujourdhui', 'status' => 'active', 'application_deadline' => '2026-03-10'],
            ['title' => 'demain', 'status' => 'active', 'application_deadline' => '2026-03-11'],
            ['title' => 'sans date', 'status' => 'active', 'application_deadline' => null],
            ['title' => 'brouillon', 'status' => 'draft', 'application_deadline' => '2026-03-11'],
        ]);

        $this->assertEqualsCanonicalizing(
            ['aujourdhui', 'demain', 'sans date'],
            JobOffer::active()->pluck('title')->all()
        );
        $this->assertSame(['hier'], JobOffer::expired()->pluck('title')->all());
    }
}
