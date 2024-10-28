<a id="dropdownDefaultButton" href="javascript:void(0)"
                class="h-10 inline-block bg-green-500 text-white px-3 py-2.5 rounded-md relative top-[2px] -left-3"><i
                    class="fa-regular fa-floppy-disk"></i></a>
<div id="dropdown"
    class="mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-full dark:bg-gray-700 transition-all">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
        <li>
            <a href="javascript:void(0)" data-field="status" data-model="{{ $model }}" data-value="1"
                class="setStatusAll block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Kích
                hoạt các tài khoản</a>
        </li>
        <li>
            <a href="javascript:void(0)" data-field="status" data-model="{{ $model }}" data-value="2"
                class="setStatusAll block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Vô
                hiệu hóa các tài khoản</a>
        </li>

    </ul>
</div>
