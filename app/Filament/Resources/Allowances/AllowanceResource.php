<?php

namespace App\Filament\Resources\Allowances;

use App\Filament\Resources\Allowances\Pages\CreateAllowance;
use App\Filament\Resources\Allowances\Pages\EditAllowance;
use App\Filament\Resources\Allowances\Pages\ListAllowances;
use App\Filament\Resources\Allowances\Schemas\AllowanceForm;
use App\Filament\Resources\Allowances\Tables\AllowancesTable;
use App\Models\Allowance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AllowanceResource extends Resource
{
    protected static ?string $model = Allowance::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Allowances';

    protected static ?string $modelLabel = 'Allowance';

    protected static ?string $pluralModelLabel = 'Allowances';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AllowanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllowancesTable::configure($table);
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
            'index' => ListAllowances::route('/'),
            'create' => CreateAllowance::route('/create'),
            'edit' => EditAllowance::route('/{record}/edit'),
        ];
    }
}