<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\ProgramPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('peserta as p')
            ->join(
                'program_pelatihan as pp',
                'p.program_pelatihan_id',
                '=',
                'pp.id'
            )
            ->select(
                'p.*',
                'pp.nama_program'
            );

        if ($request->filled('cari')) {
            $cari = trim(strip_tags($request->input('cari')));

            $query->where(function ($q) use ($cari) {
                $q->where('p.kode_peserta', 'like', "%{$cari}%")
                    ->orWhere('p.nama', 'like', "%{$cari}%")
                    ->orWhere('p.email', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('program_pelatihan_id')) {
            $query->where(
                'p.program_pelatihan_id',
                $request->input('program_pelatihan_id')
            );
        }

        $peserta = $query->orderBy('p.id')->get();
        $program = ProgramPelatihan::orderBy('nama_program')->get();

        return view('peserta.index', compact('peserta', 'program'));
    }

    public function store(Request $request)
    {
        $data = $this->bersihkanInput($request);

        $validated = validator($data, [
            'kode_peserta' => [
                'required', 'string', 'size:10',
                'alpha_dash',
                'unique:peserta,kode_peserta',
            ],
            'email' => [
                'required', 'email', 'max:100',
                'unique:peserta,email',
            ],
            'nama' => ['required', 'string', 'max:100'],
            'usia' => ['required', 'integer', 'between:15,60'],
            'program_pelatihan_id' => [
                'required',
                'integer',
                'exists:program_pelatihan,id',
            ],
        ])->validate();

        try {
            DB::table('peserta')->insert([
                ...$validated,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('peserta.index')
                ->with('success', 'Data peserta berhasil ditambahkan.');
        } catch (Throwable $e) {
            report($e);
            Log::error('Gagal menambah peserta.');

            return back()->withInput()
                ->with('error', 'Data gagal disimpan. Periksa kembali data Anda.');
        }
    }

    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        $program = ProgramPelatihan::orderBy('nama_program')->get();

        return view('peserta.edit', compact('peserta', 'program'));
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);
        $data = $this->bersihkanInput($request);

        $validated = validator($data, [
            'kode_peserta' => [
                'required', 'string', 'size:10', 'alpha_dash',
                Rule::unique('peserta', 'kode_peserta')->ignore($peserta->id),
            ],
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('peserta', 'email')->ignore($peserta->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'usia' => ['required', 'integer', 'between:15,60'],
            'program_pelatihan_id' => [
                'required', 'integer',
                'exists:program_pelatihan,id',
            ],
        ])->validate();

        try {
            DB::table('peserta')->where('id', $id)->update([
                ...$validated,
                'updated_at' => now(),
            ]);

            return redirect()->route('peserta.index')
                ->with('success', 'Data peserta berhasil diperbarui.');
        } catch (Throwable $e) {
            report($e);
            Log::error('Gagal memperbarui peserta.');

            return back()->withInput()
                ->with('error', 'Data gagal diperbarui. Periksa kembali data Anda.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('peserta')->where('id', $id)->delete();

            return redirect()->route('peserta.index')
                ->with('success', 'Data peserta berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);
            Log::error('Gagal menghapus peserta.');

            return back()->with('error', 'Data gagal dihapus.');
        }
    }

    private function bersihkanInput(Request $request): array
    {
        $data = $request->only([
            'kode_peserta',
            'email',
            'nama',
            'usia',
            'program_pelatihan_id',
        ]);

        foreach (['kode_peserta', 'email', 'nama'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }

        return $data;
    }
}
