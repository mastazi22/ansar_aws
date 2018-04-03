<?php
/**
 * Created by PhpStorm.
 * User: shuvo
 * Date: 4/3/2018
 * Time: 1:31 PM
 */

namespace App\modules\AVURP\Repositories\VDPInfo;


use App\Http\Requests\Request;
use App\modules\AVURP\Models\VDPAnsarInfo;
use App\modules\AVURP\Requests\VDPInfoRequest;
use App\modules\HRM\Models\District;
use App\modules\HRM\Models\Division;
use App\modules\HRM\Models\Thana;
use App\modules\HRM\Models\Unions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class VDPInfoRepository implements VDPInfoInterface
{
    public $info;
    /**
     * VDPInfoRepository constructor.
     * @param VDPAnsarInfo $info
     */
    public function __construct(VDPAnsarInfo $info)
    {
        $this->info = $info;
    }


    /**
     * @param VDPInfoRequest $request
     * @return mixed
     */
    public function create($request)
    {
        DB::connection('avurp')->beginTransaction();
        try{

            $division_code = sprintf("%02d",Division::find($request->division_id)->division_code);
            $unit_code = sprintf("%02d",District::find($request->unit_id)->unit_code);
            $thana_code = sprintf("%02d",Thana::find($request->thana_id)->thana_code);
            $union_code = sprintf("%02d",Unions::find($request->union_id)->code);
            $gender_code = $request->gender=='Male'?1:2;
            $word_code = '0'.$request->union_word_id;
            $count = $this->info->where($request->only(['division_id','thana_id','unit_id','union_id','union_word_id']))->count()+1;
            $count = sprintf("%03d",$count);
            $geo_id = $division_code.$unit_code.$thana_code.$union_code.$gender_code.$word_code.$count;
            if($request->hasFile('profile_pic')){
                $file = $request->file('profile_pic');
                $path = storage_path('avurp/profile_pic');
                if(!File::exists($path)) File::makeDirectory($path,777,true);
                $image_name = $geo_id.'.'.$file->clientExtension();
                Image::make($file)->save($path.'/'.$image_name);
            }
            $data = $request->except('educationInfo');
            $data['geo_id'] = $geo_id;
            if(isset($path)&&isset($image_name)) $data['profile_pic'] = $path.'/'.$image_name;
            else if($request->hasFile('profile_pic')) $data['profile_pic']='';
            $info = $this->info->find($request->route()->parameters()['info']);
            $info->update($data);
            $info->education()->delete();
            foreach ($request->educationInfo as $education){
                $info->education()->create($education);
            }
            DB::connection('avurp')->commit();
        }catch(\Exception $e){
            DB::connection('avurp')->rollback();
            if(isset($path)&&isset($image_name)){
                if(File::exists($path.'/'.$image_name)){
                    File::delete($path.'/'.$image_name);
                }
            }
            return ['data'=>['message'=>$e->getMessage()],'status'=>false];
        }
        return ['data'=>['message'=>"data updated successfully"],'status'=>true];
    }

    /**
     * @param $id
     * @return VDPAnsarInfo
     */
    public function getInfo($id)
    {
        return $this->info->with(['division','unit','thana','union','education','education.education','bloodGroup'])->find($id);
    }

    /**
     * @param array $param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getInfos($param = [],$paginate=30)
    {
        $range = $param['range']?$param['range']:'all';
        $unit = $param['unit']?$param['unit']:'all';
        $thana = $param['unit']?$param['unit']:'all';
        $vdp_infos = $this->info->with(['division','unit','thana','union']);
        if($range!='all'){
            $vdp_infos->where('division_id',$range);
        }
        if($unit!='all'){
            $vdp_infos->where('unit_id',$unit);
        }
        if($thana!='all'){
            $vdp_infos->where('thana_id',$thana);
        }
        return $vdp_infos->paginate($paginate);
    }

    /**
     * @param Request $input
     * @return mixed
     */
    public function update($request)
    {

    }
}