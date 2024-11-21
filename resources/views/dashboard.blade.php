    <x-app-layout>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-5 px-4 mx-auto max-w-screen-xl lg:py-5 lg:px-6">
                <div class=" max-w-screen-md text-left mb-8 lg:mb-8">
                    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-blue-500 dark:text-blue-500">Selamat Datang {{ Auth::user()->name }}!</h2>
                    <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">MedSched! Sistem Informasi Manajemen Penjadwalan Ruang FKIK Universitas Bengkulu. Efisiensi Waktu, Maksimalkan Ruang!.</p>
                </div>
                <div class="space-y-8 lg:space-y-0 lg:grid lg:grid-cols-4 gap-6 xl:gap-10">
                    @auth
                    @if($role == 'admin' || $role == 'operator' || $role == 'user')
                    <!-- Pricing Card -->
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50    dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium ">Jadwal Ruangan</h3>
                          <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z" clip-rule="evenodd"/>
                              </svg>

                        </div>
                        <a href="{{ route('jadwal_ruangan.index') }}" class=" text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:focus:ring-primary-900">Get started</a>
                    </div>
                    @endif
                    @endauth

                    @auth
                    @if($role == 'admin' || $role == 'operator' )
                    <!-- Pricing Card -->
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50  dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium">Data Ruangan</h3>
                        <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                        </div>

                        <a href="{{ route('data_ruangan.index') }}" class="text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                         dark:focus:ring-primary-900">Get started</a>
                    </div>
                    <!-- Pricing Card -->
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50  dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium">Jadwal MK</h3>
                          <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                        </div>

                        <a href="{{ route('pemetaan_mk.index') }}" class="text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                         dark:focus:ring-primary-900">Get started</a>
                    </div>
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50  dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium">Mata kuliah</h3>
                        <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                        </div>

                        <a href="{{ route('data_mk.index') }}" class="text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                         dark:focus:ring-primary-900">Get started</a>
                    </div>
                    <!-- Pricing Card -->
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50  dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium">Data Dosen</h3>
                          <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                              </svg>
                        </div>

                        <a href="{{ route('data_dosen.index') }}" class="text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                         dark:focus:ring-primary-900">Get started</a>
                    </div>
                    @endif
                    @endauth

                    @auth
                    @if($role == 'admin')
                    <!-- Pricing Card -->
                    <div class="flex flex-col p-6 text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow-lg shadow-gray-500/50  dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white h-full">
                        <h3 class="mb-4 text-2xl font-medium">Data Pengguna</h3>
                         <div class="flex-grow flex justify-center items-center my-8">
                            <svg class="w-[70px] h-[70px] text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                              </svg>

                        </div>

                        <a href="{{ route('data_diri.index') }}" class="text-blue-500 dark:text-blue-500 border border-blue-500 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900">Get started</a>
                    </div>
                    <!-- Pricing Card -->


                    @endif
                    @endauth

                </div>
            </div>
        </section>
   </x-app-layout>





