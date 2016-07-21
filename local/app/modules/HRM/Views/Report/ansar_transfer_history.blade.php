@extends('template.master')
@section('title','Ansar Transfer Report')
@section('breadcrumb')
    {!! Breadcrumbs::render('transfer_ansar_history') !!}
@endsection
@section('content')
    <script>
        GlobalApp.controller('TransferController', function ($scope,$http) {
            $scope.ansars = [];
            $scope.isLoading = false;
            $scope.loadTransferHistory = function (ansar_id) {
                $scope.isLoading = true;
                $http({
                    url:'{{URL::route('get_transfer_ansar_history')}}',
                    method:'get',
                    params:{ansar_id:ansar_id}
                }).then(function (response) {
                    $scope.ansars = response.data;
                    $scope.isLoading = false;
                },function (response) {
                    $scope.isLoading = false;
                })
            }
            $scope.loadTransferHistoryOnKeyPress = function (ansar_id,$event) {
                if($event.keyCode==13) {
                    $scope.isLoading = true;
                    $http({
                        url: '{{URL::route('get_transfer_ansar_history')}}',
                        method: 'get',
                        params: {ansar_id: ansar_id}
                    }).then(function (response) {
                        $scope.ansars = response.data;
                        $scope.isLoading = false;
                    }, function (response) {
                        $scope.isLoading = false;
                    })
                }
            }
            $scope.convertDate = function (d) {
                return moment(d).format("DD-MMM-YYYY")
            }
        })
        $(function () {
            $('body').on('click','#print-report', function (e) {
               // alert("pppp")
                e.preventDefault();
                $('body').append('<div id="print-area" class="letter">'+$("#ansar_transfer_history").html()+'</div>')
                window.print();
                $("#print-area").remove()
            })
        })

    </script>
    <div ng-controller="TransferController">
        <div class="loading-report animated" ng-class="{fadeInDown:isLoading,fadeOutUp:!isLoading}">
            <img src="{{asset('dist/img/ring-alt.gif')}}" class="center-block">
            <h4>Loading...</h4>
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-body"><br>
                    <div class="row">
                        <div class="col-md-6 col-centered">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {{--<label class="control-label">Enter a ansar id</label>--}}
                                    <input type="text" ng-model="ansar_id" class="form-control" placeholder="Enter Ansar Id" ng-keypress="loadTransferHistoryOnKeyPress(ansar_id,$event)">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-primary" ng-click="loadTransferHistory(ansar_id)">Generate Transfer Report</button>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" id="ansar_transfer_history">
                            <h3 style="text-align: center">Ansar Transfer History&nbsp;<a href="#" id="print-report"><span class="glyphicon glyphicon-print"></span></a></h3>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>SL. No</th>
                                        <th>From KPI</th>
                                        <th>To KPI</th>
                                        <th>District</th>
                                        <th>Thana</th>
                                        <th>Joining Date</th>
                                        <th>Transfer Date</th>
                                    </tr>
                                    <tr ng-show="ansars.length==0">
                                        <td colspan="7" class="warning">
                                            No ansar found
                                        </td>
                                    </tr>
                                    <tr ng-repeat="a in ansars" ng-show="ansars.length>0">
                                        <td>[[$index+1]]</td>
                                        <td>[[a.FromkpiName]]</td>
                                        <td>[[a.TokpiName]]</td>
                                        <td>[[a.unit]]</td>
                                        <td>[[a.thana]]</td>
                                        <td>[[convertDate(a.joiningDate)]]</td>
                                        <td>[[convertDate(a.transferDate)]]</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop