<x-layout-admin>
    <x-slot name="header"></x-slot>

    <html>
    <head>
        <title>Manajemen Data Siswa</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    </head>
    <body class="bg-gray-100">
        <div class="flex h-screen">
            <div class="p-8 flex-grow">
                <div class="bg-white p-6 rounded shadow-md">
                    <div class="text-xl font-bold mb-4">Import Data Siswa</div>

                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="bg-green-200 text-green-700 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-200 text-red-700 p-4 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('admin.import-data-siswa') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="border border-gray-300 p-4 rounded mb-4">
                            <div class="flex items-center">
                                <label class="relative">
                                    <input type="file" name="file" class="absolute inset-0 opacity-0 w-full cursor-pointer">
                                    <span class="bg-green-600 text-white px-4 py-2 rounded inline-block">Pilih File</span>
                                </label>
                                <input class="border border-gray-300 p-2 flex-grow ml-4" placeholder="Tidak Ada File Yang Dipilih" readonly type="text" id="file-name-display"/>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('admin.manage-data-siswa') }}">
                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded mr-4">Kembali</button>
                            </a>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

    <script>
        const fileInput = document.querySelector('input[type="file"]');
        const fileNameDisplay = document.getElementById('file-name-display');

        fileInput.addEventListener('change', (event) => {
            const fileName = event.target.files[0]?.name || 'Tidak Ada File Yang Dipilih';
            fileNameDisplay.value = fileName;
        });
    </script>

    </html>
</x-layout-admin>
