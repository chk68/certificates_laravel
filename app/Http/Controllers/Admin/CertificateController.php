<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(): View
    {
        $certificates = Certificate::all();
        return view('admin.certificates.show.index', compact('certificates'));
    }

    public function show($id): View
    {
        $certificate = Certificate::findOrFail($id);
        return view('admin.certificates.show.show', compact('certificate'));
    }

    public function create(): View
    {
        return view('admin.certificates.create.create');
    }

    public function store(Request $request): View
    {
        $generatedCertificate = $this->certificateService->createCertificate($request);
        $this->certificateService->cleanCertificatesFolder();
        return view('admin.certificates.create.generated', compact('generatedCertificate'));
    }

    public function edit(int $id): View
    {
        $certificate = Certificate::findOrFail($id);
        return view('admin.certificates.edit.edit', compact('certificate'));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->update($request->all());
        return redirect()->route('admin.certificates.show.show', $id)->with('success', 'Certificate updated successfully');
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->with('success', 'Certificate deleted successfully');
    }

    public function generateFromTemplate(int $templateId): View
    {
        $generatedCertificate = $this->certificateService->generateFromTemplate($templateId);

        return view('admin.certificates.create.generated', compact('generatedCertificate'));
    }

    public function showGeneratedCertificate(): View
    {
        $generatedCertificate = session('generatedCertificate');
        return view('admin.certificates.create.generated', compact('generatedCertificate'));
    }

    public function downloadCertificate(): ?BinaryFileResponse
    {
        $this->certificateService->cleanCertificatesFolder();
        return $this->certificateService->downloadGeneratedCertificate();
    }

    public function downloadGeneratedCertificatePDF(): BinaryFileResponse
    {
        return $this->certificateService->downloadGeneratedCertificatePDF();
    }




    public function confirmCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->update(['active' => false]);

        return view('admin.certificates.confirmation.confirmation', compact('certificate'));
    }

}
