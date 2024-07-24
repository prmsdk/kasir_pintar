<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReimbursementResource\Pages;
use App\Filament\Resources\ReimbursementResource\RelationManagers;
use App\Models\Reimbursement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReimbursementResource extends Resource
{
    protected static ?string $model = Reimbursement::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $recordTitleAttribute = 'judul';
    protected static ?string $navigationLabel = 'Reimburs';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi')
                    ->maxLength(255),
                Forms\Components\Textarea::make('file')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_accepted')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_accepted')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListReimbursements::route('/'),
            'create' => Pages\CreateReimbursement::route('/create'),
            'edit' => Pages\EditReimbursement::route('/{record}/edit'),
        ];
    }
}
