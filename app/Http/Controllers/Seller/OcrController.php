<?php
namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Routing\Controller;

class OcrController extends Controller
{
    public function index()
    {
        return view('ocr');
    }

    public function upload(Request $request)
    {
        $fileRead = '';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $base_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // Fix: Remove illegal characters before iconv
            $base_name_clean = preg_replace('/[\x00-\x1F\x7F]/u', '', $base_name);
            $ascii_name = @iconv('UTF-8', 'ASCII//TRANSLIT', $base_name_clean);
            if ($ascii_name === false) {
                $ascii_name = 'file';
            }
            $ascii_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', strtolower($ascii_name));
            $file_name = uniqid() . '_' . time() . '_' . $ascii_name . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $file_name, 'public');
            $upload_path = storage_path('app/public/' . $path);
            try {
                $tesseract = new TesseractOCR($upload_path);
                $tesseract->executable('C:\\Program Files\\Tesseract-OCR\\tesseract.exe');
                $tesseract->lang('vie');
                $tesseract->psm(3); // Page segmentation mode: fully automatic
                $tesseract->oem(1); // OCR Engine Mode: LSTM only
                $tesseract->config('preserve_interword_spaces', 1);
                $fileRead = $tesseract->run();
            } catch (\Exception $e) {
                $fileRead = $e->getMessage();
            }
        }
        return view('ocr', compact('fileRead'));
    }
}
