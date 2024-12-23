<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Pilih Ruangan</h1>

    <!-- Jika tidak ada ruangan yang tersedia -->
    @if($availableRooms->isEmpty())
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">
            <strong>Perhatian!</strong> Tidak ada ruangan yang tersedia pada jadwal ini.
        </div>
    @else
        <form action="{{ route('log_ruangan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pemetaan_id" value="{{ $pemetaan->id }}">

            <label for="ruangan_id" class="block text-sm font-medium text-gray-900 dark:text-white">Pilih Ruangan</label>
            <select name="ruangan_id" id="ruangan_id" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @foreach($availableRooms as $ruangan)
                    <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                @endforeach
            </select>

            <div class="mt-4 flex justify-center">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Pilih Ruangan
                </button>
            </div>
        </form>
    @endif
</div>
