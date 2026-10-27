
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta Pelatihan</title>
</head>
<body>
    <h1>Data Peserta Pelatihan</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Tambah Peserta</h2>

    <form action="{{ route('peserta.store') }}" method="POST">
        @csrf

        <p>
            <label>Kode Peserta:</label><br>
            <input type="text" name="kode_peserta"
                value="{{ old('kode_peserta') }}" maxlength="10" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email"
                value="{{ old('email') }}" maxlength="100" required>
        </p>

        <p>
            <label>Nama:</label><br>
            <input type="text" name="nama"
                value="{{ old('nama') }}" maxlength="100" required>
        </p>

        <p>
            <label>Usia (15–60 tahun):</label><br>
            <input type="number" name="usia" min="15" max="60"
                value="{{ old('usia') }}" required>
        </p>

        <p>
            <label>Program Pelatihan:</label><br>
            <select name="program_pelatihan_id" required>
                <option value="">-- Pilih Program --</option>
                @foreach ($program as $item)
                    <option value="{{ $item->id }}"
                        {{ old('program_pelatihan_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_program }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Peserta</button>
    </form>

    <hr>

    <h2>Cari Peserta</h2>

    <form action="{{ route('peserta.index') }}" method="GET">
        <input type="text" name="cari"
            value="{{ request('cari') }}"
            placeholder="Kode, nama, atau email">

        <label>Program:</label>
        <select name="program_pelatihan_id">
            <option value="">Semua Program</option>
            @foreach ($program as $item)
                <option value="{{ $item->id }}"
                    {{ request('program_pelatihan_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama_program }}
                </option>
            @endforeach
        </select>

        <button type="submit">Cari / Filter</button>
        <a href="{{ route('peserta.index') }}">Reset</a>
    </form>

    <h2>Daftar Peserta</h2>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Peserta</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Usia</th>
                <th>Program Pelatihan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peserta as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_peserta }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->usia }}</td>
                    <td>{{ $item->nama_program }}</td>
                    <td>
                        <a href="{{ route('peserta.edit', $item->id) }}">Edit</a>

                        <form action="{{ route('peserta.destroy', $item->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada data peserta yang sesuai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
