<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EventDurationType: string implements HasLabel
{
    case LAPS = 'laps';
    case MINUTES = 'minutes';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LAPS => 'В кругах',
            self::MINUTES => 'В минутах',
        };
    }
}
