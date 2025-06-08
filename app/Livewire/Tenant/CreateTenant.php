<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Tenant;

class CreateTenant extends Component
{
    public string $companyName = '';
    public string $domainName = '';

    // Properti baru untuk menampung URL redirect
    public ?string $redirectUrl = null;

    protected function rules()
    {
        return [
            'companyName' => 'required|string|min:3|max:100',
            'domainName' => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[a-z0-9\-]+$/i',
                Rule::unique('tenants', 'id'),
            ],
        ];
    }

    public function updatedCompanyName($value)
    {
        $this->domainName = Str::slug($value);
    }

    public function create()
    {
        $this->validate();
        $user = Auth::user();

        if ($user->tenant_id) {
            session()->flash('error', 'Anda sudah menjadi bagian dari sebuah tim/tenant.');
            return;
        }

        $tenant = Tenant::create([
            'id'   => $this->domainName,
            'name' => $this->companyName,
        ]);

        $domain = $this->domainName . '.' . config('tenancy.central_domains')[0];
        $tenant->domains()->create(['domain' => $domain]);

        $user->tenant_id = $tenant->id;
        $user->save();

        $tenant->run(function () {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--database' => 'tenant',
                '--force' => true,
            ]);
        });

        session()->flash('message', 'Tim/Tenant berhasil dibuat! Anda akan dialihkan.');

        // INI PERUBAHANNYA: Isi properti publik dengan URL tujuan
        $this->redirectUrl = 'http://' . $domain . '/dashboard';
    }

    public function render()
    {
        return view('livewire.tenant.create-tenant')->layout('layouts.app');
    }
}
