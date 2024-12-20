<?php

namespace App\Models\AI;


use App\Models\User as User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedContent extends Model
{
    use HasFactory, SoftDeletes ;


    // explicitly define tables
    protected $table = 'uploaded_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'group_id',
        'is_template',
        'label',
        'description'
    ];


     // relationship between pages and page_pages
     public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }


}
