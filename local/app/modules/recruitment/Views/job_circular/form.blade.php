<div ng-controller="jobCircularConstraintController"
     @if(isset($data)&&$data->constraint) ng-init="initConstraint('{{ $data->constraint->constraint}}')" @endif>

    @if(isset($data))
        {!! Form::model($data,['route'=>['recruitment.circular.update',$data],'method'=>'patch']) !!}
    @else
        {!! Form::open(['route'=>'recruitment.circular.store']) !!}
    @endif
    <div class="form-group">
        {!! Form::label('job_category_id','Select Job Category :',['class'=>'control-label']) !!}
        {!! Form::select('job_category_id',$categories,null,['class'=>'form-control']) !!}
        @if(isset($errors)&&$errors->first('job_category_id'))
            <p class="text text-danger">{{$errors->first('job_category_id')}}</p>
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('circular_name','Job Circular Title :',['class'=>'control-label']) !!}
        {!! Form::text('circular_name',null,['class'=>'form-control','placeholder'=>'Enter circular name']) !!}
        @if(isset($errors)&&$errors->first('circular_name'))
            <p class="text text-danger">{{$errors->first('circular_name')}}</p>
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('start_date','Start Date :',['class'=>'control-label']) !!}
        {!! Form::text('start_date',null,['class'=>'form-control','placeholder'=>'Enter Start Date','date-picker'=>(isset($data)?"moment('{$data->start_date}').format('DD-MMM-YYYY')":"moment('".\Carbon\Carbon::parse(Request::old('start_date'))->format('Y-m-d')."').format('DD-MMM-YYYY')")]) !!}
        @if(isset($errors)&&$errors->first('start_date'))
            <p class="text text-danger">{{$errors->first('start_date')}}</p>
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('end_date','End Date :',['class'=>'control-label']) !!}
        {!! Form::text('end_date',null,['class'=>'form-control','placeholder'=>'Enter Start Date','date-picker'=>(isset($data)?"moment('{$data->end_date}').format('DD-MMM-YYYY')":"moment('".\Carbon\Carbon::parse(Request::old('end_date'))->format('Y-m-d')."').format('DD-MMM-YYYY')")]) !!}
        @if(isset($errors)&&$errors->first('end_date'))
            <p class="text text-danger">{{$errors->first('end_date')}}</p>
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('pay_amount','Pay Amount :',['class'=>'control-label']) !!}
        {!! Form::text('pay_amount',null,['class'=>'form-control','placeholder'=>'Pay Amount']) !!}
        @if(isset($errors)&&$errors->first('pay_amount'))
            <p class="text text-danger">{{$errors->first('pay_amount')}}</p>
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('terms_and_conditions','Terms and Conditions :',['class'=>'control-label']) !!}
        {!! Form::textarea('terms_and_conditions',null,['class'=>'form-control','placeholder'=>'','id'=>'terms_and_conditions']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('test','Circular Status : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="running" name="circular_status"
               @if((isset($data)&&$data->circular_status=='running')||Request::old('circular_status')=='running')checked
               @endif id="circular_status" class="switch-checkbox">
        <label for="circular_status" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('test','Status : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="active" name="status"
               @if((isset($data)&&$data->status=='active')||Request::old('status')=='active')checked
               @endif id="status" class="switch-checkbox">
        <label for="status" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('test','Login Status : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="on" name="login_status"
               @if((isset($data)&&$data->login_status=='on')||Request::old('login_status')=='on')checked
               @endif id="login_status" class="switch-checkbox">
        <label for="login_status" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('test','Payment Status : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="on" name="payment_status"
               @if((isset($data)&&$data->payment_status=='on')||Request::old('payment_status')=='on')checked
               @endif id="payment_status" class="switch-checkbox">
        <label for="payment_status" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('test','Application Status : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="on" name="application_status"
               @if((isset($data)&&$data->application_status=='on')||Request::old('application_status')=='on')checked
               @endif id="application_status" class="switch-checkbox">
        <label for="application_status" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('test','Quota applied for all divisions and districts : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="on" name="quota_district_division"
               @if((isset($data)&&$data->quota_district_division=='on')||Request::old('quota_district_division')=='on')checked ng-init="apply_quota=1"
               @endif id="quota_district_division" class="switch-checkbox" ng-model="apply_quota" ng-true-value="1"
               ng-false-value="0">
        <label for="quota_district_division" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('Select applicant division','Select applicant division',['class'=>'control-label']) !!}
        <div class="form-control" style="height: 200px;overflow: auto;">
            <ul>
                @foreach($ranges as $r)
                    <li style="list-style: none">
                        @if(isset($data))
                            {!! Form::checkbox('applicatn_range[]',$r->id,in_array($r->id,explode(',',$data->applicatn_range)),['style'=>'vertical-align:sub','class'=>'range-app']) !!}
                            &nbsp;{{$r->division_name_bng}}
                        @else
                            {!! Form::checkbox('applicatn_range[]',$r->id,true,['style'=>'vertical-align:sub','class'=>'range-app']) !!}
                            &nbsp;{{$r->division_name_bng}}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Select applicant district','Select applicant district',['class'=>'control-label']) !!}
        <div class="form-control" style="height: 200px;overflow: auto;">
            <ul>
                @foreach($units as $u)
                    <li style="list-style: none">
                        @if(isset($data))
                            {!! Form::checkbox('applicatn_units[]',$u->id,in_array($u->division_id,explode(',',$data->applicatn_range))&&in_array($u->id,explode(',',$data->applicatn_units)),['style'=>'vertical-align:sub','data-division-id'=>$u->division_id]) !!}
                            &nbsp;{{$u->unit_name_bng}}
                        @else
                            {!! Form::checkbox('applicatn_units[]',$u->id,true,['style'=>'vertical-align:sub','data-division-id'=>$u->division_id]) !!}
                            &nbsp;{{$u->unit_name_bng}}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('test','Auto De-Activate Circular After End Date : ',['class'=>'control-label','style'=>'margin-right:15px']) !!}
        <input type="checkbox" value="1" name="auto_terminate"
               @if((isset($data)&&$data->auto_terminate=='1')||Request::old('auto_terminate')=='1')checked
               @endif id="auto_terminate" class="switch-checkbox">
        <label for="auto_terminate" class=""></label>
    </div>
    <div class="form-group">
        {!! Form::label('admit_card_message','Message for admit card :',['class'=>'control-label']) !!}
        {!! Form::textarea('admit_card_message',null,['class'=>'form-control','placeholder'=>'','id'=>'admit_card_message']) !!}
    </div>
    <div class="form-group">
        <input type="hidden" name="constraint">
        <button class="btn btn-block btn-link btn-lg" onclick="return false" data-toggle="modal"
                data-target="#constraint-modal">Add rules for circular
        </button>
    </div>
    @if(isset($data))
        <button type="submit" class="btn btn-primary pull-right">
            <i class="fa fa-save"></i>&nbsp;Update Job Circular
        </button>
    @else
        <button type="submit" class="btn btn-primary pull-right">
            <i class="fa fa-save"></i>&nbsp;Save Job Circular
        </button>
    @endif
    {!! Form::close() !!}
    <div id="constraint-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Constraint</h4>
                </div>
                <div class="modal-body">
                    <div class="constraint-rule">
                        <fieldset>
                            <legend>Gender</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="checkbox" ng-model="constraint.gender.male" ng-true-value="'male'"
                                           ng-false-value="''" name="gender-male" value="male" id="gender-male"
                                           class="box-checkbox">
                                    <label for="gender-male">Male</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="checkbox" ng-model="constraint.gender.female" ng-true-value="'female'"
                                           ng-false-value="''" name="gender-female" value="female" id="gender-female"
                                           class="box-checkbox">
                                    <label for="gender-female">Female</label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>
                                Age
                            </legend>
                            {{--age validation rules--}}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Min age</label>
                                        <input type="text" placeholder="Min age" class="form-control"
                                               ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')"
                                               ng-model="constraint.age.min">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Min age date</label>
                                        <input type="text" placeholder="Min age date" date-picker=""
                                               class="form-control"
                                               ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')"
                                               ng-model="constraint.age.minDate">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="control-label">Max age</label>
                                            <input type="text" placeholder="Max age" class="form-control"
                                                   ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')"
                                                   ng-model="constraint.age.max">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="control-label">Max age date</label>
                                            <input type="text" placeholder="Max age date" date-picker=""
                                                   class="form-control"
                                                   ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')"
                                                   ng-model="constraint.age.maxDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" ng-if="apply_quota">
                                    <fieldset>
                                            <input type="checkbox" ng-model="constraint.age.quota.enabled"
                                                   ng-true-value="'1'"
                                                   ng-false-value="''" name="quota-select" value="1" id="quota-select"
                                                   class="box-checkbox"
                                                   ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')||constraint.age.quota.data.length>0">
                                            <label for="quota-select">Rules for Quota</label>
                                            <button class="btn btn-link btn-xs" ng-click="(constraint.age.quota.data = constraint.age.quota.data?constraint.age.quota.data:[]).push({minAge:'',maxAge:''})" ng-show="constraint.age.quota.enabled&&!(constraint.gender.male!='male'&&constraint.gender.female!='female')">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        <div ng-if="constraint.age.quota.enabled" style="padding-left: 10px">
                                            <div ng-repeat="d in constraint.age.quota.data" style="display: flex;justify-content: center;flex-direction: row;margin-bottom: 5px">
                                                <button class="btn btn-link btn-xs text-danger" style="flex-grow: .3" ng-click="constraint.age.quota.data.splice($index,1)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <select ng-model="d.type" class="form-control">
                                                        <option value="">--Select Quota--</option>
                                                        <option value="son_of_freedom_fighter">Son of freedom fighter</option>
                                                        <option value="grand_son_of_freedom_fighter">Grandson of freedom fighter</option>
                                                        <option value="member_of_ansar_or_vdp">Member of ANSAR or VDP</option>
                                                        <option value="orphan">Orphan</option>
                                                        <option value="physically_disabled">Physically disabled</option>
                                                        <option value="tribe">Tribe</option>
                                                    </select>
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" placeholder="min age" class="form-control" ng-model="d.minAge">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" placeholder="max age" class="form-control" ng-model="d.maxAge">
                                                </div>
                                            </div>
                                            <p class="text text-danger" ng-if="!constraint.age.quota.data||constraint.age.quota.data.length<=0">
                                                No rules available. click <strong>"<i class="fa fa-plus"></i>"</strong> to add rule
                                            </p>
                                        </div>

                                    </fieldset>
                                </div>
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group">--}}
                                {{--<div class="form-group">--}}
                                {{--<label>Select Applicable Quotas</label>--}}
                                {{--<select ng-model="quota_type" class="form-control"--}}
                                {{--ng-change="onChangeQuota()">--}}
                                {{--<option value="" selected>--Select Quota--</option>--}}
                                {{--<option value="son_of_freedom_fighter">Son of freedom fighter</option>--}}
                                {{--<option value="grand_son_of_freedom_fighter">Grandson of freedom--}}
                                {{--fighter--}}
                                {{--</option>--}}
                                {{--<option value="member_of_ansar_or_vdp">Member of ANSAR or VDP</option>--}}
                                {{--<option value="orphan">Orphan</option>--}}
                                {{--<option value="physically_disabled">Physically disabled</option>--}}
                                {{--<option value="tribe">Tribe</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group">--}}
                                {{--<div class="form-group">--}}
                                {{--<label>Quota Based Max Age</label>--}}
                                {{--<input type="text" placeholder="" class="form-control"--}}
                                {{--ng-model="constraint.age.quota.maxAge">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<style>--}}
                                {{--.selected-quota {--}}
                                {{--padding: 1%;--}}
                                {{--display: inline-block;--}}
                                {{--background: green;--}}
                                {{--color: #FFF;--}}
                                {{--margin: 2px;--}}
                                {{--cursor: pointer;--}}
                                {{--}--}}
                                {{--</style>--}}
                                {{--<div class="col-md-12">--}}
                                {{--<div class="well">--}}
                                {{--<label style="width: 100%">Selected Quotas</label>--}}
                                {{--<div id="selected-quota-type">--}}
                                {{--append from angular--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                            {{--end age validation rules--}}
                        </fieldset>
                        {{--start height validation rules--}}
                        <fieldset>
                            <legend>Height</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Male</label>
                                        <div class="row">
                                            <div class="col-md-6" style="padding-right: 0">
                                                <input type="text" ng-disabled="constraint.gender.male!='male'"
                                                       ng-model="constraint.height.male.feet" class="form-control"
                                                       placeholder="Feet">
                                            </div>
                                            <div class="col-md-6" style="padding-right: 0">
                                                <input type="text" ng-disabled="constraint.gender.male!='male'"
                                                       ng-model="constraint.height.male.inch"
                                                       class="form-control" placeholder="Inch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Female</label>
                                        <div class="row">
                                            <div class="col-md-6" style="padding-right: 0">
                                                <input type="text" ng-disabled="constraint.gender.female!='female'"
                                                       ng-model="constraint.height.female.feet" class="form-control"
                                                       placeholder="Feet">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" ng-disabled="constraint.gender.female!='female'"
                                                       ng-model="constraint.height.female.inch" class="form-control"
                                                       placeholder="Inch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" ng-if="apply_quota">
                                    <fieldset>
                                        <input type="checkbox" ng-model="constraint.height.quota.enabled"
                                               ng-true-value="'1'"
                                               ng-false-value="''" name="quota-select-height" value="1" id="quota-select-height"
                                               class="box-checkbox"
                                               ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')||constraint.height.quota.data.length>0">
                                        <label for="quota-select-height">Rules for Quota</label>
                                        <button class="btn btn-link btn-xs" ng-click="(constraint.height.quota.data = constraint.height.quota.data?constraint.height.quota.data:[]).push({})" ng-show="constraint.height.quota.enabled&&!(constraint.gender.male!='male'&&constraint.gender.female!='female')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <div ng-if="constraint.height.quota.enabled" style="padding-left: 10px">
                                            <div ng-repeat="d in constraint.height.quota.data" style="display: flex;justify-content: center;flex-direction: row;margin-bottom: 5px">
                                                <button class="btn btn-link btn-xs text-danger" style="flex-grow: .3" ng-click="constraint.height.quota.data.splice($index,1)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <select ng-model="d.type" class="form-control">
                                                        <option value="">--Select Quota--</option>
                                                        <option value="son_of_freedom_fighter">Son of freedom fighter</option>
                                                        <option value="grand_son_of_freedom_fighter">Grandson of freedom fighter</option>
                                                        <option value="member_of_ansar_or_vdp">Member of ANSAR or VDP</option>
                                                        <option value="orphan">Orphan</option>
                                                        <option value="physically_disabled">Physically disabled</option>
                                                        <option value="tribe">Tribe</option>
                                                    </select>
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.male!='male'" placeholder="feet(Male)" class="form-control" ng-model="d.male.feet">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.male!='male'" placeholder="inch(Male)" class="form-control" ng-model="d.male.inch">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.female!='female'" placeholder="feet(Female)" class="form-control" ng-model="d.female.feet">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.female!='female'" placeholder="inch(Female)" class="form-control" ng-model="d.female.inch">
                                                </div>

                                            </div>
                                            <p class="text text-danger" ng-if="!constraint.height.quota.data||constraint.height.quota.data.length<=0">
                                                No rules available. click <strong>"<i class="fa fa-plus"></i>"</strong> to add rule
                                            </p>
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                        {{--end height validation rules--}}
                        {{--start weight validation rules--}}
                        <fieldset>
                            <legend>Weight</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Male</label>
                                        <input type="text" ng-disabled="constraint.gender.male!='male'"
                                               ng-model="constraint.weight.male" class="form-control"
                                               placeholder="Weight in kg">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Female</label>
                                        <input type="text" ng-disabled="constraint.gender.female!='female'"
                                               ng-model="constraint.weight.female" class="form-control"
                                               placeholder="Weight in kg">
                                    </div>
                                </div>
                                <div class="col-md-12" ng-if="apply_quota">
                                    <fieldset>
                                        <input type="checkbox" ng-model="constraint.weight.quota.enabled"
                                               ng-true-value="'1'"
                                               ng-false-value="''" name="quota-select-weight" value="1" id="quota-select-weight"
                                               class="box-checkbox"
                                               ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')||constraint.weight.quota.data.length>0">
                                        <label for="quota-select-weight">Rules for Quota</label>
                                        <button class="btn btn-link btn-xs" ng-click="(constraint.weight.quota.data = constraint.weight.quota.data?constraint.weight.quota.data:[]).push({})" ng-show="constraint.weight.quota.enabled&&!(constraint.gender.male!='male'&&constraint.gender.female!='female')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <div ng-if="constraint.weight.quota.enabled" style="padding-left: 10px">
                                            <div ng-repeat="d in constraint.weight.quota.data" style="display: flex;justify-content: center;flex-direction: row;margin-bottom: 5px">
                                                <button class="btn btn-link btn-xs text-danger" style="flex-grow: .3" ng-click="constraint.weight.quota.data.splice($index,1)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <select ng-model="d.type" class="form-control">
                                                        <option value="">--Select Quota--</option>
                                                        <option value="son_of_freedom_fighter">Son of freedom fighter</option>
                                                        <option value="grand_son_of_freedom_fighter">Grandson of freedom fighter</option>
                                                        <option value="member_of_ansar_or_vdp">Member of ANSAR or VDP</option>
                                                        <option value="orphan">Orphan</option>
                                                        <option value="physically_disabled">Physically disabled</option>
                                                        <option value="tribe">Tribe</option>
                                                    </select>
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.male!='male'" placeholder="weight(Male)" class="form-control" ng-model="d.male">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.female!='female'" placeholder="weight(Female)" class="form-control" ng-model="d.female">
                                                </div>

                                            </div>
                                            <p class="text text-danger" ng-if="!constraint.weight.quota.data||constraint.weight.quota.data.length<=0">
                                                No rules available. click <strong>"<i class="fa fa-plus"></i>"</strong> to add rule
                                            </p>
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                        {{--end weight validation rules--}}
                        {{--start chest validation rules--}}
                        <fieldset>
                            <legend>Chest</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Male</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" ng-disabled="constraint.gender.male!='male'"
                                                       ng-model="constraint.chest.male.min" class="form-control"
                                                       placeholder="">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" ng-disabled="constraint.gender.male!='male'"
                                                       ng-model="constraint.chest.male.max" class="form-control"
                                                       placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Female</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" ng-disabled="constraint.gender.female!='female'"
                                                       ng-model="constraint.chest.female.min" class="form-control"
                                                       placeholder="">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" ng-disabled="constraint.gender.female!='female'"
                                                       ng-model="constraint.chest.female.max" class="form-control"
                                                       placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" ng-if="apply_quota">
                                    <fieldset>
                                        <input type="checkbox" ng-model="constraint.chest.quota.enabled"
                                               ng-true-value="'1'"
                                               ng-false-value="''" name="quota-select-chest" value="1" id="quota-select-chest"
                                               class="box-checkbox"
                                               ng-disabled="(constraint.gender.male!='male'&&constraint.gender.female!='female')||constraint.chest.quota.data.length>0">
                                        <label for="quota-select-chest">Rules for Quota</label>
                                        <button class="btn btn-link btn-xs" ng-click="(constraint.chest.quota.data = constraint.chest.quota.data?constraint.chest.quota.data:[]).push({})" ng-show="constraint.chest.quota.enabled&&!(constraint.gender.male!='male'&&constraint.gender.female!='female')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <div ng-if="constraint.chest.quota.enabled" style="padding-left: 10px">
                                            <div ng-repeat="d in constraint.chest.quota.data" style="display: flex;justify-content: center;flex-direction: row;margin-bottom: 5px">
                                                <button class="btn btn-link btn-xs text-danger" style="flex-grow: .3" ng-click="constraint.chest.quota.data.splice($index,1)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <select ng-model="d.type" class="form-control">
                                                        <option value="">--Select Quota--</option>
                                                        <option value="son_of_freedom_fighter">Son of freedom fighter</option>
                                                        <option value="grand_son_of_freedom_fighter">Grandson of freedom fighter</option>
                                                        <option value="member_of_ansar_or_vdp">Member of ANSAR or VDP</option>
                                                        <option value="orphan">Orphan</option>
                                                        <option value="physically_disabled">Physically disabled</option>
                                                        <option value="tribe">Tribe</option>
                                                    </select>
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.male!='male'" placeholder="mormal(Male)" class="form-control" ng-model="d.male.min">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.male!='male'" placeholder="expanded(Male)" class="form-control" ng-model="d.male.max">
                                                </div>

                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.female!='female'" placeholder="mormal(Female)" class="form-control" ng-model="d.female.min">
                                                </div>
                                                <div class="from-group" style="flex-grow: 1;margin-right: 5px">
                                                    <input type="text" ng-disabled="constraint.gender.female!='female'" placeholder="expanded(Female)" class="form-control" ng-model="d.female.max">
                                                </div>

                                            </div>
                                            <p class="text text-danger" ng-if="!constraint.chest.quota.data||constraint.chest.quota.data.length<=0">
                                                No rules available. click <strong>"<i class="fa fa-plus"></i>"</strong> to add rule
                                            </p>
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                        {{--end chest validation rules--}}
                        <fieldset>
                            <legend>Education</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Min education</label>
                                        <select name="" id=""
                                                ng-disabled="constraint.gender.male!='male'&&constraint.gender.female!='female'"
                                                ng-model="constraint.education.min" class="form-control">
                                            <option value="">--Select a degree--</option>
                                            <option ng-repeat="(key,value) in minEduList" value="[[key]]">[[value]]
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Max education</label>
                                        <select name="" id=""
                                                ng-disabled="constraint.gender.male!='male'&&constraint.gender.female!='female'"
                                                ng-model="constraint.education.max"
                                                class="form-control">
                                            <option value="">--Select a degree--</option>
                                            <option ng-repeat="(key,value) in minEduList" value="[[key]]">[[value]]
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                    <button type="button" ng-click="onSave('constraint')" class="btn btn-primary pull-left"
                            data-dismiss="modal">Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        $(".range-app").on('change', function (event) {
            var status = $(this).prop('checked');
            var v = $(this).val();
            if (status) {
                $('*[data-division-id="' + v + '"]').prop('checked', true)
            }
            else {
                $('*[data-division-id="' + v + '"]').prop('checked', false)
            }
        })
        CKEDITOR.replace('terms_and_conditions');
        CKEDITOR.replace('admit_card_message');
    })
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>