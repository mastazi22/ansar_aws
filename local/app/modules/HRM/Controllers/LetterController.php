<?php

namespace App\modules\HRM\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\modules\HRM\Models\MemorandumModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class LetterController extends Controller
{
    //
    function transferLetterView()
    {
        return View::make('HRM::Letter.transfer_letter');
    }

    function getMemorandumIds(Request $requests)
    {
        $t = DB::table('tbl_memorandum_id')
            ->join('tbl_transfer_ansar', 'tbl_transfer_ansar.transfer_memorandum_id', '=', 'tbl_memorandum_id.memorandum_id')
            ->select('tbl_memorandum_id.*')->distinct();
        $e = DB::table('tbl_memorandum_id')
            ->join('tbl_embodiment', 'tbl_embodiment.memorandum_id', '=', 'tbl_memorandum_id.memorandum_id')
            ->select('tbl_memorandum_id.*')->distinct();
        $d = DB::table('tbl_memorandum_id')
            ->join('tbl_rest_info', 'tbl_rest_info.memorandum_id', '=', 'tbl_memorandum_id.memorandum_id')
            ->select('tbl_memorandum_id.*')->distinct();

        switch ($requests->type) {
            case 'TRANSFER':
                return $t->get();
            case 'EMBODIED':
                return $e->get();
            case 'DISEMBODIED':
                return $d->get();
            default:
                return [];
        }

    }

    function printLetter()
    {
        $id = Input::get('id');
        $type = Input::get('type');
        $unit = Input::get('unit');
        $view = Input::get('view');
        $rules = [
            'id' => 'regex:/[^<>"]+$/',
            'type' => 'regex:/^[A-Z]+$/',
            'unit' => 'numeric|regex:/^[0-9]+$/',
        ];
        $valid = Validator::make(Input::all(), $rules);

        if ($valid->fails()) {
            //return print_r($valid->messages());
            return response("Invalid Request(400)", 400);
        }
        switch ($type) {
            case 'TRANSFER':
                return $this->transferLetterPrint($id, $unit, $view);
            case 'EMBODIMENT':
                return $this->embodimentLetterPrint($id, $unit, $view);
            case 'DISEMBODIMENT':
                return $this->disembodimentLetterPrint($id, $unit, $view);
        }
    }

    function transferLetterPrint($id, $unit, $v)
    {
        $mem = MemorandumModel::where('memorandum_id', $id)->select('memorandum_id as transfer_memorandum_id', 'mem_date as created_at')->first();
        //$mem = TransferAnsar::where('transfer_memorandum_id', $id)->select('transfer_memorandum_id', 'created_at')->first();

        $user = DB::table('tbl_user')
            ->join('tbl_user_details', 'tbl_user_details.user_id', '=', 'tbl_user.id')
            ->join('tbl_units', 'tbl_units.id', '=', 'tbl_user.district_id')
            ->join('tbl_division', 'tbl_units.division_id', '=', 'tbl_division.id')
            ->where('tbl_user.district_id', $unit)->select('tbl_user_details.first_name', 'tbl_user_details.last_name', 'tbl_user_details.mobile_no', 'tbl_user_details.email', 'tbl_units.unit_name_bng as unit','tbl_division.division_name_eng as division')->first();
        $result = DB::table('tbl_transfer_ansar')
            ->join('tbl_kpi_info as pk', 'tbl_transfer_ansar.present_kpi_id', '=', 'pk.id')
            ->join('tbl_kpi_info as tk', 'tbl_transfer_ansar.transfered_kpi_id', '=', 'tk.id')
            ->join('tbl_ansar_parsonal_info', 'tbl_transfer_ansar.ansar_id', '=', 'tbl_ansar_parsonal_info.ansar_id')
            ->join('tbl_designations', 'tbl_designations.id', '=', 'tbl_ansar_parsonal_info.designation_id')
            ->where('tbl_transfer_ansar.transfer_memorandum_id', $id)
            ->select('tbl_ansar_parsonal_info.ansar_id as ansar_id', 'tbl_ansar_parsonal_info.ansar_name_bng as name', 'tbl_ansar_parsonal_info.father_name_bng as father_name', 'tbl_designations.name_bng as rank', 'pk.kpi_name as p_kpi_name', 'tk.kpi_name as t_kpi_name')->get();
        if ($mem && $result) {
            if ($v == "full") return View::make('HRM::Letter.master')->with(['mem' => $mem, 'user' => $user, 'ta' => $result, 'view' => 'print_transfer_letter']);
            else return View::make('HRM::Letter.print_transfer_letter')->with(['mem' => $mem, 'user' => $user, 'ta' => $result]);
        } else {
            return View::make('HRM::Letter.no_mem_found')->with(['id' => $id]);
        }
    }

    function embodimentLetterPrint($id, $unit, $v)
    {
        $mem = MemorandumModel::where('memorandum_id', $id)->select('memorandum_id', 'mem_date as created_at')->first();
        //$mem = EmbodimentModel::where('memorandum_id', $id)->select('memorandum_id', 'created_at')->first();
        $user = DB::table('tbl_user')
            ->join('tbl_user_details', 'tbl_user_details.user_id', '=', 'tbl_user.id')
            ->join('tbl_units', 'tbl_units.id', '=', 'tbl_user.district_id')
            ->join('tbl_division', 'tbl_units.division_id', '=', 'tbl_division.id')
            ->where('tbl_user.district_id', $unit)->select('tbl_user_details.first_name', 'tbl_user_details.last_name', 'tbl_user_details.mobile_no', 'tbl_user_details.email', 'tbl_units.unit_name_bng as unit','tbl_division.division_name_eng as division')->first();
        $result = DB::table('tbl_embodiment')
            ->join('tbl_kpi_info', 'tbl_kpi_info.id', '=', 'tbl_embodiment.kpi_id')
            ->join('tbl_ansar_parsonal_info', 'tbl_embodiment.ansar_id', '=', 'tbl_ansar_parsonal_info.ansar_id')
            ->join('tbl_units', 'tbl_units.id', '=', 'tbl_ansar_parsonal_info.unit_id')
            ->join('tbl_thana', 'tbl_thana.id', '=', 'tbl_ansar_parsonal_info.thana_id')
            ->join('tbl_designations', 'tbl_designations.id', '=', 'tbl_ansar_parsonal_info.designation_id')
            ->where('tbl_embodiment.memorandum_id', $id)
            ->select('tbl_ansar_parsonal_info.ansar_id as ansar_id', 'tbl_ansar_parsonal_info.ansar_name_bng as name', 'tbl_ansar_parsonal_info.father_name_bng as father_name', 'tbl_designations.name_bng as rank', 'tbl_kpi_info.kpi_name as kpi_name', 'tbl_ansar_parsonal_info.village_name as village_name', 'tbl_ansar_parsonal_info.post_office_name as pon', 'tbl_units.unit_name_bng as unit', 'tbl_thana.thana_name_eng as thana', 'tbl_embodiment.joining_date')->get();
        if ($mem && $result) {
            if ($v == "full") return View::make('HRM::Letter.master')->with(['mem' => $mem, 'user' => $user, 'result' => $result, 'view' => 'print_embodiment_letter']);
            else return View::make('HRM::Letter.print_embodiment_letter')->with(['result' => $result, 'user' => $user, 'mem' => $mem]);
        } else {
            return View::make('HRM::Letter.no_mem_found')->with('id', $id);
        }
    }

    function disembodimentLetterPrint($id, $unit, $v)
    {
        DB::enableQueryLog();
        $mem = DB::table('tbl_rest_info')
            ->join('tbl_memorandum_id', 'tbl_memorandum_id.memorandum_id', '=', 'tbl_rest_info.memorandum_id')->join('tbl_disembodiment_reason', 'tbl_disembodiment_reason.id', '=', 'tbl_rest_info.disembodiment_reason_id')->where('tbl_memorandum_id.memorandum_id', $id)->select('tbl_disembodiment_reason.reason_in_bng as reason', 'tbl_memorandum_id.memorandum_id', 'tbl_memorandum_id.mem_date as created_at')->first();
        //return Response::json($mem);
        $user = DB::table('tbl_user')
            ->join('tbl_user_details', 'tbl_user_details.user_id', '=', 'tbl_user.id')
            ->join('tbl_units', 'tbl_units.id', '=', 'tbl_user.district_id')
            ->join('tbl_division', 'tbl_units.division_id', '=', 'tbl_division.id')
            ->where('tbl_user.district_id', $unit)->select('tbl_user_details.first_name', 'tbl_user_details.last_name', 'tbl_user_details.mobile_no', 'tbl_user_details.email', 'tbl_units.unit_name_bng as unit','tbl_division.division_name_eng as division')->first();
        $result = DB::table('tbl_embodiment_log')
            ->join('tbl_rest_info', 'tbl_rest_info.ansar_id', '=', 'tbl_embodiment_log.ansar_id')
            ->join('tbl_kpi_info', 'tbl_kpi_info.id', '=', 'tbl_embodiment_log.kpi_id')
            ->join('tbl_ansar_parsonal_info', 'tbl_embodiment_log.ansar_id', '=', 'tbl_ansar_parsonal_info.ansar_id')
            ->join('tbl_units', 'tbl_units.id', '=', 'tbl_ansar_parsonal_info.unit_id')
            ->join('tbl_thana', 'tbl_thana.id', '=', 'tbl_ansar_parsonal_info.thana_id')
            ->join('tbl_designations', 'tbl_designations.id', '=', 'tbl_ansar_parsonal_info.designation_id')
            ->where('tbl_rest_info.memorandum_id', $id)->distinct('tbl_embodiment_log.ansar_id')
            ->select('tbl_ansar_parsonal_info.ansar_id as ansar_id', 'tbl_ansar_parsonal_info.ansar_name_bng as name', 'tbl_ansar_parsonal_info.father_name_bng as father_name', 'tbl_designations.name_bng as rank', 'tbl_kpi_info.kpi_name as kpi_name', 'tbl_ansar_parsonal_info.village_name as village_name', 'tbl_ansar_parsonal_info.post_office_name as pon', 'tbl_units.unit_name_bng as unit', 'tbl_thana.thana_name_eng as thana', 'tbl_embodiment_log.joining_date', 'tbl_embodiment_log.release_date')->get();
//        return $result;
       // return DB::getQueryLog();
        if ($mem && $result) {
            if ($v == "full") return View::make('HRM::Letter.master')->with(['mem' => $mem, 'user' => $user, 'result' => $result, 'view' => 'print_disembodiment_letter']);
            else return View::make('HRM::Letter.print_disembodiment_letter')->with(['result' => $result, 'user' => $user, 'mem' => $mem]);
        } else {
            return View::make('HRM::Letter.no_mem_found')->with('id', $id);
        }
    }

    function embodimentLetterView()
    {
        return View::make('HRM::Letter.embodiment_letter');
    }

    function disembodimentLetterView()
    {
        return View::make('HRM::Letter.disembodiment_letter');
    }
}
