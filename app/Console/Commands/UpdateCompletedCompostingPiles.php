<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Composting;
use Carbon\Carbon;

class UpdateCompletedCompostingPiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composting:update-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update composting piles that have 45 or more trackings but no end_date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for completed composting piles...');
        
        // Buscar pilas que tienen 45 o más seguimientos pero no tienen end_date
        $completedPiles = Composting::whereNull('end_date')
            ->with('trackings')
            ->get()
            ->filter(function ($composting) {
                return $composting->trackings->count() >= 45;
            });

        if ($completedPiles->isEmpty()) {
            $this->info('No completed piles found.');
            return;
        }

        $this->info("Found {$completedPiles->count()} completed piles to update.");

        foreach ($completedPiles as $composting) {
            $trackingCount = $composting->trackings->count();
            $this->info("Updating pile {$composting->formatted_pile_num} with {$trackingCount} trackings...");
            
            $composting->update([
                'end_date' => now()->toDateString()
            ]);
            
            $this->info("✓ Pile {$composting->formatted_pile_num} marked as completed.");
        }

        $this->info('All completed piles have been updated successfully!');
    }
}