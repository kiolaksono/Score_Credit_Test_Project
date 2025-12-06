<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- Wajib untuk transaction
use App\Models\Application;
use App\Models\Group;
use App\Models\Item;
use App\Models\GroupScore;
use App\Models\GroupItemScore;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    /**
     * Determine risk level based on application summary score
     * High Risk: < 56
     * Medium Risk: 56-70
     * Low Risk: > 70
     */
    private function getRiskLevel($score)
    {
        if ($score < 56) {
            return ['level' => 'High Risk', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-400'];
        } elseif ($score >= 56 && $score <= 70) {
            return ['level' => 'Medium Risk', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-400'];
        } else {
            return ['level' => 'Low Risk', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-400'];
        }
    }

    public function index(Request $request)
    {
        // Terima query pencarian dari query string (GET)
        $search = $request->query('search');

        // Query dasar: urut berdasarkan tanggal, serta eager count relasi
        $query = Application::orderBy('created_at', 'desc')
            ->withCount(['groupScores', 'groupItemScores']);

        // Jika ada kata kunci pencarian, filter nama aplikasi
        if (!empty($search)) {
            $query->where('application_name', 'like', '%' . $search . '%');
        }

        // Paginate dan pastikan query string pencarian disertakan di link pagination
        $applications = $query->paginate(15)->appends($request->only('search'));

        return view('applications.index', compact('applications'));
    }

    public function show(\App\Models\Application $application)
    {
        // Load related scores
        $application->load(['groupScores', 'groupItemScores.item', 'groupItemScores.groupItem']);

        // Master groups + items so we can show questions even if no selection
        $groups = Group::with('groupItems.items')->get();

        // Map group scores and item scores for quick lookup
        $groupScoresMap = $application->groupScores->keyBy('group_id');
        $itemScoresMap = $application->groupItemScores->keyBy('group_item_id');

        // Determine risk level based on score
        $risk_level = $this->getRiskLevel($application->application_summary_score);

        return view('applications.show', compact('application', 'groups', 'groupScoresMap', 'itemScoresMap', 'risk_level'));
    }

    public function create()
    {
        // Ambil semua Group, beserta GroupItem dan Item-nya sekaligus (Eager Loading)
        // Ini biar query database hemat dan cepat
        $groups = Group::with('groupItems.items')->get();

        return view('applications.create', compact('groups'));
    }

    public function store(Request $request)
    {
        // TEMP DEBUG: tampilkan payload request supaya kita tahu apa yang dikirim form
        // Hapus/komentari baris ini setelah debugging selesai
        // dd($request->all());

        // 1. Validasi Input
        // Form view mengirimkan nama yang berbeda (mis. applicant_name, date_of_birth),
        // jadi kita terima field minimal dan gunakan fallback saat menyimpan.
        $request->validate([
            'items' => 'required|array',
            'application_name' => 'nullable|string|max:255',
            'application_gender' => 'nullable|in:1,2',
            'application_birth_date' => 'nullable|date',
            'application_birth_place' => 'nullable|string|max:255',
            'application_address' => 'nullable|string|max:500',
            'application_postal_code' => 'nullable|string|max:20',
        ]);

        // Gunakan DB Transaction agar jika ada error di tengah jalan, data tidak masuk setengah-setengah
        DB::beginTransaction();

        try {
            // 2. Simpan Data Pemohon (Application)
            // Kita set score 0 dulu, nanti diupdate setelah hitungan selesai
            $application = Application::create([ // Jika belum ada login, hardcode 1 dulu atau hapus field ini jika nullable
                'application_number' => 'APP-' . time(), // Contoh nomor aplikasi sederhana
                // Fallback ke field yang ada di view: applicant_name / date_of_birth
                'application_name' => $request->input('application_name') ?? $request->input('applicant_name') ?? 'Unknown',
                'application_gender' => $request->input('application_gender') ?? null,
                'application_birth_place' => $request->input('application_birth_place') ?? null,
                'application_birth_date' => $request->input('application_birth_date') ?? $request->input('date_of_birth') ?? null,
                'application_address' => $request->input('application_address') ?? null,
                'application_postal_code' => $request->input('application_postal_code') ?? null,
                'application_summary_score' => 0 
            ]);

            $totalApplicationScore = 0; // Variable penampung skor akhir

            // 3. Ambil Master Data Group & GroupItem untuk Loop Perhitungan
            $groups = Group::with('groupItems')->get();

            foreach ($groups as $group) {
                
                $totalScoreInThisGroup = 0; // Penampung "SUM H" di Excel

                foreach ($group->groupItems as $groupItem) {
                    
                    // Ambil ID jawaban yang dipilih user dari form
                    // $request->items bentuknya array [group_item_id => item_id]
                    $selectedItemId = $request->items[$groupItem->group_item_id] ?? null;

                    if ($selectedItemId) {
                        // Ambil data Item dari DB untuk tahu Skor mentahnya (Bobot F)
                        $item = Item::find($selectedItemId);

                        if ($item) {
                            // RUMUS 1 (Excel Kolom H): Bobot F * Bobot D
                            // Nilai Pilihan * Bobot Pertanyaan
                            $calculatedItemScore = $item->item_rate * $groupItem->group_item_rate;

                            // Simpan ke table group_item_scores (simpan juga item_id agar bisa ditampilkan nanti)
                            GroupItemScore::create([
                                'application_id'    => $application->application_id,
                                'group_item_id'     => $groupItem->group_item_id,
                                'item_id'           => $selectedItemId,
                                'group_item_score'  => $calculatedItemScore
                            ]);

                            // Tambahkan ke penampung skor group
                            $totalScoreInThisGroup += $calculatedItemScore;
                        }
                    }
                }

                // RUMUS 2 (Excel Kolom "SUM H * BOBOT B")
                // Total skor pertanyaan di group ini * Bobot Group
                $finalGroupScore = $totalScoreInThisGroup * $group->group_rate;

                // Simpan ke table group_scores
                GroupScore::create([
                    'application_id' => $application->application_id,
                    'group_id'       => $group->group_id,
                    'group_score'          => $finalGroupScore
                ]);

                // Tambahkan ke Total Skor Aplikasi
                $totalApplicationScore += $finalGroupScore;
            }

            // 4. Update Skor Akhir di Table Application
            $application->update([
                'application_summary_score' => $totalApplicationScore
            ]);

            // Commit transaksi (Simpan permanen ke DB)
            DB::commit();

            // Redirect ke halaman detail aplikasi yang baru dibuat dengan pesan sukses
            return redirect()->route('applications.show', $application->application_id)
                             ->with('success', 'Aplikasi berhasil disimpan! Total Skor: ' . $totalApplicationScore);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('ApplicationController@store error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->view('errors.application_error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}