<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTimeInterface;

class FeedbackAns extends Model
{
    protected $table = "feedback_ans";
    protected $primaryKey = "id";
    protected $fillable = ['event_id', 'user_id', 'ans'];

    public function user(){
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function event(){
    	return $this->hasOne(events::class, 'id', 'event_id');
    }
    
    public function getTableColumns()
    {
        $qry = "SELECT column_name
            FROM information_schema.columns
            WHERE table_name = 'event_feedback_ans'
            AND table_schema = 'kuchingi_eventsandbox'";

        $result = DB::select($qry);

        dd($result);
    }
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
