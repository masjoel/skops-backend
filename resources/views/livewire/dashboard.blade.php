<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">📊 Dashboard Sekolah</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
            <h2 class="text-lg">Siswa</h2>
            <p class="text-3xl font-bold">{{ $totalSiswa }}</p>
        </div>

        <div class="bg-green-500 text-white p-4 rounded-lg shadow">
            <h2 class="text-lg">Guru</h2>
            <p class="text-3xl font-bold">{{ $totalGuru }}</p>
        </div>

        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
            <h2 class="text-lg">Jenis Skor</h2>
            <p class="text-3xl font-bold">{{ $totalSkor }}</p>
        </div>

        <div class="bg-red-500 text-white p-4 rounded-lg shadow">
            <h2 class="text-lg">Kontrol</h2>
            <p class="text-3xl font-bold">{{ $totalKontrol }}</p>
        </div>
    </div>
</div>
