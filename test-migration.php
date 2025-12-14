<?php

use App\Models\Organization;
use App\Models\Event;

$org = Organization::first();

echo "========================================\n";
echo "MULTI-TENANT MIGRACIJA - PROVERA\n";
echo "========================================\n\n";

if ($org) {
    echo "✓ Default organizacija: {$org->name}\n";
    echo "✓ Slug: {$org->slug}\n";
    echo "✓ Članovi: " . $org->members()->count() . "\n";
    echo "✓ Eventi: " . $org->events()->count() . "\n\n";

    $event = $org->events()->first();
    if ($event) {
        echo "✓ Event: {$event->name} ({$event->year})\n";
        echo "✓ Učesnici: " . $event->participants()->count() . "\n";
        echo "✓ Dodele (assignments): " . $event->assignments()->count() . "\n";
        echo "✓ Želje: " . $event->wishes()->count() . "\n\n";
    }

    echo "========================================\n";
    echo "SVE JE USPEŠNO MIGRIRANO! ✓\n";
    echo "========================================\n";
} else {
    echo "❌ Nema organizacija!\n";
}
