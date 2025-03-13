<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use Filament\Forms\Components\TextInput;

class ISBNScanner extends TextInput
{
    protected string $view = 'components.isbn-scanner';

    public function setupScanner(): self
    {
        $this->extraAttributes(['id' => 'isbn-scanner']);
        return $this;
    }
}