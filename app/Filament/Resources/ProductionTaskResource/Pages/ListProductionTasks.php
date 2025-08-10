<?php

namespace App\Filament\Resources\ProductionTaskResource\Pages;

use App\Filament\Resources\ProductionTaskResource;
use Filament\Resources\Pages\ListRecords;

class ListProductionTasks extends ListRecords
{
    protected static string $resource = ProductionTaskResource::class;
}
