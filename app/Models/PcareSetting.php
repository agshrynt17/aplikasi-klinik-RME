<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcareSetting extends Model
{
    protected $table = 'pcare_settings';

    protected $fillable = [
        'nama_setting',
        'nilai_setting',
        'deskripsi',
        'can_edit',
        'is_secret',
    ];

    protected $casts = [
        'can_edit'  => 'boolean',
        'is_secret' => 'boolean',
    ];

    /**
     * Nilai yang ditampilkan — secret fields dimask
     */
    public function getDisplayValueAttribute(): string
    {
        if ($this->is_secret && !empty($this->nilai_setting)) {
            $val = $this->nilai_setting;
            // Tampilkan 2 karakter awal + mask sisanya
            return substr($val, 0, 2) . str_repeat('*', max(4, strlen($val) - 2));
        }
        return $this->nilai_setting ?? '';
    }
}

