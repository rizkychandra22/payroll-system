<?php

namespace App\Filament\Resources\Payrolls;

use App\Filament\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Resources\Payrolls\Pages\GeneratePayroll;
use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Resources\Payrolls\Tables\PayrollsTable;
use App\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Payroll';

    protected static ?string $navigationLabel = 'Payroll History';

    protected static ?string $modelLabel = 'Payroll';

    protected static ?string $pluralModelLabel = 'Payroll History';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'employee_id';

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
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
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'generate' => GeneratePayroll::route('/generate'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }
}
