<header class="bg-white shadow-sm border-b">

    <div class="flex items-center justify-between px-8 py-4">

        <div>

            <h2 class="text-2xl font-bold text-slate-800">
                Dashboard
            </h2>

            <p class="text-sm text-slate-500">
                Daily Report Department Instrument & Control System
            </p>

        </div>

        <div class="flex items-center gap-4">

            <button class="relative">

                🔔

                <span
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    3
                </span>

            </button>

            <div class="text-right">

                <div class="font-semibold">
                    {{ Auth::user()->name }}
                </div>

                <div class="text-sm text-slate-500">
                    Supervisor
                </div>

            </div>

            <div
                class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                {{ strtoupper(substr(Auth::user()->name,0,1)) }}

            </div>

        </div>

    </div>

</header>