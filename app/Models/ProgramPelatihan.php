<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramPelatihan extends Model
{
    protected $table = 'program_pelatihan';

    public $timestamps = false;

    protected $fillable = [
        'nama_program',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(
            Peserta::class,
            'program_pelatihan_id'
        );
    }
}
