<?php

namespace App\modules\SD\Models;

use App\modules\HRM\Models\KpiGeneralModel;
use App\modules\HRM\Models\PersonalInfo;
use Illuminate\Database\Eloquent\Model;

class CashDeposite extends Model
{

    protected $connection = 'sd';
    protected $table = 'tbl_cash_deposit';
    protected $guarded = ['id'];

    public function kpi(){
        return $this->belongsTo(KpiGeneralModel::class,'kpi_id');
    }
    public function demand(){
        return $this->belongsTo(DemandLog::class,'demand_or_salary_sheet_id')->where('payment_against','demand_sheet');
    }
    public function salarySheet(){
        return $this->belongsTo(SalarySheetHistory::class,'demand_or_salary_sheet_id')->where('payment_against','salary_sheet');
    }
}
