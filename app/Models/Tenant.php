<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Beritahu Eloquent bahwa primary key bukan auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Beritahu Eloquent bahwa tipe primary key adalah string.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Daftarkan kolom kustom Anda di sini.
     * Ini memberitahu stancl/tenancy untuk memperlakukan kolom-kolom ini
     * sebagai kolom asli, bukan memasukkannya ke dalam 'data' (JSON).
     * @return array
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
        ];
    }

    /**
     * Atribut yang dapat diisi (mass assignable).
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Atribut yang akan otomatis di-cast ke tipe data lain.
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
}
