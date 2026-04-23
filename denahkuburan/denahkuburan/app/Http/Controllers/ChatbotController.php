<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $apiKey = env('OPENROUTER_API_KEY');
        if (!$apiKey) {
            return response()->json(['reply' => 'Sistem belum dikonfigurasi (API Key OpenRouter tidak ditemukan di .env).']);
        }

        $systemPrompt = "Anda adalah Asisten Virtual resmi dari DenahMakam bernama Asisten DenahMakam. Tugas Anda adalah membantu pelanggan dengan informasi terkait pemesanan makam, biaya (standar Rp 2.500.000 sudah termasuk sewa lahan, jasa gali, dan pemeliharaan 8 bulan pertama), prosedur pembayaran, dan lokasi blok makam (Islam: Blok A-B, Protestan: Blok C-D, Katolik: Blok E-F, Hindu/Budha: Blok G-L, Umum: Blok M-N). Berikan jawaban yang ramah, sopan, ringkas, berempati, dan menggunakan bahasa Indonesia yang baik.";

        try {
            $response = Http::timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => url('/'),
                'X-Title' => 'DenahMakam Chatbot',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemma-3-4b-it:free',
                'messages' => [
                    ['role' => 'user', 'content' => $systemPrompt . "\n\nIni adalah pertanyaan dari pelanggan yang harus Anda jawab secara langsung sebagai Asisten:\n" . $request->message],
                ],
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya sedang tidak dapat memproses kata-kata saat ini.';
                return response()->json(['reply' => $reply]);
            }

            return response()->json(['reply' => 'Maaf, terjadi gangguan dalam menghubungi layanan asisten kami (Error ' . $response->status() . ').'], 500);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Maaf, server AI sedang mengalami masalah koneksi.'], 500);
        }
    }
}
