<?php

namespace App\modules\AVURP\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Class VDPInfoRequest
 * @package App\modules\AVURP\Requests
 */
class VDPInfoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()){
            case 'POST':
                return [
                    'ansar_name_bng'=>'required',
                    'ansar_name_eng'=>'required',
                    'father_name_bng'=>'required',
                    'mother_name_bng'=>'required',
                    'designation'=>'required',
                    'date_of_birth'=>'required',
                    'marital_status'=>'required',
                    'national_id_no'=>'required',
                    'mobile_no_self'=>'required|unique:avurp.avurp_vdp_ansar_info',
                    'height_feet'=>'required',
                    'height_inch'=>'required',
                    'blood_group_id'=>'required',
                    'gender'=>'required',
                    'health_condition'=>'required',
                    'division_id'=>'required',
                    'unit_id'=>'required',
                    'thana_id'=>'required',
                    'union_id'=>'required',
                    'union_word_id'=>'required',
                    'smart_card_id'=>'sometimes|exists:hrm.tbl_ansar_parsonal_info,ansar_id|unique:avurp.avurp_vdp_ansar_info',
                    'post_office_name'=>'required',
                    'village_house_no'=>'required',
                    'educationInfo'=>'required',
                    'training_info'=>'required',
                    'educationInfo.*.education_id'=>'required',
                    'educationInfo.*.institute_name'=>'required',
                    'training_info.*.training_id'=>'required',
                    'training_info.*.sub_training_id'=>'required',

                ];
            case 'PATCH':
                return [
                    'ansar_name_bng'=>'required',
                    'ansar_name_eng'=>'required',
                    'father_name_bng'=>'required',
                    'mother_name_bng'=>'required',
                    'designation'=>'required',
                    'date_of_birth'=>'required',
                    'marital_status'=>'required',
                    'national_id_no'=>'required',
                    'mobile_no_self'=>'required|unique:avurp.avurp_vdp_ansar_info,mobile_no_self,'.$this->route()->parameters()['info'],
                    'height_feet'=>'required',
                    'height_inch'=>'required',
                    'blood_group_id'=>'required',
                    'gender'=>'required',
                    'health_condition'=>'required',
                    'division_id'=>'required',
                    'unit_id'=>'required',
                    'thana_id'=>'required',
                    'union_id'=>'required',
                    'union_word_id'=>'required',
                    'smart_card_id'=>'sometimes|exists:hrm.tbl_ansar_parsonal_info,ansar_id|unique:avurp.avurp_vdp_ansar_info,smart_card_id,'.$this->route()->parameters()['info'],
                    'post_office_name'=>'required',
                    'village_house_no'=>'required',
                    'educationInfo'=>'required',
                    'training_info'=>'required',
                    'educationInfo.*.education_id'=>'required',
                    'educationInfo.*.institute_name'=>'required',
                    'training_info.*.training_id'=>'required',
                    'training_info.*.sub_training_id'=>'required',
                ];
        }
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'educationInfo.*.education_id.required'=>'This field required',
            'educationInfo.*.institute_name.required'=>'This field required',
            'training_info.*.training_id.required'=>'This field required',
            'training_info.*.sub_training_id.required'=>'This field required',
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json($errors,JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }


}