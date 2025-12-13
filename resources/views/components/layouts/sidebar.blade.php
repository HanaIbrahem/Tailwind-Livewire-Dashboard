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
