<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add the GEOMETRY column for spatial data (NOT NULL is required for SPATIAL index)
        DB::statement('ALTER TABLE addresses ADD COLUMN location GEOMETRY NOT NULL AFTER longitude');
        // Update existing records to populate the location column using POINT geometry
        // For NULL coordinates, set a default point (0,0) or you can handle differently
        DB::statement('
            UPDATE addresses 
            SET location = ST_GeomFromText(
                CONCAT("POINT(", 
                    COALESCE(longitude, 0), 
                    " ", 
                    COALESCE(latitude, 0), 
                ")"), 
                4326
            )
        ');
        // Add spatial index on the location column
        DB::statement('ALTER TABLE addresses ADD SPATIAL INDEX spatial_location(location)');
        
        // Create trigger for INSERT operations
        DB::unprepared('
            CREATE TRIGGER addresses_location_insert 
            BEFORE INSERT ON addresses
            FOR EACH ROW
            BEGIN
                IF NEW.latitude IS NOT NULL AND NEW.longitude IS NOT NULL THEN
                    SET NEW.location = ST_GeomFromText(
                        CONCAT("POINT(", NEW.longitude, " ", NEW.latitude, ")"), 
                        4326
                    );
                END IF;
            END
        ');
        // Create trigger for UPDATE operations
        DB::unprepared('
            CREATE TRIGGER addresses_location_update 
            BEFORE UPDATE ON addresses
            FOR EACH ROW
            BEGIN
                IF NEW.latitude IS NOT NULL AND NEW.longitude IS NOT NULL THEN
                    SET NEW.location = ST_GeomFromText(
                        CONCAT("POINT(", NEW.longitude, " ", NEW.latitude, ")"), 
                        4326
                    );
                ELSEIF NEW.latitude IS NULL OR NEW.longitude IS NULL THEN
                    SET NEW.location = NULL;
                END IF;
            END
        ');
        // Schema::table('addresses', function (Blueprint $table) {
        //     //
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS addresses_location_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS addresses_location_update');
        
        // Drop spatial index
        DB::statement('ALTER TABLE addresses DROP INDEX spatial_location');
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
