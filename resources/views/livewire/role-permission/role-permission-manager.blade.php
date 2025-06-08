<div>
    <div class="p-6 sm:p-8 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6">Manajemen Role & Permission</h1>

            <!-- Notifikasi -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- SECTION: ROLES -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Manajemen Role</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Buat, edit, hapus, dan atur permission untuk setiap role.</p>
                </div>
                <div class="p-6">
                    <button wire:click="createRole()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out mb-4">
                        Buat Role Baru
                    </button>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permissions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @forelse ($role->permissions->pluck('name') as $permission)
                                            <span class="inline-block bg-gray-200 dark:bg-gray-600 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-200 mr-2 mb-2">{{ $permission }}</span>
                                        @empty
                                            <span class="text-gray-400 dark:text-gray-500">Tidak ada permission</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="managePermissions({{ $role->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Permissions</button>
                                        <button wire:click="editRole({{ $role->id }})" class="ml-4 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                                        <button wire:click="deleteRole({{ $role->id }})" wire:confirm="Anda yakin ingin menghapus role ini?" class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada role.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $roles->links() }}</div>
                </div>
            </div>

            <!-- SECTION: PERMISSIONS -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Manajemen Permission</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Buat, edit, dan hapus permission yang akan digunakan.</p>
                </div>
                <div class="p-6">
                    <button wire:click="createPermission()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out mb-4">
                        Buat Permission Baru
                    </button>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permission</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($permissions as $permission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $permission->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="editPermission({{ $permission->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</button>
                                        <button wire:click="deletePermission({{ $permission->id }})" wire:confirm="Anda yakin ingin menghapus permission ini?" class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada permission.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $permissions->links() }}</div>
                </div>
            </div>

            <!-- SECTION: USERS & ROLES -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Assign Role ke User</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Atur role untuk setiap user yang terdaftar.</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }} ({{ $user->email }})</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @forelse ($user->getRoleNames() as $roleName)
                                            <span class="inline-block bg-blue-200 dark:bg-blue-800 rounded-full px-3 py-1 text-sm font-semibold text-blue-800 dark:text-blue-200 mr-2 mb-2">{{ $roleName }}</span>
                                        @empty
                                            <span class="text-gray-400 dark:text-gray-500">Tidak ada role</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="manageRoles({{ $user->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Manage Roles</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada user.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>

            <!-- Semua Modal diletakkan di sini -->
            <!-- Modal untuk Role -->
            <x-dialog-modal wire:model.live="isRoleModalOpen">
                <x-slot name="title">
                    {{ $roleId ? 'Edit Role' : 'Buat Role Baru' }}
                </x-slot>
                <x-slot name="content">
                    <div class="mt-4">
                        <x-label for="roleName" value="{{ __('Nama Role') }}" />
                        <x-input id="roleName" type="text" class="mt-1 block w-full" wire:model="roleName" />
                        <x-input-error for="roleName" class="mt-2" />
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button wire:click="closeRoleModal" wire:loading.attr="disabled">
                        Batal
                    </x-secondary-button>
                    <x-button class="ms-3" wire:click="storeRole" wire:loading.attr="disabled">
                        Simpan
                    </x-button>
                </x-slot>
            </x-dialog-modal>

            <!-- Modal untuk Permission -->
            <x-dialog-modal wire:model.live="isPermissionModalOpen">
                <x-slot name="title">
                    {{ $permissionId ? 'Edit Permission' : 'Buat Permission Baru' }}
                </x-slot>
                <x-slot name="content">
                    <div class="mt-4">
                        <x-label for="permissionName" value="{{ __('Nama Permission') }}" />
                        <x-input id="permissionName" type="text" class="mt-1 block w-full" wire:model="permissionName" />
                        <x-input-error for="permissionName" class="mt-2" />
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button wire:click="closePermissionModal" wire:loading.attr="disabled">
                        Batal
                    </x-secondary-button>
                    <x-button class="ms-3" wire:click="storePermission" wire:loading.attr="disabled">
                        Simpan
                    </x-button>
                </x-slot>
            </x-dialog-modal>

            <!-- Modal untuk Assign Permission to Role -->
            <x-dialog-modal wire:model.live="isPermissionAssignmentModalOpen">
                <x-slot name="title">
                    Assign Permissions ke Role: {{ $managingPermissionsForRole?->name }}
                </x-slot>
                <x-slot name="content">
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                        @forelse ($allPermissions as $permission)
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" wire:model="rolePermissions" value="{{ $permission->name }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                            </label>
                        @empty
                            <p class="dark:text-gray-300">Tidak ada permission yang tersedia.</p>
                        @endforelse
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button wire:click="closePermissionAssignmentModal" wire:loading.attr="disabled">
                        Batal
                    </x-secondary-button>
                    <x-button class="ms-3" wire:click="syncPermissions" wire:loading.attr="disabled">
                        Update Permissions
                    </x-button>
                </x-slot>
            </x-dialog-modal>

            <!-- Modal untuk Assign Role to User -->
            <x-dialog-modal wire:model.live="isRoleAssignmentModalOpen">
                <x-slot name="title">
                    Assign Roles ke User: {{ $managingRolesForUser?->name }}
                </x-slot>
                <x-slot name="content">
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                        @forelse ($allRoles as $role)
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" wire:model="userRoles" value="{{ $role->name }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                            </label>
                        @empty
                            <p class="dark:text-gray-300">Tidak ada role yang tersedia.</p>
                        @endforelse
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button wire:click="closeRoleAssignmentModal" wire:loading.attr="disabled">
                        Batal
                    </x-secondary-button>
                    <x-button class="ms-3" wire:click="syncRoles" wire:loading.attr="disabled">
                        Update Roles
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
