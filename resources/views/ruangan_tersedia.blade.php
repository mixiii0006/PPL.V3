<x-app-layout>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 ">
        @if ($errors->any())
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="sr-only">Danger</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>

            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl tracking-tight font-extrabold text-blue-500 dark:text-blue-500 mb-4">Ruangan Tersedia pada Hari {{ $hari }}</h2>
            <div class="inline-flex flex-col w-full rounded-md shadow-sm md:w-auto md:flex-row" role="group">
                <div class="inline-flex flex-col w-full rounded-md shadow-sm md:w-auto md:flex-row" role="group">
                    {{-- <a href="{{ route('log_ruangan.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-blue-500 rounded-t-lg md:rounded-tr-none md:rounded-l-lg hover:bg-blue-600 hover:border-blue-600 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-white dark:bg-blue-700 dark:border-blue-600 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:text-white">
                        Semua
                    </a> --}}
                    {{-- @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                        $currentDay = request()->route('day'); // Get the current day from the URL
                    @endphp

                    @foreach ($days as $day)
                        <a href="{{ route('ruangan_tersedia.show', $day) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-blue-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-white dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-blue-600 dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white
                        {{ $day === $currentDay ? 'bg-blue-500 text-white' : '' }}
                        {{ $loop->last ? 'rounded-b-lg md:rounded-bl-none md:rounded-r-lg' : '' }}">
                            {{ $day }}
                        </a>
                    @endforeach --}}
                </div>

                <button id="defaultModalButton3"   data-modal-target="defaultModal3" data-modal-toggle="defaultModal3" type="button" class="flex w-full items-center justify-center rounded-lg border ml-3 border-blue-500 bg-white px-3 py-2 text-sm font-medium text-blue-500 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-blue focus:ring-4 focus:ring-blue-100 dark:border-blue-500 dark:bg-gray-800 dark:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue dark:focus:ring-blue-00 sm:w-auto">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Cetak
                    <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                    </svg>
                </button>

                <button  type="button" id="defaultModalButton"   data-modal-target="defaultModal"data-modal-toggle="defaultModal"
                        class="flex items-center justify-center text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium ml-3 rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 border border-blue-500">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Tambah
                </button>
            </div>
        </div>


        {{-- @foreach ($ruangan as $r)
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mb-6">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 pr-4 pl-4 pb-2 pt-2">
                    <div class="text-2xl font-bold text-center py-3  text-blue-500 dark:text-blue-500">{{ $r->nama_ruangan }}</div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" data-modal-target="tambahModal_{{ $r->id }}" data-modal-toggle="tambahModal_{{ $r->id }}" class="text-blue-500 border border-blue-500 px-4 py-2 rounded-lg">Tambah Jadwal</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead>
                            <tr>

                                <th>Slot Waktu Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availableRooms as $room)
                                <tr>
                                    <td>
                                        @foreach ($room['slots'] as $slot)
                                            <div>{{ $slot['start'] }} - {{ $slot['end'] }}</div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
             <!-- Modal content for this room -->
            <div id="tambahModal_{{ $r->id }}" tabindex="-1" aria-hidden="true" class="hidden bg-gray-500 bg-opacity-25 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-bold text-center text-blue-500 dark:text-blue-500">
                                Tambah Jadwal untuk {{ $r->nama_ruangan }}
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="tambahModal_{{ $r->id }}">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <!-- Modal body -->
                        <form action="{{ route('log_ruangan.store') }}" method="POST">
                            @csrf
                            <div class="grid gap-4">
                                <div class="grid gap-4 mb-7 sm:grid-cols-1">
                                    <div>
                                        <label for="nama_modul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Kuliah</label>
                                        <input type="text" name="nama_modul" id="nama_modul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required="">
                                    </div>

                                    <label for="nama_matakuliah--{{ $loop->index }}" class="block  text-sm font-medium text-gray-900 dark:text-white">Modul</label>
                                    <select name="matakuliah_id" id="nama_matakuliah-{{ $loop->index }}" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                        @foreach ($matakuliah as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_matakuliah }}</option>
                                        @endforeach
                                    </select>
                                    <label for="Nama-{{ $loop->index }}" class="block  text-sm font-medium text-gray-900 dark:text-white">Dosen</label>
                                    <select name="dosen_id" id="Nama-{{ $loop->index }}" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                        @foreach ($dosen as $item)
                                            <option value="{{ $item->id }}">{{ $item->Nama }}</option>
                                        @endforeach
                                    </select>

                                    <div class="grid grid-cols-3 gap-4">
                                        <!-- Hari -->
                                        <div>
                                            <label for="hari" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                                            <select id="hari" name="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                                <option value="">Pilih Hari</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jumat">Jumat</option>
                                            </select>
                                        </div>

                                        <!-- Jam Mulai -->
                                        <div>
                                            <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input type="time" id="jam_mulai" name="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="07:00" required />
                                            </div>
                                        </div>

                                        <!-- Jam Selesai -->
                                        <div>
                                            <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input type="time" id="jam_selesai" name="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="10:00" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Tanggal Mulai -->
                                        <div>
                                            <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                        </div>

                                        <!-- Tanggal Selesai -->
                                        <div>
                                            <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                        </div>
                                    </div>

                                    <label for="jenis_ruangan" class="block text-sm font-medium text-gray-900 dark:text-white">Jenis Ruangan</label>
                                    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                            <div class="flex items-center ps-3">
                                                <input id="jenis_ruangan_rk" type="radio" name="jenis_ruangan" value="RK" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                <label for="jenis_ruangan_rk" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Kuliah</label>
                                            </div>
                                        </li>
                                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                            <div class="flex items-center ps-3">
                                                <input id="jenis_ruangan_rd" type="radio" name="jenis_ruangan" value="RD" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                <label for="jenis_ruangan_rd" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Diskusi</label>
                                            </div>
                                        </li>
                                        <li class="w-full dark:border-gray-600">
                                            <div class="flex items-center ps-3">
                                                <input id="jenis_ruangan_seminar" type="radio" name="jenis_ruangan" value="Seminar" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                <label for="jenis_ruangan_seminar" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Seminar</label>
                                            </div>
                                        </li>
                                    </ul>

                                    <div >
                                        <label for="jumlah_mahasiswa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Mahasiswa</label>
                                        <input type="number" id="jumlah_mahasiswa" name="jumlah_mahasiswa"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </div>



                                </div>
                            </div>
                            <button type="submit" class="flex text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8  py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                Tambah
                            </button>

                        </form>
                        <script>
                            $(document).ready(function() {
                               $('.js-example-basic-single').select2();
                           });
                       </script>
                    </div>
                </div>
            </div>
        @endforeach --}}


        {{-- <table>
            <thead>
                <tr>
                    <th>Ruangan</th>
                    <th>Slot Waktu Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($availableRooms as $room)
                    <tr>
                        <td>{{ $room['ruangan'] }}</td>
                        <td>
                            @foreach ($room['slots'] as $slot)
                                <div>{{ $slot['start'] }} - {{ $slot['end'] }}</div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        <div class="inline-flex flex-col w-full rounded-md shadow-sm md:w-auto md:flex-row mb-4" role="group">
            @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                $currentDay = request()->query('hari'); // Ambil hari yang dipilih dari query string
            @endphp

            @foreach ($days as $day)
                <a href="{{ route('ruangan_tersedia.index', ['hari' => $day]) }}"
                   id="day-{{ $loop->iteration }}"
                   class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-blue-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-white dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-blue-600 dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white
                   {{ $day === $currentDay ? 'bg-blue-500 text-active' : '' }}
                   {{ $loop->first ? 'rounded-t-lg md:rounded-tr-none md:rounded-l-lg' : '' }}
                   {{ $loop->last ? 'rounded-b-lg md:rounded-bl-none md:rounded-r-lg' : '' }}"
                   onclick="setActiveButton(this)">
                    {{ $day }}
                </a>
            @endforeach
        </div>
        {{-- <h2 class="text-xl ml-5 font-semibold mb-4">Ruangan yang Tersedia pada Hari {{ $hari }}</h2> --}}


        <!-- Jika ada ruangan yang tersedia, tampilkan -->
        @if(!empty($availableRooms))

        <div class="space-y-4">
            @foreach ($availableRooms as $room)
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 pr-4 pl-4 pb-2 pt-2">
                        <div class="text-2xl font-bold text-center py-3 text-blue-500 dark:text-blue-500">{{ $room['ruangan'] }}</div>
                        {{-- <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="button" data-modal-target="tambahModal_{{ $room['ruangan'] }}" data-modal-toggle="tambahModal_{{ $room['ruangan'] }}" class="text-blue-500 border border-blue-500 px-4 py-2 rounded-lg">Tambah Jadwal</button>
                        </div> --}}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">NO</th>
                                    <th scope="col" class="px-4 py-3">Jam Tersedia</th>
                                    <th scope="col" class="px-4 py-3">Jam Mulai</th>
                                    <th scope="col" class="px-4 py-3">Jam Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($room['slots']))
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center">Tidak ada slot yang tersedia</td>
                                    </tr>
                                @else
                                    @foreach($room['slots'] as $slot)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3">Jam Tersedia</td>
                                            <td class="px-4 py-3">{{ $slot['start'] }}</td>
                                            <td class="px-4 py-3">{{ $slot['end'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>



                <div id="tambahModal_{{ $room['ruangan'] }}" tabindex="-1" aria-hidden="true" class="hidden bg-gray-500 bg-opacity-25 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                            <!-- Modal header -->
                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                <h3 class="text-lg font-bold text-center text-blue-500 dark:text-blue-500">
                                    Tambah Jadwal untuk {{ $room['ruangan'] }}
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="tambahModal_{{ $room['ruangan'] }}">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>

                            <!-- Modal body -->
                            <form action="{{ route('ruangan_tersedia.store') }}" method="POST">
                                @csrf
                                <div class="grid gap-4">
                                    <div class="grid gap-4 mb-7 sm:grid-cols-1">
                                        <input type="hidden" name="skip_create_jadwal" value="1">
                                        <div>
                                            <label for="nama_modul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Kuliah</label>
                                            <input type="text" name="nama_modul" id="nama_modul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required="">
                                        </div>

                                        <label for="nama_matakuliah--{{ $loop->index }}" class="block  text-sm font-medium text-gray-900 dark:text-white">Modul</label>
                                        <select name="matakuliah_id" id="nama_matakuliah-{{ $loop->index }}" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                            @foreach ($matakuliah as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_matakuliah }}</option>
                                            @endforeach
                                        </select>
                                        <label for="Nama-{{ $loop->index }}" class="block  text-sm font-medium text-gray-900 dark:text-white">Dosen</label>
                                        <select name="dosen_id" id="Nama-{{ $loop->index }}" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                            @foreach ($dosen as $item)
                                                <option value="{{ $item->id }}">{{ $item->Nama }}</option>
                                            @endforeach
                                        </select>

                                        <div class="grid grid-cols-3 gap-4">
                                            <!-- Hari -->
                                            <div>
                                                <label for="hari" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                                                <select id="hari" name="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                                    <option value="">Pilih Hari</option>
                                                    <option value="Senin">Senin</option>
                                                    <option value="Selasa">Selasa</option>
                                                    <option value="Rabu">Rabu</option>
                                                    <option value="Kamis">Kamis</option>
                                                    <option value="Jumat">Jumat</option>
                                                </select>
                                            </div>

                                            <!-- Jam Mulai -->
                                            <div>
                                                <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <input type="time" id="jam_mulai" name="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="07:00" required />
                                                </div>
                                            </div>

                                            <!-- Jam Selesai -->
                                            <div>
                                                <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <input type="time" id="jam_selesai" name="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="10:00" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Tanggal Mulai -->
                                            <div>
                                                <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                            </div>

                                            <!-- Tanggal Selesai -->
                                            <div>
                                                <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                            </div>
                                        </div>

                                        <label for="jenis_ruangan" class="block text-sm font-medium text-gray-900 dark:text-white">Jenis Ruangan</label>
                                        <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="jenis_ruangan_rk" type="radio" name="jenis_ruangan" value="RK" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="jenis_ruangan_rk" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Kuliah</label>
                                                </div>
                                            </li>
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="jenis_ruangan_rd" type="radio" name="jenis_ruangan" value="RD" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="jenis_ruangan_rd" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Diskusi</label>
                                                </div>
                                            </li>
                                            <li class="w-full dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="jenis_ruangan_seminar" type="radio" name="jenis_ruangan" value="Seminar" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="jenis_ruangan_seminar" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Seminar</label>
                                                </div>
                                            </li>
                                        </ul>

                                        <div >
                                            <label for="jumlah_mahasiswa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Mahasiswa</label>
                                            <input type="number" id="jumlah_mahasiswa" name="jumlah_mahasiswa"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        </div>



                                    </div>
                                </div>
                                <button type="submit" class="flex text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8  py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                    Tambah
                                </button>

                            </form>
                            <script>
                                $(document).ready(function() {
                                   $('.js-example-basic-single').select2();
                               });
                           </script>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @else
        <p>Tidak ada ruangan yang tersedia pada hari ini.</p>
    @endif



    <div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden bg-gray-500 bg-opacity-25  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-bold  text-center text-blue-500 dark:text-blue-500">
                        Tambah Data
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('ruangan_tersedia.store') }}" method="POST">
                    @csrf
                    <div class="grid gap-4">
                        <div class="grid gap-4 mb-7 sm:grid-cols-1">
                            <input type="hidden" name="skip_create_jadwal" value="1">
                            <div>
                                <label for="nama_modul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Kuliah</label>
                                <input type="text" name="nama_modul" id="nama_modul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required="">
                            </div>

                            <label for="nama_matakuliah" class="block  text-sm font-medium text-gray-900 dark:text-white">Modul</label>
                            <select name="matakuliah_id" id="nama_matakuliah" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                @foreach ($matakuliah as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_matakuliah }}</option>
                                @endforeach
                            </select>
                            <label for="Nama" class="block  text-sm font-medium text-gray-900 dark:text-white">Dosen</label>
                            <select name="dosen_id" id="Nama" class="js-example-basic-single bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="states" multiple="multiple">
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->id }}">{{ $item->Nama }}</option>
                                @endforeach
                            </select>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Hari -->
                                <div>
                                    <label for="hari" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                                    <select id="hari" name="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Pilih Hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>

                                <!-- Jam Mulai -->
                                <div>
                                    <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="jam_mulai" name="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="07:00" required />
                                    </div>
                                </div>

                                <!-- Jam Selesai -->
                                <div>
                                    <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="jam_selesai" name="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="07:00" max="18:00" value="10:00" required />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Tanggal Mulai -->
                                <div>
                                    <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="" required>
                                </div>
                            </div>

                            <label for="jenis_ruangan" class="block text-sm font-medium text-gray-900 dark:text-white">Jenis Ruangan</label>
                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                    <div class="flex items-center ps-3">
                                        <input id="jenis_ruangan_rk" type="radio" name="jenis_ruangan" value="RK" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="jenis_ruangan_rk" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Kuliah</label>
                                    </div>
                                </li>
                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                    <div class="flex items-center ps-3">
                                        <input id="jenis_ruangan_rd" type="radio" name="jenis_ruangan" value="RD" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="jenis_ruangan_rd" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Diskusi</label>
                                    </div>
                                </li>
                                <li class="w-full dark:border-gray-600">
                                    <div class="flex items-center ps-3">
                                        <input id="jenis_ruangan_seminar" type="radio" name="jenis_ruangan" value="Seminar" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="jenis_ruangan_seminar" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruang Seminar</label>
                                    </div>
                                </li>
                            </ul>

                            <div class="grid grid-cols-2 gap-4">
                            <div>
                            <label for="nama_ruangan" class="block  text-sm font-medium text-gray-900 dark:text-white mb-2">Ruang</label>
                            <select name="ruangan_id" id="nama_ruangan" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            </div>
                            <!-- Input Jumlah Mahasiswa (Hanya untuk RD) -->
                            <div id="jumlah_mahasiswa_field">
                                <label for="jumlah_mahasiswa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Mahasiswa</label>
                                <input type="number" id="jumlah_mahasiswa" name="jumlah_mahasiswa"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>
                            </div>


                        </div>
                    </div>
                    <button type="submit" class="flex text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8  py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>






        <div id="defaultModal3" tabindex="-1" aria-hidden="true" class="hidden bg-gray-500 bg-opacity-25  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-bold  text-center text-blue-500 dark:text-blue-500">
                            Cetak Jadwal
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal3">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{ route('jadwal_ruangan.cetak') }}" method="GET">

                        <div class="grid grid-cols-2 gap-4" id="filters" role="tabpanel" aria-labelledby="filters-tab">
                            <!-- Pilihan Mata Kuliah -->
                            <div class="space-y-2 mb-4">
                                <h5 class="text-sm font-medium uppercase text-blue-500">Ruangan</h5>
                                <div class="flex items-center">
                                    <select
                                        name="ruangan"
                                        id="ruangan"
                                        class="w-full h-12 p-2 rounded-lg border-gray-300 bg-gray-100 text-gray-900 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-primary-600">
                                        <option value=""> Semua Ruangan</option>
                                        @foreach ($ruangan as $mk)
                                            <option value="{{ $mk->id }}">{{ $mk->nama_ruangan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="space-y-2 mb-4">
                                <h5 class="text-sm font-medium uppercase text-blue-500">Hari</h5>
                                <div class="flex items-center">
                                    <select name="hari" class="w-full h-12 p-2 rounded-lg border-gray-300 bg-gray-100 text-gray-900 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-primary-600">
                                        <option value="">Hari </option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <button type="submit" class="flex text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8  py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                            Cetak
                        </button>
                    </form>
                </div>
            </div>
        </div>




        <script>

            function searchData() {
            const searchInput = document.getElementById('simple-search').value.toLowerCase();
            const dataContainer = document.getElementById('data-container');
            const rows = dataContainer.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const cellText = cells[j].textContent || cells[j].innerText;
                        if (cellText.toLowerCase().includes(searchInput)) {
                            found = true;
                            break;
                        }
                    }
                }

                if (found) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
                }
            }

            document.getElementById('simple-search').addEventListener('input', searchData);

            searchData();


            document.addEventListener("DOMContentLoaded", function(event) {
            document.getElementById('defaultModalButton').click();
            });


            document.addEventListener('DOMContentLoaded', function() {
                const courseData = document.getElementById('courseData').value;
                let datas = courseData.split(',');

                const resultBox = document.querySelector('.resultBox');
                const inputBox = document.getElementById('nama_matakuliah');

                inputBox.addEventListener('keyup', function() {
                    let result = [];
                    let input = inputBox.value;

                    if (input.length) {
                        result = datas.filter((keyword) => {
                            return keyword.toLowerCase().includes(input.toLowerCase());
                        });

                        display(result);
                    } else {
                        resultBox.innerHTML = "";
                        resultBox.style.display = "none"; // Sembunyikan kotak hasil jika input kosong
                    }

                    if (!result.length) {
                        resultBox.innerHTML = "";
                        resultBox.style.display = "none"; // Sembunyikan kotak hasil jika tidak ada hasil
                    }
                });

                function display(result) {
                    if (result.length) {
                        const content = result.map((list) => {
                            return "<li onclick='selectInput(this)' class='cursor-pointer hover:bg-gray-200'>" + list + "</li>";
                        });
                        resultBox.innerHTML = "<ul class='border border-gray-300 bg-white'>" + content.join('') + "</ul>";
                        resultBox.style.display = "block"; // Tampilkan kotak hasil
                        }
                    }
                });

                function selectInput(list) {
                    const inputBox = document.getElementById('nama_matakuliah');
                    inputBox.value = list.innerHTML; // Set input value ke nama mata kuliah yang dipilih
                    const resultBox = document.querySelector('.resultBox');
                    resultBox.innerHTML = "";
                    resultBox.style.display = "none"; // Sembunyikan kotak hasil setelah pemilihan
                }

                $(document).ready(function() {
                    $('.js-example-basic-single').select2();

                });


                const rdRadio = document.getElementById('jenis_ruangan_rd');
                const rkRadio = document.getElementById('jenis_ruangan_rk');
                const seminarRadio = document.getElementById('jenis_ruangan_seminar'); // Correct ID here
                const jumlahMahasiswaField = document.getElementById('jumlah_mahasiswa_field');

                function toggleJumlahMahasiswa() {
                    // If RD (Ruang Diskusi) is selected, show the jumlah mahasiswa field
                    if (rdRadio.checked) {
                        jumlahMahasiswaField.style.display = 'block';
                    } else {
                        // Otherwise, hide the jumlah mahasiswa field
                        jumlahMahasiswaField.style.display = 'none';
                    }
                }

                // Event listeners to trigger the visibility toggle when any radio button changes
                rdRadio.addEventListener('change', toggleJumlahMahasiswa);
                rkRadio.addEventListener('change', toggleJumlahMahasiswa);
                seminarRadio.addEventListener('change', toggleJumlahMahasiswa);

                // Call the function initially to set the correct state on page load
                toggleJumlahMahasiswa();


               // Fungsi untuk menangani perubahan tombol yang dipilih
                function setActiveButton(button) {
                    // Hapus kelas 'bg-blue-500' dan 'text-white' dari semua tombol
                    document.querySelectorAll('.inline-flex a').forEach(function(btn) {
                        btn.classList.remove('bg-blue-500', 'text-white', 'active');
                    });

                    // Menambahkan kelas 'bg-blue-500', 'text-white', dan 'active' pada tombol yang dipilih
                    button.classList.add('bg-blue-500', 'text-white', 'active');
                }

                // Menambahkan logika untuk tetap mempertahankan tombol aktif setelah refresh
                document.addEventListener('DOMContentLoaded', function() {
                    const currentDay = '{{ $currentDay }}';
                    if (currentDay) {
                        const activeButton = document.querySelector(`a[href*="${currentDay}"]`);
                        if (activeButton) {
                            activeButton.classList.add('bg-blue-500', 'text-white', 'active');
                        }
                    }
                });



        </script>
        </section>

    </x-app-layout>




