<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns registration_groups into a record of the transaction, not just the maths:
 * who booked it, how they paid, and what was actually received.
 *
 * Individual registrations are unaffected — they keep their per-person payment
 * proof and never populate the organizer or group payment columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_groups', function (Blueprint $table) {
            // Who made the booking. May not be racing at all (a club coordinator).
            $table->string('organizer_name')->nullable()->after('leader_email');
            $table->string('organizer_email')->nullable()->after('organizer_name');
            $table->string('organizer_mobile')->nullable()->after('organizer_email');
            $table->string('organizer_team')->nullable()->after('organizer_mobile');

            // How it was paid, promoted off the duplicated per-member proof rows.
            $table->string('payment_method')->nullable()->after('total_due');

            // pending | verified | rejected — the state of the single group transfer.
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->decimal('amount_received', 10, 2)->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('amount_received');
            $table->timestamp('verified_at')->nullable()->after('payment_reference');
            // users.id is a bigint auto-increment, unlike the UUID keys used elsewhere.
            $table->foreignId('verified_by')->nullable()->after('verified_at')
                ->constrained('users')->nullOnDelete();

            $table->text('admin_notes')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('registration_groups', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'organizer_name', 'organizer_email', 'organizer_mobile', 'organizer_team',
                'payment_method', 'payment_status', 'amount_received', 'payment_reference',
                'verified_at', 'verified_by', 'admin_notes',
            ]);
        });
    }
};
