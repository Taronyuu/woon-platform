<?php

namespace App\Filament\Resources\ContactLists\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('created_at')
                    ->label('Subscribed At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $csv = "email,created_at\n";
                            foreach ($records as $record) {
                                $csv .= $record->email . ',' . $record->created_at->format('Y-m-d H:i:s') . "\n";
                            }
                            return response()->streamDownload(function () use ($csv) {
                                echo $csv;
                            }, 'contactlist-' . now()->format('Y-m-d') . '.csv');
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
