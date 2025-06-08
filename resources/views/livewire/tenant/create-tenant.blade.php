<div>
    <div class="p-6 sm:p-8 bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-2">Buat Tim/Tenant Baru</h1>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-6">Langkah terakhir untuk memulai petualangan Anda.</p>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span>{{ session('message') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="create">
                <div class="space-y-6">
                    <div>
                        <label for="companyName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Perusahaan/Tim</label>
                        <input type="text" id="companyName" wire:model.live="companyName" placeholder="Contoh: Perusahaan Maju Jaya"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('companyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="domainName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Tim Anda</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" id="domainName" wire:model="domainName" placeholder="perusahaan-maju-jaya"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 text-sm">
                                .{{ config('tenancy.central_domains')[0] }}
                            </span>
                        </div>
                        @error('domainName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out disabled:opacity-50">
                        <span wire:loading.remove>Buat Tim Saya</span>
                        <span wire:loading>Membuat tim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('tenant-created', (event) => {
            // Ambil URL dari data event dan arahkan browser ke sana.
            window.location.href = event.url;
        });
    });
</script>
