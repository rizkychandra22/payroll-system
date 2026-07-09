<?php

namespace App\Filament\Resources\EmployeeDeductions;

use App\Filament\Resources\EmployeeDeductions\Pages\CreateEmployeeDeduction;
use App\Filament\Resources\EmployeeDeductions\Pages\EditEmployeeDeduction;
use App\Filament\Resources\EmployeeDeductions\Pages\ListEmployeeDeductions;
use App\Filament\Resources\EmployeeDeductions\Schemas\EmployeeDeductionForm;
use App\Filament\Resources\EmployeeDeductions\Tables\EmployeeDeductionsTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeDeductionResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Employee Deductions';

    protected static ?string $modelLabel = 'Employee Deduction';

    protected static ?string $pluralModelLabel = 'Employee Deductions';

    protected static ?int $navigationSort = 5;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedMinusCircle;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return EmployeeDeductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeDeductionsTable::configure($table);
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
            'index' => ListEmployeeDeductions::route('/'),
            'create' => CreateEmployeeDeduction::route('/create'),
            'edit' => EditEmployeeDeduction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['deductions']);
    }
}
