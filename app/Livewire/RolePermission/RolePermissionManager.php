<?php

namespace App\Livewire\RolePermission;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManager extends Component
{
    use WithPagination;

    public ?int $roleId = null;
    public string $roleName = '';
    public bool $isRoleModalOpen = false;
    public ?int $permissionId = null;
    public string $permissionName = '';
    public bool $isPermissionModalOpen = false;
    public ?Role $managingPermissionsForRole = null;
    public array $rolePermissions = [];
    public bool $isPermissionAssignmentModalOpen = false;
    public ?User $managingRolesForUser = null;
    public array $userRoles = [];
    public bool $isRoleAssignmentModalOpen = false;

    protected function rules()
    {
        if ($this->isRoleModalOpen) {
            return [
                'roleName' => [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique('roles', 'name')->ignore($this->roleId)
                ]
            ];
        }
        if ($this->isPermissionModalOpen) {
            return [
                'permissionName' => [
                    'required',
                    'string',
                    'min:3',
                    Rule::unique('permissions', 'name')->ignore($this->permissionId)
                ]
            ];
        }
        return [];
    }

    public function render()
    {
        return view('livewire.role-permission.role-permission-manager', [
            'roles' => Role::with('permissions')->latest()->paginate(5, ['*'], 'rolesPage'),
            'permissions' => Permission::latest()->paginate(5, ['*'], 'permissionsPage'),
            'users' => User::with('roles')->latest()->paginate(5, ['*'], 'usersPage'),
            'allPermissions' => Permission::all(),
            'allRoles' => Role::all(),
        ])->layout('layouts.app');
    }

    public function createRole()
    {
        $this->resetRoleForm();
        $this->isRoleModalOpen = true;
    }

    public function editRole(Role $role)
    {
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->isRoleModalOpen = true;
    }

    public function storeRole()
    {
        $this->validate();
        Role::updateOrCreate(['id' => $this->roleId], ['name' => $this->roleName, 'guard_name' => 'web']);
        session()->flash('message', 'Role berhasil disimpan.');
        $this->closeRoleModal();
    }

    public function deleteRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
            return;
        }
        $role->delete();
        session()->flash('message', 'Role berhasil dihapus.');
    }

    public function closeRoleModal()
    {
        $this->isRoleModalOpen = false;
        $this->resetRoleForm();
    }

    private function resetRoleForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->resetErrorBag();
    }

    public function createPermission()
    {
        $this->resetPermissionForm();
        $this->isPermissionModalOpen = true;
    }

    public function editPermission(Permission $permission)
    {
        $this->permissionId = $permission->id;
        $this->permissionName = $permission->name;
        $this->isPermissionModalOpen = true;
    }

    public function storePermission()
    {
        $this->validate();
        Permission::updateOrCreate(['id' => $this->permissionId], ['name' => $this->permissionName, 'guard_name' => 'web']);
        session()->flash('message', 'Permission berhasil disimpan.');
        $this->closePermissionModal();
    }

    public function deletePermission(Permission $permission)
    {
        $permission->delete();
        session()->flash('message', 'Permission berhasil dihapus.');
    }

    public function closePermissionModal()
    {
        $this->isPermissionModalOpen = false;
        $this->resetPermissionForm();
    }

    private function resetPermissionForm()
    {
        $this->permissionId = null;
        $this->permissionName = '';
        $this->resetErrorBag();
    }

    public function managePermissions(Role $role)
    {
        $this->managingPermissionsForRole = $role;
        $this->rolePermissions = $role->permissions->pluck('name')->toArray();
        $this->isPermissionAssignmentModalOpen = true;
    }

    public function syncPermissions()
    {
        if ($this->managingPermissionsForRole) {
            $this->managingPermissionsForRole->syncPermissions($this->rolePermissions);
            session()->flash('message', 'Permissions berhasil di-assign ke role.');
            $this->closePermissionAssignmentModal();
        }
    }

    public function closePermissionAssignmentModal()
    {
        $this->isPermissionAssignmentModalOpen = false;
        $this->managingPermissionsForRole = null;
        $this->rolePermissions = [];
    }

    public function manageRoles(User $user)
    {
        $this->managingRolesForUser = $user;
        $this->userRoles = $user->getRoleNames()->toArray();
        $this->isRoleAssignmentModalOpen = true;
    }

    public function syncRoles()
    {
        if ($this->managingRolesForUser) {
            $this->managingRolesForUser->syncRoles($this->userRoles);
            session()->flash('message', 'Roles berhasil di-assign ke user.');
            $this->closeRoleAssignmentModal();
        }
    }

    public function closeRoleAssignmentModal()
    {
        $this->isRoleAssignmentModalOpen = false;
        $this->managingRolesForUser = null;
        $this->userRoles = [];
    }
}
