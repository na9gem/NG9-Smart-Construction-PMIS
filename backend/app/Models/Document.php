<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;


    /**
 * Mass Assignable
 */
protected $fillable = [

    'project_id',

    'contract_id',

    'document_type',

    'document_name',

    'file_name',

    'file_path',

    'file_size',

    'mime_type',

    'revision',

    'remark',

    'uploaded_by',

    'document_no',

    'document_date',

    'uploaded_at',

    'file_extension',

    'ai_summary',

    'tags',

    'status',

];

/**
 * Data Type Casting
 */
protected $casts = [

    'file_size' => 'integer',

    'document_date' => 'date',

    'uploaded_at' => 'datetime',

    'tags' => 'array',

];



/**
 * Relationship : Document belongs to Project
 */
public function project()
{
    return $this->belongsTo(Project::class);
}

/**
 * Relationship : Document belongs to Contract
 */
public function contract()
{
    return $this->belongsTo(Contract::class);
}

/**
 * Relationship : Document belongs to User
 */
public function uploader()
{
    return $this->belongsTo(User::class, 'uploaded_by');
}

}