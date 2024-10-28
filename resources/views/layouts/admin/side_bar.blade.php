<aside class="relative bg-sidebar h-screen w-96 hidden sm:block shadow-xl">
    <div class="p-6">
        <a href="/dashboard/index" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
        <button
            class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
            <i class="fas fa-plus mr-3"></i> New Report
        </button>
    </div>
    <nav class="text-white text-base font-semibold pt-3">
        @foreach (__('sidebar.module') as $key => $val)
            <div
                class="slide_bar--item select-none {{ in_array(request()->segment(1), $val['name']) ? 'active-nav-link' : '' }}  text-white py-4 pl-6 nav-item">
                <div>
                    <div class="flex justify-between items-center">
                        <div>
                            <i class="{{ $val['icon'] }}"></i>
                            {{ $val['title'] }}
                        </div>
                        <i class="fa-solid fa-angle-down mr-7"></i>
                    </div>
                    @if (isset($val['sub_module']))
                        <ul class="slide_bar--sub-item text-sm mt-3 mb-3 ml-7 hidden transition-all">
                            @foreach ($val['sub_module'] as $sub_module)
                                <li class="mt-2 opacity-75 hover:opacity-100">
                                    <a href="{{ $sub_module['route'] }}">
                                        {{ $sub_module['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
        <a href="/dashboard/tables"
            class="flex items-center {{ request()->is('dashboard/tables') ? 'active-nav-link' : '' }} text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
            <i class="fas fa-table mr-3"></i>
            Tables
        </a>
        <a href="/dashboard/forms"
            class="flex items-center {{ request()->is('dashboard/forms') ? 'active-nav-link' : '' }} text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
            <i class="fas fa-align-left mr-3"></i>
            Forms
        </a>
        <a href="/dashboard/tabs"
            class="flex items-center {{ request()->is('dashboard/tabs') ? 'active-nav-link' : '' }} text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
            <i class="fas fa-tablet-alt mr-3"></i>
            Tabbed Content
        </a>
        <a href="/dashboard/calendar"
            class="flex items-center {{ request()->is('dashboard/calendar') ? 'active-nav-link' : '' }} text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
            <i class="fas fa-calendar mr-3"></i>
            Calendar
        </a>
    </nav>
    <a href="#"
        class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
        <i class="fas fa-arrow-circle-up mr-3"></i>
        Upgrade to Pro!
    </a>
</aside>

<script>
    document.querySelectorAll('.slide_bar--item').forEach(function(item) {
        item.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
</script>
