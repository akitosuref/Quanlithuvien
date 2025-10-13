<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Custom Data Type: Address
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        // User (Librarian, Member)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->enum('role', ['librarian', 'member']);
            $table->enum('account_status', ['ACTIVE', 'CLOSED', 'CANCELED', 'BLACKLISTED'])->default('ACTIVE');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Library Card (R6)
        Schema::create('library_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_number')->unique();
            $table->date('issued_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Rack (R2)
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->string('rack_number')->unique();
            $table->string('location_identifier');
            $table->timestamps();
        });

        // Book (R3)
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 20)->unique();
            $table->string('title');
            $table->string('subject');
            $table->date('publication_date');
            $table->timestamps();
        });

        // Book Item (R4, R2)
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('rack_id')->nullable()->constrained('racks')->onDelete('set null');
            $table->string('barcode')->unique();
            $table->enum('format', ['HARDCOVER', 'PAPERBACK', 'AUDIOBOOK', 'EBOOK', 'NEWSPAPER', 'MAGAZINE', 'JOURNAL'])->default('HARDCOVER');
            $table->enum('status', ['AVAILABLE', 'RESERVED', 'LOANED', 'LOST'])->default('AVAILABLE');
            $table->timestamps();
        });

        // Book Lending (R10, R8)
        Schema::create('book_lendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_item_id')->constrained('book_items')->onDelete('cascade');
            $table->date('borrowed_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->timestamps();
        });

        // Book Reservation (R10, R9, R13)
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_item_id')->constrained('book_items')->onDelete('cascade');
            $table->date('reservation_date');
            $table->enum('status', ['WAITING', 'PROCESSING', 'CANCELED'])->default('WAITING');
            $table->timestamps();
        });

        // Notification (R12)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lending_id')->nullable()->constrained('book_lendings')->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->enum('type', ['EMAIL', 'POSTAL']);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('book_reservations');
        Schema::dropIfExists('book_lendings');
        Schema::dropIfExists('book_items');
        Schema::dropIfExists('books');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('library_cards');
        Schema::dropIfExists('users');
        Schema::dropIfExists('addresses');
    }
};