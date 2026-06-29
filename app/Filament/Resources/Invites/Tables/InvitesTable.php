<?php

namespace App\Filament\Resources\Invites\Tables;

use App\Models\Invite;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('created_at'),

                TextColumn::make('token')
                    ->label('Приглашение')
                    ->formatStateUsing(fn(Invite $record) => url('/invite/' . $record->token))
                    ->copyable()
                    ->copyMessage('Скопировано!')
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
