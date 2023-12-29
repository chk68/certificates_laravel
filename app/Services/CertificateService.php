<?php

namespace App\Services;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\File;


class CertificateService
{
    public function createCertificate(Request $request): string
    {
        $data = $request->except('image');
        $data['user_id'] = Auth::id();

        $data['email'] = $request->input('email');

        $certificate = Certificate::create($data);
        $img = imagecreatefromjpeg(public_path('images/certificate.jpg'));
        $textColor = imagecolorallocate($img, 0, 0, 0);

        $this->addTextToImage($img, $certificate, $textColor);
        $filename = 'certificate_' . time() . '.jpg';

        imagejpeg($img, public_path('certificates/' . $filename));
        imagedestroy($img);
        session(['generatedCertificate' => $filename]);

        $this->sendCertificateEmail($certificate);


        return $filename;
    }

    protected function addTextToImage($image, $certificate, $textColor): void
    {
        $nameCoords = ['x' => 1920, 'y' => 1350];
        $serviceCoords = ['x' => 920, 'y' => 2150];
        $costCoords = ['x' => 920, 'y' => 3100];

        $name = $certificate->name;
        $service = $certificate->service;
        $cost = $certificate->cost;

        imagettftext($image, 152, 0, $nameCoords['x'], $nameCoords['y'], $textColor, public_path('fonts/georgia.ttf'), $name);
        imagettftext($image, 152, 0, $serviceCoords['x'], $serviceCoords['y'], $textColor, public_path('fonts/georgia.ttf'), $service);
        imagettftext($image, 152, 0, $costCoords['x'], $costCoords['y'], $textColor, public_path('fonts/georgia.ttf'), $cost);
    }

    public function downloadGeneratedCertificate(): ?BinaryFileResponse
    {
        $filename = session('generatedCertificate');

        if (!$filename) {
            abort(404, 'Certificate not found');
        }

        $filePath = public_path('certificates/' . $filename);
        $downloadFilename = $filename;

        return response()->download($filePath, $downloadFilename, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    public function downloadGeneratedCertificatePDF(): ?BinaryFileResponse
    {
        $filename = session('generatedCertificate');

        if (!$filename) {
            abort(404, 'Certificate not found');
        }

        $imagePath = public_path('certificates/' . $filename);
        $pdfPath = public_path('certificates/' . $filename . '.pdf');
        $html = '<img src="' . $imagePath . '" style="max-width: 100%;">';

        PDF::loadHtml($html)->setPaper('a4', 'landscape')->save($pdfPath);

        return response()->download($pdfPath, $filename . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function createCertificateFromTemplate(Certificate $template, Certificate $newCertificate): string
    {
        $img = imagecreatefromjpeg(public_path('images/certificate.jpg'));
        $textColor = imagecolorallocate($img, 0, 0, 0);

        $this->addTextToImage($img, $newCertificate, $textColor);
        $filename = 'certificate_' . time() . '.jpg';

        imagejpeg($img, public_path('certificates/' . $filename));
        imagedestroy($img);
        session(['generatedCertificate' => $filename]);

        return $filename;
    }

    public function generateFromTemplate(int $templateId): string
    {
        $templateCertificate = Certificate::findOrFail($templateId);

        $existingCertificate = Certificate::where([
            'name' => $templateCertificate->name,
            'cost' => $templateCertificate->cost,
            'description' => $templateCertificate->description,
            'service' => $templateCertificate->service,
            'user_id' => Auth::id(),
        ])->first();

        if ($existingCertificate) {
            $generatedCertificate = $this->createCertificateFromTemplate($existingCertificate, $templateCertificate);
        } else {
            $data = [
                'name' => $templateCertificate->name,
                'cost' => $templateCertificate->cost,
                'description' => $templateCertificate->description,
                'service' => $templateCertificate->service,
                'user_id' => Auth::id(),
            ];
            $newCertificate = Certificate::create($data);
            $generatedCertificate = $this->createCertificateFromTemplate($newCertificate, $templateCertificate);
        }

        $this->cleanCertificatesFolder();

        return $generatedCertificate;
    }

    public function cleanCertificatesFolder(): void
    {
        $certificatesPath = public_path('certificates');

        if (!is_dir($certificatesPath)) {
            return;
        }

        $files = glob($certificatesPath . '/*');

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < strtotime('-3 hours')) {
                unlink($file);
            }
        }
    }





    public function sendCertificateEmail(Certificate $certificate): void
    {
        $data = [
            'certificate_link' => route('admin.certificates.confirmation.confirmation', ['id' => $certificate->id]),
        ];

        Mail::send('emails.certificate_confirmation', $data, function ($message) use ($certificate) {
            $message->to($certificate->email)->subject('Підтвердження сертифікату');
        });
    }


}

