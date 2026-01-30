<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/billing/pdf/{filename}', function (string $filename) {
    abort_unless(Filament::auth()->check(), 403);

    $path = storage_path('app/private/session/' . $filename);

    abort_unless(file_exists($path), 404);

    return Response::file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline, filename="' . $filename .'"',
    ]);
})
->name('billing.pdf.show')
->middleware(['web', 'auth']);