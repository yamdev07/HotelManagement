<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class FloorPlan extends Model
{
    use \App\Models\Concerns\BelongsToHotel;
    protected $fillable = ['room_id', 'layout', 'updated_by'];
 
    protected $casts = ['layout' => 'array'];
 
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
 
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
