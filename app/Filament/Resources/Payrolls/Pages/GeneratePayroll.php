<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Filament\Resources\Payrolls\Schemas\GeneratePayrollForm;
use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Services\PayrollGenerator;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Throwable;

class GeneratePayroll extends Page
{
    use CanUseDatabaseTransactions;

    protected static string $resource = PayrollResource::class;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?string $previousUrl = null;

    public function mount(): void
    {
        $this->authorizeAccess();
        $this->form->fill();
        $this->previousUrl = url()->previous();
    }

    public function hydrate(): void
    {
        $this->authorizeAccess();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canCreate(), 403);
    }

    public function getBreadcrumb(): string
    {
        return 'Generate Payroll';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Generate Payroll';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Generate payroll massal berdasarkan position untuk bulan dan tahun tertentu.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->statePath('data')
            ->operation('create');
    }

    public function form(Schema $schema): Schema
    {
        return GeneratePayrollForm::configure($schema);
    }

    public function generate(): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();
            $result = app(PayrollGenerator::class)->generateForPositions(
                $data['payroll_month'],
                $data['payroll_year'],
                $data['positions'],
            );
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $monthLabel = PayrollForm::getMonthOptions()[(int) $data['payroll_month']] ?? (string) $data['payroll_month'];
        $yearLabel = (string) (int) $data['payroll_year'];

        $notification = Notification::make()
            ->title("Payroll {$monthLabel} {$yearLabel} selesai diproses.")
            ->body("Berhasil dibuat {$result['created_count']} payroll, dilewati {$result['skipped_count']} payroll yang sudah ada sebelumnya.");

        if ($result['created_count'] > 0) {
            $notification->success();
        } else {
            $notification->warning();
        }

        $notification->send();

        $redirectUrl = $this->getResourceUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode($redirectUrl));
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Payroll')
                ->submit('generate'),
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
            ->livewireSubmitHandler('generate')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->sticky($this->areFormActionsSticky())
                    ->key('form-actions'),
            ]);
    }
}
