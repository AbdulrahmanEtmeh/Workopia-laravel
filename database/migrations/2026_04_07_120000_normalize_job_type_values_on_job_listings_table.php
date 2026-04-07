<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    DB::statement('ALTER TABLE job_listings DROP CONSTRAINT IF EXISTS job_listings_job_type_check');

    DB::statement(<<<'SQL'
            UPDATE job_listings
            SET job_type = CASE job_type
                WHEN 'Full-time' THEN 'full_time'
                WHEN 'Full-Time' THEN 'full_time'
                WHEN 'Part-time' THEN 'part_time'
                WHEN 'Part-Time' THEN 'part_time'
                WHEN 'Contract' THEN 'contract'
                WHEN 'Temporary' THEN 'temporary'
                WHEN 'Internship' THEN 'internship'
                WHEN 'Volunteer' THEN 'volunteer'
                WHEN 'On-call' THEN 'on_call'
                WHEN 'On-Call' THEN 'on_call'
                ELSE job_type
            END
        SQL);

    DB::statement(<<<'SQL'
            ALTER TABLE job_listings
            ADD CONSTRAINT job_listings_job_type_check
            CHECK (job_type IN (
                'full_time',
                'part_time',
                'contract',
                'temporary',
                'internship',
                'volunteer',
                'on_call'
            ))
        SQL);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::statement('ALTER TABLE job_listings DROP CONSTRAINT IF EXISTS job_listings_job_type_check');

    DB::statement(<<<'SQL'
            UPDATE job_listings
            SET job_type = CASE job_type
                WHEN 'full_time' THEN 'Full-time'
                WHEN 'part_time' THEN 'Part-time'
                WHEN 'contract' THEN 'Contract'
                WHEN 'temporary' THEN 'Temporary'
                WHEN 'internship' THEN 'Internship'
                WHEN 'volunteer' THEN 'Volunteer'
                WHEN 'on_call' THEN 'On-call'
                ELSE job_type
            END
        SQL);

    DB::statement(<<<'SQL'
            ALTER TABLE job_listings
            ADD CONSTRAINT job_listings_job_type_check
            CHECK (job_type IN (
                'Full-time',
                'Part-time',
                'Contract',
                'Temporary',
                'Internship',
                'Volunteer',
                'On-call'
            ))
        SQL);
  }
};
