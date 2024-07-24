<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReimbursementResource\Pages;
use App\Filament\Resources\ReimbursementResource\RelationManagers;
use App\Models\Reimbursement;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
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
    protected static ?string $breadcrumb = 'Reimburs';
    protected static ?string $navigationLabel = 'Reimburs';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        if ($user->hasRole(['Staff'])) {
            return static::getModel()::where('user_id', auth()->user()->id)->count();
        } else {
            return static::getModel()::count();
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pilih User')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->hidden(fn () => auth()->user()->hasRole(['Staff']) ? true : false),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->visible(fn () => auth()->user()->hasRole(['Staff']) ? false : true),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn () => auth()->user()->hasRole(['Staff']) ? true : false),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(255)
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('file')
                    ->downloadable()
                    ->openable()
                    ->rules(['mimes:png,jpg,jpeg,webp,pdf'])
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'])
                    ->required(),
                auth()->user()->hasRole(['Direktur']) || auth()->user()->hasRole(['Finance']) ? Forms\Components\Toggle::make('is_accepted')
                    ->label('ACC Direktur')
                    ->required()
                    ->disabled(fn ($state) => $state == 0)
                    ->columnSpanFull() :
                    Forms\Components\Hidden::make('is_accepted'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = auth()->user();
                if ($user->hasRole(['Staff'])) {
                    $query->where('user_id', auth()->user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->hidden(fn () => auth()->user()->hasRole(['Staff']) ? true : false),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                auth()->user()->hasRole(['Direktur']) ?
                    Tables\Columns\ToggleColumn::make('is_accepted')->label('ACC Direktur')
                    :
                    Tables\Columns\IconColumn::make('is_accepted')->label('ACC Direktur')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function ($data) {
                    if (auth()->user()->hasRole(['Staff'])) {
                        $data['user_id'] = auth()->user()->id;
                    }

                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
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
            // 'create' => Pages\CreateReimbursement::route('/create'),
            // 'edit' => Pages\EditReimbursement::route('/{record}/edit'),
        ];
    }
}
