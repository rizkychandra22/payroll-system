<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Filament\Resources\Payrolls\Schemas\PrintPayrollHistoryForm;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class PrintPayrollHistory extends Page
{
    protected static string $resource = PayrollResource::class;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeAccess();
        $this->form->fill();
    }

    public function hydrate(): void
    {
        $this->authorizeAccess();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canViewAny(), 403);
    }

    public function getBreadcrumb(): string
    {
        return 'Cetak Slip Gaji';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Cetak Slip Gaji';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Pilih bulan, tahun, dan jabatan yang ingin dicetak ke PDF.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return PrintPayrollHistoryForm::configure($schema);
    }

    public function print(): void
    {
        $this->authorizeAccess();

        $data = $this->form->getState();
        $url = route('payrolls.history.print.pdf', [
            'payroll_month' => (int) $data['payroll_month'],
            'payroll_year' => (int) $data['payroll_year'],
            'positions' => array_values($data['positions'] ?? []),
        ]);

        $this->redirect($url, navigate: false);
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('print')
                ->label('Print PDF')
                ->submit('print'),
            Action::make('cancel')
                ->label('Cancel')
                ->url($this->getResourceUrl())
                ->color('gray'),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('print')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->sticky($this->areFormActionsSticky())
                    ->key('form-actions'),
            ]);
    }
}
