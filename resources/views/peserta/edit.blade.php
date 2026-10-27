
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta</title>
</head>
<body>
    <h1>Edit Data Peserta</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Kode Peserta:</label><br>
            <input type="text" name="kode_peserta"
                value="{{ old('kode_peserta', $peserta->kode_peserta) }}"
                maxlength="10" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email"
                value="{{ old('email', $peserta->email) }}"
                maxlength="100" required>
        </p>

        <p>
            <label>Nama:</label><br>
            <input type="text" name="nama"
                value="{{ old('nama', $peserta->nama) }}"
                maxlength="100" required>
        </p>

        <p>
            <label>Usia (15–60 tahun):</label><br>
            <input type="number" name="usia"
                value="{{ old('usia', $peserta->usia) }}"
                min="15" max="60" required>
        </p>

        <p>
            <label>Program Pelatihan:</label><br>
            <select name="program_pelatihan_id" required>
                <option value="">-- Pilih Program --</option>
                @foreach ($program as $item)
                    <option value="{{ $item->id }}"
                        {{ old('program_pelatihan_id', $peserta->program_pelatihan_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_program }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('peserta.index') }}">Kembali</a>
    </form>
</body>
</html>
