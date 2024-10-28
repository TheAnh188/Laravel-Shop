<!-- Desktop Header -->
<header class="w-full items-center bg-gray-200 py-2 px-6 hidden sm:flex">
    <div class="w-1/2 h-full ">

    </div>
    <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end ">
        <div class="flex items-center">
            @foreach ($languagess as $key => $value)
                <div class="{{ $value->current == 1 ? 'bg-[#718ceb]' : '' }} mr-3 w-[50px] h-[40px] justify-center flex items-center">
                    <a href="/language/{{ $value->id }}/change" class="inline-block w-[30px] h-[20px] object-cover ">
                        <img class="h-full w-full" src="{{ $value->image }}" alt="">
                    </a>
                </div>
            @endforeach
        </div>
        <button @click="isOpen = !isOpen"
            class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
            <img src="https://source.unsplash.com/uJ8LNVCBjFQ/400x400">
        </button>
        <button x-show="isOpen" @click="isOpen = false"
            class="h-full w-full fixed inset-0 cursor-default"></button>
        <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
            <a href="#" class="block px-4 py-2 account-link hover:text-white">Account</a>
            <a href="#" class="block px-4 py-2 account-link hover:text-white">Support</a>
            <a href="/auth/logout" class="block px-4 py-2 account-link hover:text-white">Sign Out</a>
        </div>
    </div>
</header>
