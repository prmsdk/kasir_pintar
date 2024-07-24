<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use App\Filament\Resources\ReimbursementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReimbursement extends EditRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
