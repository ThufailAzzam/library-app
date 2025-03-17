<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class IsbnScannerField extends Field
{
    protected string $view = 'filament.forms.components.isbn-scanner-field';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(false); // This field doesn't need to be saved directly to the model
    }
}