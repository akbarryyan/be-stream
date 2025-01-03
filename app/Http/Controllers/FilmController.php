<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class FilmController extends Controller
{
    // Menampilkan daftar film
    public function index()
    {
        $films = Film::all();
        return response()->json($films);
    }

    // Menampilkan detail film berdasarkan ID
    public function show($id)
    {
        $film = Film::findOrFail($id);
        return response()->json($film);
    }

    // Menghapus film berdasarkan ID
    public function destroy($id)
    {
        $film = Film::findOrFail($id);
        $film->delete();
        return response()->json(['message' => 'Film berhasil dihapus']);
    }

    // Metode untuk melakukan scraping data film dan menyimpannya ke database
    public function scrapeFilms()
    {
        $response = Http::get('https://tv4.lk21official.pics/');

        if ($response->successful()) {
            $htmlContent = $response->body();
            
            // Gunakan Symfony DomCrawler untuk parsing HTML
            $crawler = new Crawler($htmlContent);
            
            $crawler->filter('.item-overlay')->each(function ($node) {
                $title = $node->filter('figcaption h3.caption')->text() ?? 'Unknown Title';
                $image_url = $node->filter('picture source')->first()->attr('srcset') ?? 'default-image-url.jpg'; // Ambil srcset dari elemen source pertama
                $trailer_url = $node->filter('.item-action a.btn-success')->attr('href') ?? null;
                $movie_url = $node->filter('.item-action a.btn-primary')->attr('href') ?? null;

                // Debugging: Logging hasil scraping
                Log::info("Title: {$title}");
                Log::info("Image URL: {$image_url}");
                Log::info("Trailer URL: {$trailer_url}");
                Log::info("Movie URL: {$movie_url}");

                // Pastikan URL gambar lengkap dengan menambahkan protokol
                if (strpos($image_url, '//') === 0) {
                    $image_url = 'https:' . $image_url;
                }

                if ($title && $movie_url) {
                    Film::updateOrCreate(
                        ['title' => $title],
                        [
                            'image_url' => $image_url,
                            'trailer_url' => $trailer_url,
                            'movie_url' => $movie_url
                        ]
                    );
                }
            });

            return response()->json(['message' => 'Data film berhasil di-scrape dan disimpan.']);
        } else {
            return response()->json(['error' => 'Gagal mendapatkan data dari situs web.'], 500);
        }
    }

    // Pencarian film berdasarkan judul
    public function search(Request $request)
    {
        $query = $request->input('query');
        $films = Film::where('title', 'LIKE', "%{$query}%")->get();
        return response()->json($films);
    }
}
