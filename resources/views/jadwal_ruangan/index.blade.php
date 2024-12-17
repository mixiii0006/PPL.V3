<x-app-layout>


    <section class="bg-white py-3 antialiased dark:bg-gray-900 md:pb-4">
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
        <div class="py-5 px-4 mx-auto max-w-screen-xl lg:py-5 lg:px-6">

          <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8 ">
            <div>
              <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <div>
                    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-blue-500 dark:text-blue-500">Selamat Datang {{ Auth::user()->name }}!</h2>
                    <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">MedSched! Sistem Informasi Manajemen Penjadwalan Ruang FKIK Universitas Bengkulu. Efisiensi Waktu, Maksimalkan Ruang!.</p>
                </div>
                </ol>
              </nav>


              <div class="inline-flex flex-col w-full rounded-md shadow-sm md:w-auto md:flex-row" role="group">

                <div class="inline-flex flex-col w-full rounded-md shadow-sm md:w-auto md:flex-row" role="group">
                    <a href="{{ route('jadwal_ruangan.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-blue-500 rounded-t-lg md:rounded-tr-none md:rounded-l-lg hover:bg-blue-600 hover:border-blue-600 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-white dark:bg-blue-700 dark:border-blue-600 dark:hover:bg-blue-600 dark:focus:ring-blue-500 dark:focus:text-white">
                        Semua
                    </a>
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                    @endphp

                    @foreach ($days as $day)
                        <a href="{{ route('jadwal_ruangan.show', $day) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-primary-500 dark:focus:text-white
                        {{ $day === $day ? 'bg-primary-700 text-gray-900 dark:text-white' : '' }}
                        {{-- {{ $loop->first ? 'rounded-t-lg md:rounded-tr-none md:rounded-l-lg' : '' }} --}}
                        {{ $loop->last ? 'rounded-b-lg md:rounded-bl-none md:rounded-r-lg' : '' }}">
                            {{ $day }}
                        </a>
                    @endforeach
                  </div>
              </div>
            </div>

            <div class="flex items-center space-x-4">

              <button id="defaultModalButton"   data-modal-target="defaultModal"data-modal-toggle="defaultModal" type="button" class="flex w-full items-center justify-center rounded-lg border  border-blue-500 bg-white px-3 py-2 text-sm font-medium text-blue-500 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-blue focus:ring-4 focus:ring-blue-100 dark:border-blue-500 dark:bg-gray-800 dark:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue dark:focus:ring-blue-00 sm:w-auto">
                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Cetak
                <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                </svg>
              </button>

              <button data-modal-toggle="filterModal" data-modal-target="filterModal" type="button" class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">
                <svg class="-ms-0.5 me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                </svg>
                Filters
                <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                </svg>
              </button>
            </div>
          </div>



          <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($datas as $data)
            <div class="h-full  flex flex-col">
              <div class=" flex flex-col rounded-lg border border-gray-200 bg-white p-6 shadow-lg shadow-gray-500/50 dark:border-gray-700 dark:bg-gray-800 h-full ">
                <div class="text-center mb-4">
                    <a href="#" class="text-lg font-bold text-blue-500 leading-tight">Jadwal</a>
                </div>

                <div class="w-auto h-[200px] mx-auto ">
                {{-- <div class="w-auto h-auto mx-auto"> --}}
                <table class="table-auto w-full text-left">
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Ruangan</a></td>
                        <td class="pr-2 text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->ruangan->nama_ruangan }}</td>
                    </tr>
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Kuliah</a></td>
                        <td class="pr-2">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->nama_modul}}</td>
                    </tr>
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Modul</a></td>
                        <td class="pr-2">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->mata_kuliah->nama_matakuliah }}</td>
                    </tr>
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Dosen</a></td>
                        <td class="pr-2">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->dosen->Nama }}</td>
                    </tr>
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Jam</a></td>
                        <td class="pr-2">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->jam_mulai }} s.d. {{ $data->pemetaan->jam_selesai }}</td>
                    </tr>
                    <tr>
                        <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Hari</a></td>
                        <td class="pr-2">:</td>
                        <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->hari }}</td>
                    </tr>
                </table>
                </div>

                <div class="mt-3 flex items-center justify-end gap-4">
                {{-- @auth
                @if(Auth::user()->role=='admin'|| Auth::user()->role == 'operator')
                   <div class="flex items-center justify-end gap-1">
                    <button id='{{$data->id}}-editButton' data-modal-target="{{$data->id}}-updateProductModal" data-modal-toggle="{{$data->id}}-updateProductModal"  type="button" data-tooltip-target="tooltip-quick-look" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                      <span class="sr-only"> Edit </span>
                      <svg class="w-5 h-5 text-gray-800 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                      </svg>
                    </button>
                    <div id="tooltip-quick-look" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700" data-popper-placement="top">
                      Edit
                      <div class="tooltip-arrow" data-popper-arrow=""></div>
                    </div>

                    <button id='{{$data->id}}-deleteButton'  data-modal-target="{{$data->id}}-deleteModal" data-modal-toggle="{{$data->id}}-deleteModal" type="button" data-tooltip-target="tooltip-add-to-favorites" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                      <span class="sr-only"> Hapus </span>
                      <svg class="w-5 h-5 text-red-400 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                      </svg>
                    </button>
                    <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700" data-popper-placement="top">
                     Hapus
                      <div class="tooltip-arrow" data-popper-arrow=""></div>
                    </div>
                  </div>
                @endif
                @endauth --}}

                  <button type="button" id="{{$data->id}}-readProductButton" data-modal-target="{{$data->id}}-readProductModal" data-modal-toggle="{{$data->id}}-readProductModal" class="inline-flex items-center  border border-blue-500 rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-blue-500 hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Detail
                  </button>
                </div>
              </div>

            {{-- <x-hapus :id="$data->id" :route="route('jadwal_ruangan.destroy', $data->id)"/> --}}
                </div>

                {{-- <div id="{{$data->id}}-updateProductModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden   bg-gray-500 bg-opacity-25 fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                        <!-- Modal content -->
                        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                            <!-- Modal header -->
                            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                <h3 class="text-lg font-bold  text-center text-blue-500 dark:text-blue-500">
                                    Edit Data
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="{{$data->id}}-updateProductModal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form action="{{ route('jadwal_ruangan.update', $data->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-4 mb-7 sm:grid-cols-1">
                                    <label for="matakuliah-{{$data->id}}" class="block  text-sm font-medium text-gray-900 dark:text-white">Mata Kuliah</label>
                                <select name="pemetaan_id" id="matakuliah-{{$data->id}}" class="js-example-basic-multiple bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="state" >
                                    @foreach ($pemetaan as $item)
                                        <option value="{{ $item->id }}" >{{ $item->mata_kuliah->nama_matakuliah }}</option>
                                    @endforeach
                                </select>
                                @error('pemetaan_id')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror


                                <label for="ruangan-{{$data->id}}" class="block  text-sm font-medium text-gray-900 dark:text-white">Ruang</label>
                                <select name="ruangan_id" id="ruangan-{{$data->id}}" class="js-example-basic-multiple bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="state">
                                    @foreach ($ruangan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                                @error('pemetaan_id')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                                <div>
                                    <label for="jam_masuk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Masuk</label>
                                    <input type="time" name="jam_masuk" id="jam_masuk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ old('jam_masuk', $data->jam_masuk) }}" required="">
                                </div>
                                <div>
                                    <label for="jam_keluar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Keluar</label>
                                    <input type="time" name="jam_keluar" id="jam_keluar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ old('jam_keluar', $data->jam_keluar) }}" required="">
                                </div>
                                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                                    <select id="category" name="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option selected="hari">{{ old('hari', $data->hari)}}</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                    </select>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <button id='{{$data->id}}-update' type="submit" class="text-blue-500 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 text-center border border-blue-500 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        Selesai
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}

                <div id="{{$data->id}}-readProductModal" tabindex="-1" aria-hidden="true" class="hidden  bg-gray-500 bg-opacity-25  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
                    <div class="relative p-4 w-full max-w-xl h-full md:h-auto">
                        <!-- Modal content -->
                        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                <!-- Modal header -->
                                <div class="flex justify-between border-b dark:border-gray-600 rounded-t mb-3 ">
                                    <div class="text-center mb-4">
                                        <a href="#" class="text-lg font-bold text-blue-500 leading-tight">Jadwal</a>
                                    </div>
                                    <div>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="{{$data->id}}-readProductModal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                                    <table>
                                        <tr>
                                            <td  class="pr-4"><a class="text-sm font-semibold  text-gray-900  dark:text-white">Ruangan</a></td>
                                            <td class="pr-2 text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->ruangan->nama_ruangan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Judul Kuliah</a></td>
                                            <td class="pr-2 text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->nama_modul }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Modul</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->mata_kuliah->nama_matakuliah }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Tingkat</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->mata_kuliah->tingkat }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Dosen</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->dosen->Nama }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Tanggal</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->tanggal_mulai }} s.d. {{ $data->pemetaan->tanggal_selesai }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Jam</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->jam_mulai }} s.d. {{ $data->pemetaan->jam_selesai }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Hari</a></td>
                                            <td class="pr-2">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->pemetaan->hari }}</td>
                                        </tr>
                                     
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Kapasitas </a></td>
                                            <td class="pr-2 text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->ruangan->kapasitas }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pr-4"><a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Fasilitas </a></td>
                                            <td class="pr-2 text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white">:</td>
                                            <td class="text-sm font-normal leading-tight text-gray-900 hover:underline dark:text-white">{{ $data->ruangan->fasilitas }}</td>
                                        </tr>

                                    </table>
                                </div>

                        </div>
                    </div>
                </div>

            <script>
                    document.addEventListener("DOMContentLoaded", function(event) {
                    document.getElementById('{{$data->id}}-readProductButton').click();
                    });
            </script>
          @endforeach
          </div>
        </div>

          {{-- modal tambah --}}
          <div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden bg-gray-500 bg-opacity-25  overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-bold  text-center text-blue-500 dark:text-blue-500">
                            Cetak Jadwal
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{ route('jadwal_ruangan.cetak') }}" method="GET">

                        <div class="grid grid-cols-2 gap-4" id="filters" role="tabpanel" aria-labelledby="filters-tab">
                            <!-- Pilihan Mata Kuliah -->
                            <div class="space-y-2 mb-4">
                                <h5 class="text-sm font-medium uppercase text-blue-500">Modul</h5>
                                <div class="flex items-center">
                                    <select
                                        name="mata_kuliah"
                                        id="mata_kuliah"
                                        class="w-full h-12 p-2 rounded-lg border-gray-300 bg-gray-100 text-gray-900 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-primary-600">
                                        <option value=""> Semua Modul</option>
                                        @foreach ($matakuliah as $mk)
                                            <option value="{{ $mk->id }}">{{ $mk->nama_matakuliah }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="space-y-2 mb-4">
                                <h5 class="text-sm font-medium uppercase text-blue-500">Dosen</h5>
                                <div class="flex items-center">
                                    <select
                                        name="dosen"
                                        id="dosen"
                                        class="w-full p-2 h-12 rounded-lg border-gray-300 bg-gray-100 text-gray-900 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-primary-600">
                                        <option value="" > Semua Dosen</option>
                                        @foreach ($dosen as $dsn)
                                            <option value="{{ $dsn->id }}">{{ $dsn->Nama }}</option>
                                        @endforeach
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

        <form action="{{ route('filter.jadwal') }}" method="get" id="filterModal" tabindex="-1" aria-hidden="true" class="fixed bg-gray-500 bg-opacity-25  left-0 right-0 top-0 z-50 hidden h-modal w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0 md:h-full">
            <div class="relative h-full w-full max-w-xl md:h-auto">
              <!-- Modal content -->
              <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex items-start justify-between rounded-t pt-5 px-5 pb-3 ">
                    <h3 class="text-lg font-bold  text-center text-blue-500 dark:text-blue-500">
                        Filters
                    </h3>
                  <button type="button" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="filterModal">
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal body -->
                <div class="px-4 md:px-5">
                  <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="-mb-px flex flex-wrap text-center text-sm font-medium" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                        <li class="mr-1" role="presentation">
                          <button class="inline-block pb-2 pr-1" id="brand-tab" data-tabs-target="#brand" type="button" role="tab" aria-controls="profile" aria-selected="false">Ruangan</button>
                        </li>
                        <li class="mr-1" role="presentation">
                          <button class="inline-block px-2 pb-2 hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300" id="advanced-filers-tab" data-tabs-target="#advanced-filters" type="button" role="tab" aria-controls="advanced-filters" aria-selected="false">Mata Kuliah</button>
                        </li>
                        <li class="mr-1" role="presentation">
                            <button class="inline-block px-2 pb-2 hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300" id="advanced-filers-tab2" data-tabs-target="#advanced-filters2" type="button" role="tab" aria-controls="advanced-filters2" aria-selected="false">Hari</button>
                          </li>
                    </ul>

                  </div>
                  <div id="myTabContent">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3" id="brand" role="tabpanel" aria-labelledby="brand-tab">

                      <div class="space-y-2 ">
                        <h5 class="text-sm font-medium uppercase text-blue-500  ">RD</h5>
                        @foreach ($ruangan as $data)
                        @if (str_starts_with($data->nama_ruangan, 'RD') && intval(substr($data->nama_ruangan, 2)) <= 9)
                            <div class="flex items-center">
                            <input id="checkbbox_{{ $data->id }}" type="checkbox" name="ruangan_ids[]" value="{{ $data->id }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                            <label for="checkbbox_{{ $data->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ $data->nama_ruangan }}
                            </label>
                            </div>
                        @endif
                        @endforeach
                      </div>
                    <div class="space-y-2">
                        <h5 class="text-sm font-medium uppercase text-blue-500  ">RD</h5>
                        @foreach ($ruangan as $data)
                          @if (str_starts_with($data->nama_ruangan, 'RD') && intval(substr($data->nama_ruangan, 2)) > 9)
                            <div class="flex items-center">
                              <input id="checkbbox_{{ $data->id }}" type="checkbox" name="ruangan_ids[]" value="{{ $data->id }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                              <label for="checkbbox_{{ $data->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ $data->nama_ruangan }}
                              </label>
                            </div>
                          @endif
                        @endforeach
                      </div>
                      <div class="space-y-2">
                        <h5 class="text-sm font-medium uppercase text-blue-500 ">RK</h5>
                        @foreach ($ruangan as $data)
                            @if (str_starts_with($data->nama_ruangan, 'RK'))
                                <div class="flex items-center ">
                                    <input id="checkbbox_{{ $data->id }}" type="checkbox" name="ruangan_ids[]" value="{{ $data->id }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    <label for="checkbbox_{{ $data->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $data->nama_ruangan }}
                                    </label>
                                </div>
                            @endif
                        @endforeach

                        <h5 class="text-sm font-medium uppercase text-blue-500  mt-8">Seminar</h5>

                        @foreach ($ruangan as $data)
                            @if (str_starts_with($data->nama_ruangan, 'Seminar'))
                                <div class="flex items-center">
                                    <input id="checkbbox_{{ $data->id }}" type="checkbox" name="ruangan_ids[]" value="{{ $data->id }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    <label for="checkbbox_{{ $data->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $data->nama_ruangan }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                      </div>
                      {{-- <div class="mb-4">
                        <label for="hari" class="text-sm font-medium text-gray-900 dark:text-gray-300">Pilih Hari:</label>
                        <select name="hari" id="hari" class="w-full mt-2 px-4 py-2 border border-gray-300 rounded">
                            <option value="">Semua Hari</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
                <div class="space-y-4" id="advanced-filters" role="tabpanel" aria-labelledby="advanced-filters-tab">
                    <div class="grid grid-cols-1 ">
                        <div class="space-y-2">
                            <h5 class="text-sm font-medium uppercase text-blue-500">Mata Kuliah</h5>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($matakuliah->chunk(13) as $chunkedMatakuliah)
                                    <div>
                                        @foreach ($chunkedMatakuliah as $mataKuliah)
                                            <div class="flex items-center mt-1">
                                                <input id="checkbox_{{ $mataKuliah->id }}" type="checkbox" name="mata_kuliah_ids[]" value="{{ $mataKuliah->id }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                                <label for="checkbox_{{ $mataKuliah->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                    {{ $mataKuliah->nama_matakuliah }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>


                    </div>
                </div>

                <div class="space-y-4" id="advanced-filters2" role="tabpanel" aria-labelledby="advanced-filters-tab2">
                    <div class="grid grid-cols-1 ">
                        <div class="space-y-2">
                            <h5 class="text-sm font-medium uppercase text-blue-500">Hari</h5>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                    <div>
                                        <div class="flex items-center mt-1">
                                            <input id="checkbox_{{ $hari }}" type="checkbox" name="hari[]" value="{{ $hari }}" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                            <label for="checkbox_{{ $hari }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ $hari }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>


                    </div>
                </div>



                <button type="submit" class="rounded-lg border  mt-6 mb-6  border-blue-500 bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-blue-500 dark:text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-700 dark:hover:bg-primary-800 dark:focus:ring-primary-800">Filter</button>

                <!-- Modal footer -->

              </div>
            </div>
        </form>


        <script>

            $(document).ready(function() {
                    $('.js-example-basic-multiple').select2();
                });


                const rdRadio = document.getElementById('jenis_ruangan_rd');
                const rkRadio = document.getElementById('jenis_ruangan_rk');
                const jumlahMahasiswaField = document.getElementById('jumlah_mahasiswa_field');

                function toggleJumlahMahasiswa() {
                    // Jika jenis ruangan RD dipilih, tampilkan field jumlah mahasiswa
                    if (rdRadio.checked) {
                        jumlahMahasiswaField.style.display = 'block';
                    } else {
                        // Jika jenis ruangan RK dipilih, sembunyikan field jumlah mahasiswa
                        jumlahMahasiswaField.style.display = 'none';
                    }
                }

                // Event listener untuk mengubah tampilan saat jenis ruangan berubah
                rdRadio.addEventListener('change', toggleJumlahMahasiswa);
                rkRadio.addEventListener('change', toggleJumlahMahasiswa);

                // Panggil fungsi untuk mengatur tampilan saat halaman dimuat
                toggleJumlahMahasiswa();

        </script>


        </div>


      </section>

</x-app-layout>
