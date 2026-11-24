<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use DateTimeInterface;
use Filament\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Options as XlsxOptions;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TableExport
{
    public static function excelAction(string $name = 'export_excel', ?string $label = null): Action
    {
        return Action::make($name)
            ->label($label ?? 'Export Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(fn (Action $action): BinaryFileResponse => static::excelResponse($action->getLivewire()));
    }

    public static function pdfAction(string $name = 'export_pdf', ?string $label = null): Action
    {
        return Action::make($name)
            ->label($label ?? 'Export PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(fn (Action $action) => static::pdfResponse($action->getLivewire()));
    }

    public static function excelResponse(HasTable $livewire): BinaryFileResponse
    {
        $columns = static::exportColumns($livewire->getTable());
        $path = tempnam(sys_get_temp_dir(), 'qac-xlsx-');

        $writer = new XlsxWriter(new XlsxOptions());
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues(array_column($columns, 'label')));

        $livewire->getTableQueryForExport()
            ->latest()
            ->limit(5000)
            ->lazy()
            ->each(function (Model $record) use ($writer, $columns): void {
                $writer->addRow(Row::fromValues(array_map(
                    fn (array $column): string => static::cellValue($record, $column['name']),
                    $columns,
                )));
            });

        $writer->close();

        return response()
            ->download($path, static::filename($livewire, 'xlsx'), [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
            ->deleteFileAfterSend(true);
    }

    public static function pdfResponse(HasTable $livewire)
    {
        $columns = static::exportColumns($livewire->getTable());
        $rows = $livewire->getTableQueryForExport()
            ->latest()
            ->limit(2000)
            ->get()
            ->map(fn (Model $record): array => array_map(
                fn (array $column): string => static::cellValue($record, $column['name']),
                $columns,
            ));

        $pdf = Pdf::loadView('exports.table', [
            'title' => $livewire->getTablePluralModelLabel(),
            'exportedAt' => now()->format('Y-m-d H:i'),
            'columns' => $columns,
            'rows' => $rows,
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->download(static::filename($livewire, 'pdf'));
    }

    /**
     * @return array<int, array{name: string, label: string}>
     */
    protected static function exportColumns(Table $table): array
    {
        return collect($table->getVisibleColumns())
            ->map(fn ($column): array => [
                'name' => $column->getName(),
                'label' => $column->getLabel() ?: $column->getName(),
            ])
            ->values()
            ->all();
    }

    protected static function cellValue(Model $record, string $name): string
    {
        $value = data_get($record, $name);

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        if ($value === null) {
            return '';
        }

        return (string) $value;
    }

    protected static function filename(HasTable $livewire, string $ext): string
    {
        return str($livewire->getTablePluralModelLabel())
            ->lower()
            ->kebab()
            ->prepend('')
            ->append('-')
            ->append(now()->format('Ymd-His'))
            ->append('.')
            ->append($ext)
            ->toString();
    }
}