<div class="drawer-side">
    <label for="sidebar-toggle" class="drawer-overlay"></label>

    <aside class="w-52 h-full bg-base-100 shadow-lg flex flex-col border-r border-base-300/60">

        <div class="pt-2 ms-3 text-xl font-bold tracking-tight">

            <a href="{{ route('dashboard') }}" wire:navigate>
                <x-logo />
            </a>
        </div>


        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto sidebar-scroll ">
            <nav class="p-3 space-y-1 text-sm">
                <p class="px-3 pt-3 text-[11px] font-semibold uppercase text-base-content/60">Main</p>

                <x-nav.link route="dashboard" match="dashboard" class="mb-1">
                    <span class="inline-flex items-center gap-2 ">
                        {{-- Dashboard (grid) --}}
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            aria-hidden="true">
                            <rect x="3" y="3" width="8" height="8" rx="1.5"></rect>
                            <rect x="13" y="3" width="8" height="8" rx="1.5"></rect>
                            <rect x="3" y="13" width="8" height="8" rx="1.5"></rect>
                            <rect x="13" y="13" width="8" height="8" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </span>
                </x-nav.link>

                <x-nav.link route="modal" match="modal" class="mb-1">

                    <span class="inline-flex items-center gap-2 ">


                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <rect x="3.5" y="5" width="17" height="14" rx="2.5" />
                            <path d="M3.5 8h17" />
                            <circle cx="17.5" cy="6.5" r="0.8" />
                            <circle cx="14.5" cy="6.5" r="0.8" />
                            <path d="M7 11h8" />
                            <path d="M7 14h5" />
                        </svg>


                        <span>Modal</span>
                    </span>

                </x-nav.link>

                <x-nav.link route="table" match="table" class="mb-1">

                    <span class="inline-flex items-center gap-2 ">

                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm2 8v-2h7v2H4Zm0 2v2h7v-2H4Zm9 2h7v-2h-7v2Zm7-4v-2h-7v2h7Z"
                                clip-rule="evenodd" />
                        </svg>

                        <span>Table</span>
                    </span>
                </x-nav.link>

                <x-nav.link route="alert" match="alert" class="mb-1">

                    <span class="inline-flex items-center gap-2 ">

                        <!-- Info -->
                        <!-- Warning -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="h-4 w-4 shrink-0 stroke-current" fill="none" aria-hidden="true" role="img">
                            <path d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Alert</span>
                    </span>
                </x-nav.link>


                <x-nav.link route="form" match="form" class="mb-1">

                    <span class="inline-flex items-center gap-2 ">

                        <!-- Info -->
                        <!-- Warning -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="h-4 w-4 shrink-0 stroke-current" fill="none" aria-hidden="true" role="img">
                            <path
                                d="M59 64a1 1 0 0 0-1 1v2H47.083a1 1 0 0 0-1 1v11H10V12a5.267 5.267 0 0 0-1.002-3H55a3.003 3.003 0 0 1 3 3v35a1 1 0 0 0 2 0V12a5.006 5.006 0 0 0-5-5H5a5.006 5.006 0 0 0-5 5v32a1 1 0 0 0 1 1h7v35a1 1 0 0 0 1 1h38a1.028 1.028 0 0 0 .79-.376l11.917-11.917A1.091 1.091 0 0 0 60 68v-3a1 1 0 0 0-1-1zM2 43V12a3 3 0 0 1 6 0v31zm46.083 34.503V69h8.503z"
                                style="fill:#1d1b1e" />
                            <path
                                d="M87 52h-4v-1a1 1 0 0 0-1-1H38a1.208 1.208 0 0 0-.53.152L29.714 55H27a1 1 0 0 0 0 2h2.713l7.757 4.848A1.195 1.195 0 0 0 38 62h39v1a1 1 0 0 1-1 1H66a4.004 4.004 0 0 0-4 4 1 1 0 0 0 2 0 2.003 2.003 0 0 1 2-2h10a3.003 3.003 0 0 0 3-3v-1h3a1 1 0 0 0 1-1v-1h4a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-14 0v8H43v-8zm-36 .804v6.392L31.887 56zM39 60v-8h2v8zm36-8h2v8h-2zm6 8h-2v-8h2zm5-2h-3v-4h3zM15 33h20a1 1 0 0 0 1-1V14a1 1 0 0 0-1-1H15a1 1 0 0 0-1 1v18a1 1 0 0 0 1 1zm1-2V19h5v3a1 1 0 0 0 1.555.832L25 21.202l2.445 1.63A1 1 0 0 0 29 22v-3h5v12zm18-14h-5v-2h5zm-7 3.132-1.445-.964a1 1 0 0 0-1.11 0L23 20.132V15h4zM16 15h5v2h-5z"
                                style="fill:#1d1b1e" />
                            <path
                                d="M24 25h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2zM21 28h-3a1 1 0 0 0 0 2h3a1 1 0 0 0 0-2zM53 13H40a1 1 0 0 0 0 2h13a1 1 0 0 0 0-2zM53 19H40a1 1 0 0 0 0 2h13a1 1 0 0 0 0-2zM53 25H40a1 1 0 0 0 0 2h13a1 1 0 0 0 0-2zM53 31H40a1 1 0 0 0 0 2h13a1 1 0 0 0 0-2zM53 37H15a1 1 0 0 0 0 2h38a1 1 0 0 0 0-2zM54 44a1 1 0 0 0-1-1H15a1 1 0 0 0 0 2h38a1 1 0 0 0 1-1zM29 50a1 1 0 0 0-1-1H15a1 1 0 0 0 0 2h13a1 1 0 0 0 1-1zM23 55h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2zM28 61H15a1 1 0 0 0 0 2h13a1 1 0 0 0 0-2zM41 67H15a1 1 0 0 0 0 2h26a1 1 0 0 0 0-2zM41 73H15a1 1 0 0 0 0 2h26a1 1 0 0 0 0-2z"
                                style="fill:#1d1b1e" />
                        </svg>
                        <span>Form</span>
                    </span>
                </x-nav.link>
                <x-nav.group title="Group" match="dashboard.*" class="mb-1">

                    <x-slot:icon>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <!-- clipboard body -->
                            <path d="M8 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" />
                            <!-- clip top -->
                            <path d="M9 4V3a3 3 0 0 1 6 0v1" />
                            <!-- check mark -->
                            <path d="M9 13l2 2 4-4" />
                        </svg>
                    </x-slot:icon>
                    <x-nav.link route="dashboard" match="dashboard" size="sm">
                        <span class="inline-flex items-center gap-2">
                            {{-- List (lines) --}}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" aria-hidden="true">
                                <path d="M4 6h16M4 12h16M4 18h10"></path>
                            </svg>
                            <span>List</span>
                        </span>
                    </x-nav.link>

                </x-nav.group>

                {{-- administrtor routes --}}



            </nav>

        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-base-300/60">
            {{-- <a class="text-primary font-bold justify-start w-full rounded-lg">Hi {{trim(auth()->user()->first_name. '
        '.auth()->user()->last_name)}}</a> --}}
        </div>
    </aside>
</div>
