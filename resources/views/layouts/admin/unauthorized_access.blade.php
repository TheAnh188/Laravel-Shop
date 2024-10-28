@cannot('accessibility', $authorization['canonical'])
    @if (isset($authorization['canonical']))
        <div class="flex justify-center items-center flex-col mt-3">
            <img src="{{ asset('images/empty.svg') }}" alt="" class="w-[40%] h-[40%]">
            <h3 class="mt-3 text-xl font-semibold text-blue-700">{{ $authorization['message'] }}</h3>
        </div>
    @endif
@endcannot
