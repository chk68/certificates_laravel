<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [IndexController::class, 'index'])->name('admin.certificates.index');

    Route::get('/admin/certificates', [CertificateController::class, 'index'])->name('admin.certificates.index');
    Route::get('/admin/certificates/show/{id}', [CertificateController::class, 'show'])->name('admin.certificates.show.show');
    Route::get('/admin/certificates/create', [CertificateController::class, 'create'])->name('admin.certificates.create');
    Route::get('/admin/certificates/edit/{id}', [CertificateController::class, 'edit'])->name('admin.certificates.edit.edit');
    Route::put('/admin/certificates/update/{id}', [CertificateController::class, 'update'])->name('admin.certificates.update');
    Route::delete('/admin/certificates/{id}', [CertificateController::class, 'destroy'])->name('admin.certificates.destroy');
    Route::post('/admin/certificates', [CertificateController::class, 'store'])->name('admin.certificates.store');

    Route::get('/admin/certificates/download', [CertificateController::class, 'downloadCertificate'])->name('admin.certificates.download');
    Route::get('/admin/certificates/generated', [CertificateController::class, 'showGeneratedCertificate'])->name('admin.certificates.generated');
    Route::post('/admin/certificates/download/pdf', [CertificateController::class, 'downloadGeneratedCertificatePDF'])->name('admin.certificates.download.pdf');
    Route::get('/admin/certificates/createFromTemplate/{id}', [CertificateController::class, 'generateFromTemplate'])->name('admin.certificates.createFromTemplate');

});

Route::get('/certificate/confirmation/{id}', [CertificateController::class, 'confirmCertificate'])->name('admin.certificates.confirmation.confirmation');



