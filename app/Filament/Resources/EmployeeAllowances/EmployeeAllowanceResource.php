<?php

namespace App\Filament\Resources\EmployeeAllowances;

use App\Filament\Resources\EmployeeAllowances\Pages\CreateEmployeeAllowance;
use App\Filament\Resources\EmployeeAllowances\Pages\EditEmployeeAllowance;
use App\Filament\Resources\EmployeeAllowances\Pages\ListEmployeeAllowances;
use App\Filament\Resources\EmployeeAllowances\Schemas\EmployeeAllowanceForm;
use App\Filament\Resources\EmployeeAllowances\Tables\EmployeeAllowancesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeAllowanceResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Employee Allowances';

    protected static ?string $modelLabel = 'Employee Allowance';

    protected static ?string $pluralModelLabel = 'Employee Allowances';

    protected static ?int $navigationSort = 4;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return EmployeeAllowanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeAllowancesTable::configure($table);
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
            'index' => ListEmployeeAllowances::route('/'),
            'create' => CreateEmployeeAllowance::route('/create'),
            'edit' => EditEmployeeAllowance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['allowances']);
    }
}
