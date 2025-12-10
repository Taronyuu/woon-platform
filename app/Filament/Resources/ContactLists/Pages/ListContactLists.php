<?php

namespace App\Filament\Resources\ContactLists\Pages;

use App\Filament\Resources\ContactLists\ContactListResource;
use Filament\Resources\Pages\ListRecords;

class ListContactLists extends ListRecords
{
    protected static string $resource = ContactListResource::class;
}
