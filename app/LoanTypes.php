<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanTypes extends Model
{
    protected $table = 'loantypes';

    public function loans(){
        return $this->belongsTo('App\Loan');
    }
}
