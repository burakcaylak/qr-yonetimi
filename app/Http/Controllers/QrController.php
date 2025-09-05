<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;


class QrController extends Controller
{
    // İlk kez QR üret ve sabit kodu ata (bir daha değişmez)
    public function generate(Product $product)
    {
        if (!$product->qr_code) {
            $product->qr_code = Str::ulid()->toBase32(); // kısa ve benzersiz
            $product->save();
        }
        return back()->with('success','QR hazır.');
    }

    public function toggle(Product $product)
    {
        $product->qr_active = !$product->qr_active;
        $product->save();
        return back()->with('success', $product->qr_active ? 'QR aktif.' : 'QR pasif.');
    }

    // PNG indir
    public function downloadPng(Product $product)
    {
        abort_unless($product->qr_code, 404);

        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            abort(500, 'PNG çıktısı için PHP GD veya Imagick eklentisi gerekir.');
        }

        $url = route('qr.redirect', $product->qr_code);

        $png = QrCode::format('png')->size(600)->margin(1)->generate($url);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-'.$product->id.'.png"',
        ]);
    }


    // SVG indir
    public function downloadSvg(Product $product)
    {
        abort_unless($product->qr_code, 404);
        $url = route('qr.redirect', $product->qr_code);

        $svg = QrCode::format('svg')->size(600)->margin(1)->generate($url);
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr-'.$product->id.'.svg"',
        ]);
    }

    // QR okutma yönlendirmesi (her taramada kayıt)
    public function redirect(string $code)
    {
        $product = Product::where('qr_code', $code)->firstOrFail();

        if (!$product->qr_active) {
            abort(410, 'QR pasif'); // ya da özel bir “pasif” sayfasına yönlendirebilirsin
        }

        // scan kaydı
        QrScan::create([
            'product_id' => $product->id,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer'    => request()->headers->get('referer'),
        ]);

        // PDF’e yönlendir
        return redirect()->to(route('pdf.serve', ['slug' => $product->slug]));

    }

    // basit istatistik örneği
    public function stats(Product $product)
    {
        $total = $product->scans()->count();
        $last7 = $product->scans()->where('created_at','>=',now()->subDays(7))->count();
        $recent = $product->scans()->latest()->take(20)->get();

        return view('qr.stats', compact('product','total','last7','recent'));
    }

    public function servePdf(string $slug)
    {
    $product = \App\Models\Product::where('slug', $slug)->firstOrFail();

    if (!$product->pdf_path || !Storage::disk('public')->exists($product->pdf_path)) {
        abort(404);
    }

    $path = Storage::disk('public')->path($product->pdf_path);

    // Tarayıcıda aç (inline)
    return response()->file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'. $product->slug .'.pdf"',
    ]);
    }

    public function preview(\App\Models\Product $product)
    {
        abort_unless($product->qr_code, 404);

    $url = route('qr.redirect', $product->qr_code);

    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(240)->margin(1)->generate($url);

    return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
