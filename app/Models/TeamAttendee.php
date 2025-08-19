<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class TeamAttendee extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'event_id', 'event_date', 'team_name', 'team_leader', 'team_member_1', 'team_member_2', 'team_member_3', 'team_member_4', 'team_member_5'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Method to retrieve all team members as an array
    public function getMembers()
    {
        return [
            $this->team_member_1,
            $this->team_member_2,
            $this->team_member_3,
            $this->team_member_4,
            $this->team_member_5,
        ];
    }
}
