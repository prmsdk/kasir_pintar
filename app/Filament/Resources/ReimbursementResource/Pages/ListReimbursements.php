<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use App\Filament\Resources\ReimbursementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReimbursements extends ListRecords
{
    protected static string $resource = ReimbursementResource::class;

    protected static ?string $title = 'Tabel Reimbursement';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus')->label('Tambah')->mutateFormDataUsing(function ($data) {
                if (auth()->user()->hasRole(['Staff'])) {
                    $data['user_id'] = auth()->user()->id;
                }

                return $data;
            }),
        ];
    }
}
