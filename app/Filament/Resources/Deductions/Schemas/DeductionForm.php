<?php

namespace App\Filament\Resources\Deductions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nama')
                    ->required(),
                TextInput::make('amount')->label('Jumlah')
                    ->required()
                    ->numeric(),
                Textarea::make('description')->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }
}
