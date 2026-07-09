<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')->label('Nama Lengkap')
                    ->required(),
                TextInput::make('nik')->label('NIK')
                    ->required(),
                TextInput::make('position')->label('Jabatan')
                    ->required(),
                TextInput::make('basic_salary')->label('Gaji Pokok')
                    ->required()
                    ->numeric(),
                DatePicker::make('join_date')->label('Tanggal Bergabung')
                    ->required(),
            ]);
    }
}
