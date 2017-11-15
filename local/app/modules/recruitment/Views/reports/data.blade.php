<?php $i = (intVal($applicants->currentPage() - 1) * $applicants->perPage()) + 1; ?>
<div class="table-responsive">

    <table class="table table-bordered" style="overflow: hidden">
        <caption><span style="font-size: 20px;">Total Applicant({{$applicants->total()}})</span>
            <form action="{{URL::route('report.applicants.status_export')}}" method="post" target="_blank" style="display: inline">
                {!! csrf_field() !!}
                <input type="hidden" ng-repeat="(k,v) in param" name="[[k]]" value="[[v]]">
                <button class="btn btn-primary btn-xs">
                    <i class="fa fa-file-excel-o"></i>&nbsp; Export page
                </button>
            </form>
            @if(count($applicants))
                <div class="pull-right" style="margin: -20px 0" paginate ref="loadPage(url)">
                    {{$applicants->render()}}
                </div>
            @endif
        </caption>

        <tr>
            <th>#</th>
            <th>Applicant Name</th>
            <th>Father Name</th>
            <th>Birth Date</th>
            <th>National ID No.</th>
            <th>Division</th>
            <th>District</th>
            <th>Thana</th>
            <th>Height</th>
            <th>Weight</th>
            @if(Auth::user()->type==11)
                <th>Mobile no</th>
            @endif
            <th>Status</th>

        </tr>

        @if(count($applicants))
            @foreach($applicants as $a)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$a->applicant_name_bng}}</td>
                    <td>{{$a->father_name_bng}}</td>
                    <td>{{$a->date_of_birth}}</td>
                    <td>{{$a->national_id_no}}</td>
                    <td>{{$a->division->division_name_bng}}</td>
                    <td>{{$a->district->unit_name_bng}}</td>
                    <td>{{$a->thana->thana_name_bng}}</td>
                    <td>{{$a->height_feet}} feet {{$a->height_inch}} inch</td>
                    <td>{{$a->weight}} kg</td>
                    @if(Auth::user()->type==11)
                        <td>{{$a->mobile_no_self}}</td>
                    @endif
                    <td>{{$a->status}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12" class="bg-warning">
                    No applicants found
                </td>
            </tr>
        @endif

    </table>
</div>
@if(count($applicants))
    <form action="{{URL::route('report.applicants.status_export')}}" method="post" target="_blank" style="display: inline">
        {!! csrf_field() !!}
        <input type="hidden" ng-repeat="(k,v) in param" name="[[k]]" value="[[v]]">
        <button class="btn btn-primary btn-xs">
            <i class="fa fa-file-excel-o"></i>&nbsp; Export page
        </button>
    </form>
    <div class="pull-right" style="margin: -20px 0" paginate ref="loadPage(url)">
        {{$applicants->render()}}
    </div>
@endif