<header class="sticky top-0 z-20 border-b border-base-300/60 bg-base-100 backdrop-blur">
  <div class="flex items-center justify-between gap-3 px-3 py-2 md:px-4">

    <div class="flex items-center gap-2">
      <label for="sidebar-toggle" class="btn btn-square btn-ghost lg:hidden">
        ☰
      </label>
      <h1 class="text-xl md:text-xl font-semibold tracking-tight">Dashboard</h1>
    </div>

    <div class="hidden md:flex items-center max-w-md w-full">
      <label
        class="input input-bordered input-sm flex items-center gap-2 w-full hover:ring-2 hover:ring-primary hover:border-primary transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
          class="w-4 h-4 opacity-70">
          <path fill-rule="evenodd"
            d="M10.5 3.75a6.75 6.75 0 1 0 4.2 12.06l3.72 3.72a.75.75 0 1 0 1.06-1.06l-3.72-3.72a6.75 6.75 0 0 0-5.26-11zM5.25 10.5a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0z"
            clip-rule="evenodd" />
        </svg>
        <input type="text" class="grow" placeholder="Search..." />
      </label>
    </div>

    <div class="flex items-center gap-2 md:gap-3">
      <x-nav.theme />

      <!-- 🔔 Notification dropdown -->
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle indicator">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <!-- for any Notifications -->
          <span class="badge badge-xs badge-primary indicator-item"></span>
        </div>
        <div tabindex="0"
          class="mt-4 z-30 card card-compact dropdown-content w-72 bg-base-100 border border-base-300/60 shadow">
          <div class="card-body">
            <h2 class="card-title">Notifications</h2>
            <ul class="space-y-2 text-sm">
              <li class="flex justify-between items-center">
                <span>New user signed up</span>
                <span class="badge badge-success badge-sm">Now</span>
              </li>
              <li class="flex justify-between items-center">
                <span>Payment received</span>
                <span class="badge badge-info badge-sm">5 min</span>
              </li>
              <li class="flex justify-between items-center">
                <span>System update available</span>
                <span class="badge badge-warning badge-sm">1 hr</span>
              </li>
            </ul>
            <div class="card-actions mt-3 justify-end">
              <button class="btn btn-sm btn-primary btn-block">View All</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Avatar dropdown -->
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="avatar btn btn-ghost btn-circle">
          <div class="w-7 rounded-full ring-2 ring-base-300/60">
            <img src="{{ asset('profile.png') }}" />
          </div>
        </div>
        <ul
          class="menu dropdown-content mt-4 p-2 shadow bg-base-100 rounded-box w-56 z-30 border border-base-300/60">
          <li>HI Hana</li>
          <form action="" method="post">
            @csrf
            <li>
              <input type="submit" class="font-bold text-error" value="Logout">
            </li>
          </form>
        </ul>
      </div>

    </div>
  </div>
</header>
