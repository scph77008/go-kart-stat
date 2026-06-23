<?php
namespace App\Filament\Resources\Events;

use Filament\Support\Contracts\HasLabel;

enum EventType: string implements HasLabel
{
    case TEAM = 'team';
    case INDIVIDUAL = 'individual';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TEAM => 'Team',
            self::INDIVIDUAL => 'Individual',
        };
    }
}
