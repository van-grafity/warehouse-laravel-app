<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Traits\UserRecords;


class Packinglist extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'serial_number',
        'invoice_id',
        'buyer',
        'gl_number',
        'po_number',
        'color_id',
        'batch_number',
        'style',
        'fabric_content',
        'remark',
    ];

    protected static function booted(): void
    {
        static::deleted(function (Packinglist $packinglist) {
            $fabric_rolls = $packinglist->fabric_rolls;
            foreach ($fabric_rolls as $key => $roll) {
                $roll->delete();
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function fabric_rolls()
    {
        return $this->hasMany(FabricRoll::class,'packinglist_id','id');
    }
    

    public function countPackinglistThisMonth($year_filter = null, $month_filter = null)
    {
        $month_filter = $month_filter ? $month_filter : date('m');
        $year_filter = $year_filter ? $year_filter : date('Y');

        $result = Packinglist::select('id')
                ->whereYear("created_at", $year_filter)
                ->whereMonth("created_at", $month_filter)
                ->withTrashed()
                ->get();

        return $result->count();
    }

    public function generate_serial_number($color_code)
    {
        // ## GET total Packinglist this month
        $packinglist_this_month = $this->countPackinglistThisMonth();
        $next_number = $packinglist_this_month + 1;
        
        $serial_number = 'WHA-PL-' . generateRandomString(4). '-' . $color_code . '-' . date('ym') . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
        return $serial_number;
    }

    public function getOrCreateDataByName(Array $data_to_insert)
    {
        $builder = $this;
        $batch = $data_to_insert['batch'];
        $color_id = Color::where('color',$data_to_insert['color'])->first()->id;
        
        $get_packinglist = $builder->where('batch_number', $batch)->where('color_id', $color_id)->first();
        if(!$get_packinglist){
            $invoice = Invoice::where('invoice_number',$data_to_insert['invoice'])->first();
            $color = Color::where('color',$data_to_insert['color'])->first();

            $packinglist_model = Packinglist::create([
                'serial_number' => $this->generate_serial_number($color->code),
                'invoice_id'=> $invoice->id,
                'buyer'=> $data_to_insert['buyer'],
                'gl_number'=> $data_to_insert['gl_number'],
                'po_number'=> $data_to_insert['po_number'],
                'batch_number'=> $data_to_insert['batch'],
                'style'=> $data_to_insert['style'],
                'fabric_content'=> $data_to_insert['fabric_content'],
                'color_id'=> $color->id,
            ]);
            $packinglist_id = $packinglist_model->id;
        } else {
            $packinglist_id = $get_packinglist->id;
        }
        return $packinglist_id;
    }

    public function getRollSummaryInPackinglist($packinglist_id, $summary_option = null)
    {
        $result = DB::table('packinglists')->join('fabric_rolls','fabric_rolls.packinglist_id' ,'=', 'packinglists.id')
            ->when($summary_option, function ($query, string $summary_option) {
                if($summary_option == 'stock_in') {
                    $query->join('fabric_roll_racks','fabric_roll_racks.fabric_roll_id','=','fabric_rolls.id');
                }
            })
            ->where('fabric_rolls.deleted_at', null)
            ->where('packinglists.id', $packinglist_id)
            ->groupBy('packinglists.id')
            ->select(DB::raw('
                count(fabric_rolls.id) as total_roll, 
                SUM(fabric_rolls.yds) as total_length_yds, 
                SUM(fabric_rolls.kgs) as total_weight_kgs
            '))
            ->first();

        return $result;
    }
    
}
