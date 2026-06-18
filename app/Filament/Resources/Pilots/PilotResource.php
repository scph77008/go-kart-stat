<?php

namespace App\Filament\Resources\Pilots;

use App\Filament\Resources\Pilots\Pages\CreatePilot;
use App\Filament\Resources\Pilots\Pages\EditPilot;
use App\Filament\Resources\Pilots\Pages\ListPilots;
use App\Filament\Resources\Pilots\Schemas\PilotForm;
use App\Filament\Resources\Pilots\Tables\PilotsTable;
use App\Models\Pilot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PilotResource extends Resource
{
    protected static ?string $model = Pilot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Name';

    public static function form(Schema $schema): Schema
    {
        return PilotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PilotsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPilots::route('/'),
            'create' => CreatePilot::route('/create'),
            'edit' => EditPilot::route('/{record}/edit'),
        ];
    }
}
