@extends('template.master')
@section('title','Dashboard')
{{--@section('small_title','Human Resource Management')--}}
@section('breadcrumb')
{!! Breadcrumbs::render('hrm') !!}
@endsection
@section('title','Dashboard')
{{--@section('small_title','Human Resource Management')--}}
@section('breadcrumb')
{!! Breadcrumbs::render('hrm') !!}
@endsection
@section('content')
        <!-- Content Wrapper. Contains page content -->
<script>


    GlobalApp.controller('TotalAnsar', function ($http, $scope) {

        $scope.allAnsar = [];
        $scope.loadingAnsar = true;
        $scope.embodimentData = {};
        $scope.graphData = [];
        $scope.loadAnsar = function () {
            $http({
                url: "{{URL::to('HRM/getTotalAnsar')}}",
                method: 'get',
            }).then(function (response) {
//                alert(JSON.stringify(response.data));
                $scope.allAnsar = formatNumber(response.data);
                console.log(response.data);
                $scope.loadingAnsar = false;
            }, function (response) {
                $scope.loadingAnsar = false;
                switch (response.status) {
                    case 404:
                        response.data = "Not found(404)";
                        break;
                    case 500:
                        response.data = "Server error(500)";
                        break;
                }
                $scope.allAnsar = {
                    totalAnsar: response.data,
                    totalPanel: response.data,
                    totalNotVerified: response.data,
                    totalFree: response.data,
                    totalEmbodied: response.data,
                    totalOffered: response.data,
                    totalFreeze: response.data,
                    totalBlockList: response.data,
                    totalBlackList: response.data,
                    totalRest: response.data,
                }
            })
        }
        $scope.loadAnsar();

        $scope.loadRecentAnsar = function () {

            $http({
                url: "{{URL::to('HRM/getrecentansar')}}",
                method: 'get',
            }).then(function (response) {
//                alert(JSON.stringify(response.data));
                $scope.recentAnsar = formatNumber(response.data);
                console.log(response.data);
//                $scope.loadingAnsar = false;
            }, function (response) {
//                $scope.loadingAnsar = false;
                switch (response.status) {
                    case 404:
                        response.data = "Not found(404)";
                        break;
                    case 500:
                        response.data = "Server error(500)";
                        break;
                }
                $scope.recentAnsar = {
                    recentAnsar: response.data,
                    recentPanel: response.data,
                    recentNotVerified: response.data,
                    recentFree: response.data,
                    recentEmbodied: response.data,
                    recentFreeze: response.data,
                    recentBlockList: response.data,
                    recentBlackList: response.data,
                    recentRest: response.data,
                    recentOffered: response.data

                }
            })
        }
        $scope.loadRecentAnsar();


        $scope.progressInfo = [];
        $scope.loadingProgressInfo = true;
        $scope.progressData = function () {
            $http({
                url: "{{URL::to('HRM/progress_info')}}",
                method: 'get',
            }).then(function (response) {
                $scope.progressInfo = formatNumber(response.data);
                $scope.loadingProgressInfo = false;
            }, function (response) {
                $scope.loadingProgressInfo = false;
                switch (response.status) {
                    case 404:
                        response.data = "Not found(404)";
                        break;
                    case 500:
                        response.data = "Server error(500)";
                        break;
                }
                $scope.progressInfo = {
                    totalServiceEndedInThreeYears: response.data,
                    totalAnsarReachedFiftyYearsOfAge: response.data,
                    totalNotInterestedMembersUptoTenTimes: response.data,

                }
            })
        }
        $scope.fiveDaysOffer = function () {
            $http({
                url: "{{URL::route('offer_accept_last_5_day_data')}}",
                method: 'get',
                params: {
                    type: 'count',
                    unit:'all',
                    thana:'all',
                    division:'all',
                    rank:'all',
                    sex:'all'
                }
            }).then(function (response) {
                $scope.offerAcceptLastFiveDays = sum(response.data.total);
                $scope.loadingProgressInfo = false;
            }, function (response) {
                $scope.loadingProgressInfo = false;
                switch (response.status) {
                    case 404:
                        response.data = "Not found(404)";
                        break;
                    case 500:
                        response.data = "Server error(500)";
                        break;
                }
                $scope.offerAcceptLastFiveDays = response.data;
            })
        }
        function sum(t){
            var s = 0;
            for(var i in t){
                for(var j =0 ;j<t[i].length;j++){
                    s = parseInt(s)+ parseInt(t[i][j].total);
                }
            }
            return s;
        }
        $scope.progressData();
        $scope.fiveDaysOffer();
        $http({
            url: "{{URL::to('HRM/graph_embodiment')}}",
            method: 'get',
        }).then(function (response) {
            $scope.graphData = response.data
        }, function (response) {
            $scope.graphData = [];
        })
        $scope.graphDisembodiment = [];
        {{--$scope.graphDisembodimentData = function () {--}}
        {{--$http({--}}
        {{--url: "{{URL::to('graph_disembodiment')}}",--}}
        {{--method: 'get',--}}
        {{--}).then(function (response) {--}}
        {{--$scope.graphData.push({d:response.data})--}}
        {{--})--}}
        {{--}--}}
        {{--$scope.graphDisembodimentData();--}}
        function formatNumber(data) {
            Object.keys(data).forEach(function (key) {
                data[key] = data[key].toLocaleString();
            })
            return data;
        }
    })
    GlobalApp.directive('graph', function () {
        return {
            restrict: 'A',
            link: function (scope, elem, attr) {
                scope.$watch('graphData', function (n, o) {
                    var labels = [], ea = [], ed = [];
                    if (Object.keys(n).length > 0) {

                        n.ea.forEach(function (item) {
                            labels.push(item.month)
                            ea.push(item.total)
                        })
                        n.da.forEach(function (item) {
                            ed.push(item.total)
                        })
                        $.getScript('http://www.chartjs.org/assets/Chart.js', function () {

                            var data = {
                                labels: labels,

                                datasets: [
                                    {
                                        fillColor: "rgba(0,60,100,1)",
                                        strokeColor: "black",
                                        pointColor: "rgba(220,220,220,1)",
                                        pointStrokeColor: "#fff",
                                        data: ea
                                    },
                                    {
                                        fillColor: "rgba(151,187,205,0.5)",
                                        strokeColor: "rgba(151,187,205,1)",
                                        pointColor: "rgba(151,187,205,1)",
                                        pointStrokeColor: "#fff",
                                        data: ed
                                    }
                                ]
                            }

                            var options = {
                                animation: true,
                                events:['click'],
                                onClick: function (event) {
                                    alert(event.datapoint.y)
                                },
                            };

                            //Get the context of the canvas element we want to select
                            var c = $('#graph-embodiment');
                            var ct = c.get(0).getContext('2d');
                            var ctx = document.getElementById("graph-embodiment").getContext("2d");
                            /*********************/
                            new Chart(ctx).Bar(data, options);
                            //document.getElementById("legendDiv").innerHTML = new Chart(ctx).Bar(data,options).generateLegend();

                        })
                    }
                }, true)
            }
        }
    })
</script>
<section class="content" ng-controller="TotalAnsar">

    <!-- =========================================================== -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">

        </div>
        <!-- /.col -->
        <!-- show line-->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="line-bar-top"></div>
            <div class="info-box bg-aqua"><span class="info-box-icon"><img src="{{asset('dist/img/not_verified.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/not_verified_ansar" class="btn-link"
                       style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tu')</span>
                    <span class="info-box-number" style="font-weight: normal">[[allAnsar.totalNotVerified]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/not_verified_ansar" class="btn-link"
                       style="color:#FFFFFF">
                    <span class="progress-description">Recent-[[recentAnsar.recentNotVerified]]
                    </span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><i class="fa fa-envelope"></i></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/offerred_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.to')</span>
                    <span class="info-box-number" style="font-weight: normal">
                       [[(allAnsar.totalOffered|num)+(allAnsar.totalOfferedReceived|num)]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/offerred_ansar" style="color:#FFFFFF"
                       class="btn-link">
                    <span class="progress-description" style="color:#FFFFFF">
                       Recent-[[(recentAnsar.recentOffered|num)+(recentAnsar.recentOfferedReceived|num)]]
                    </span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img
                            src="{{asset('dist/img/freeze.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/freezed_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tfr')</span>
                    <span class="info-box-number" style="font-weight: normal">[[allAnsar.totalFreeze]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/freezed_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentFreeze]]</span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img
                            src="{{asset('dist/img/ansars.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/all_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.ta')</span>
                    <span class="info-box-number" style="font-weight: normal">
                        [[allAnsar.totalAnsar]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/all_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentAnsar]]</span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img src="{{asset('dist/img/free.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/free_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tf')</span>
                    <span class="info-box-number" style="font-weight: normal">
                        [[allAnsar.totalFree]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/free_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentFree]]</span></a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><i class="fa fa-bed"></i></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/rest_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tr')</span>
                    <span class="info-box-number" style="font-weight: normal">
                      [[allAnsar.totalRest]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/rest_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentRest]]</span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img
                            src="{{asset('dist/img/blocklist.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/blocked_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tb')</span>
                    <span class="info-box-number" style="font-weight: normal">
                        [[allAnsar.totalBlockList]]

                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar">
                    </span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/blocked_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentBlockList]]</span></a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            {{--<div class="info-box bg-aqua"> <span class="info-box-icon"><i class="fa fa-exclamation-circle"></i></span>--}}
            {{--<div class="info-box-content"> <span class="info-box-text">Total Not Verified (Status Free)</span> <span class="info-box-number">322</span>--}}
            {{--<div class="progress">--}}
            {{--<div class="progress-bar" style="width: 70%"></div>--}}
            {{--</div>--}}
            {{--<span class="progress-description">70% Increase in 30 Days </span> </div>--}}
            {{--<!-- /.info-box-content -->--}}
            {{--</div>--}}
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- show line-->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="line-bar-bottom"></div>
            <div class="info-box bg-aqua"><span class="info-box-icon"><img src="{{asset('dist/img/queue.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/paneled_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tp')</span>
                    <span class="info-box-number" style="font-weight: normal">
                       [[allAnsar.totalPanel]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar"></span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/paneled_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentPanel]]</span></a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img src="{{asset('dist/img/embodiment2.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/embodied_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.te')</span>
                    <span class="info-box-number" style="font-weight: normal">
                       [[allAnsar.totalEmbodied]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar"></span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a
                            href="{{URL::to('HRM/show_recent_ansar_list')}}/embodied_ansar" style="color:#FFFFFF"
                            class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentEmbodied]]</span></a></div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

        </div>
        <!-- /.col -->
        <div class="col-md-3 line-bar-middle col-sm-6 col-xs-12">
            <div class="info-box bg-aqua"><span class="info-box-icon"><img
                            src="{{asset('dist/img/blacklist.png')}}"></span>

                <div class="info-box-content">
                    <a href="{{URL::to('HRM/show_ansar_list')}}/blacked_ansar"
                       class="btn-link" style="color: #FFFFFF !important;">
                        <span class="info-box-text">@lang('title.tba')</span>
                    <span class="info-box-number" style="font-weight: normal">
                      [[allAnsar.totalBlackList]]
                        <img src="{{asset('dist/img/facebook-white.gif')}}" width="20" ng-show="loadingAnsar"></span>
                    </a>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <a href="{{URL::to('HRM/show_recent_ansar_list')}}/blacked_ansar"
                       style="color:#FFFFFF" class="btn-link">
                        <span class="progress-description">Recent-[[recentAnsar.recentBlackList]]</span></a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- =========================================================== -->
    <div class="row">
        <div class="col-sm-12 col-md-9 col-xs-12 pull-right">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Progress Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="padding-left: 0;padding-right: 0">
                    <div class="label-hrm" style="border-bottom: 1px solid rgba(153, 153, 153, 0.52)">
                        <div class="label-hrm-title">
                            <span class="info-box-text"
                                  style="color: #000000;white-space: normal;overflow: auto;text-overflow: initial">Total number of Ansars who will complete 3 years of service within the next 2 months</span>
                        </div>

                        <div class="label-hrm-calculation">
                                <span class="info-box-text" style="color: #000000;white-space: normal;overflow: auto;text-overflow: initial">
                                    <a href="{{URL::to('HRM/service_ended_in_three_years')}}/[[progressInfo.totalServiceEndedInThreeYears]]"
                                            class="btn-link">[[progressInfo.totalServiceEndedInThreeYears]]</a><img
                                            src="{{asset('dist/img/facebook.gif')}}" width="20"
                                            ng-show="loadingProgressInfo">
                                     </span>
                        </div>
                        <br style="clear: left;"/>
                    </div>
                    <div class="label-hrm" style="border-bottom: 1px solid rgba(153, 153, 153, 0.52)">
                        <div class="label-hrm-title">
                            <span class="info-box-text"
                                  style="color: #000000;white-space: normal;overflow: auto;text-overflow: initial">Total number of Ansars who will reach 50 years of age within the next 3 months</span>
                        </div>

                        <div class="label-hrm-calculation">
                            <span class="info-box-text" style="color: #000000">
                                <a href="{{URL::to('HRM/ansar_reached_fifty_years')}}/[[progressInfo.totalAnsarReachedFiftyYearsOfAge]]"
                                                                                  class="btn-link">[[progressInfo.totalAnsarReachedFiftyYearsOfAge]]</a><img
                                        src="{{asset('dist/img/facebook.gif')}}" width="20"
                                        ng-show="loadingProgressInfo"></span>
                        </div>
                        <br style="clear: left;"/>
                    </div>
                    <div class="label-hrm" style="border-bottom: 1px solid rgba(153, 153, 153, 0.52)">
                        <div class="label-hrm-title">
                            <span class="info-box-text"
                                  style="color: #000000;white-space: normal;overflow: auto;text-overflow: initial">Total number of Ansars who are not interested to join after 10 or more reminders </span>
                        </div>

                        <div class="label-hrm-calculation">
                            <span class="info-box-text" style="color: #000000">
                                <a href="{{URL::to('HRM/ansar_not_interested')}}/[[progressInfo.totalNotInterestedMembersUptoTenTimes]]"
                                                                                  class="btn-link">[[progressInfo.totalNotInterestedMembersUptoTenTimes]]</a><img
                                        src="{{asset('dist/img/facebook.gif')}}" width="20"
                                        ng-show="loadingProgressInfo"></span>
                        </div>
                        <br style="clear: left;"/>
                    </div>
                    <div class="label-hrm">
                        <div class="label-hrm-title">
                            <span class="info-box-text"
                                  style="color: #000000;white-space: normal;overflow: auto;text-overflow: initial">Total number of Ansars who accept the offer within last 5 days </span>
                        </div>

                        <div class="label-hrm-calculation">
                            <span class="info-box-text" style="color: #000000">
                                <a href="{{URL::route('offer_accept_last_5_day')}}" class="btn-link">[[offerAcceptLastFiveDays==undefined?0:offerAcceptLastFiveDays]]</a>
                                <img src="{{asset('dist/img/facebook.gif')}}" width="20" ng-show="loadingProgressInfo"></span>
                        </div>
                        <br style="clear: left;"/>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-sm-12 col-md-9 col-xs-12 pull-right">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Total number of Ansars who have
                        been Embodied and
                        Disembodied in recent years</h3>
                </div>
                <div class="box-body">
                    <div id="graph-level" class="col-md-8 col-sm-12 col-xs-12 col-centered" style="text-align: center">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <span style="color: #000000"><i class="fa fa-lg fa-circle"
                                                            style="color: rgba(0,60,100,1) !important;"></i>  Embodied</span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <span style="color: #000000"><i class="fa fa-lg fa-circle"
                                                            style="color: rgba(151,187,205,0.5) !important;"></i>  Disembodied</span>
                        </div>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <canvas id="graph-embodiment" graph style="width: 100%; height: 160px;" class="well"></canvas>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        {{--<div class="col-sm-4 col-md-4">--}}
        {{--<div class="box box-solid">--}}
        {{--<div class="box-header with-border">--}}
        {{--<h3 class="box-title">Total Ansar Disemboded in recent Year</h3>--}}
        {{--</div><!-- /.box-header -->--}}
        {{--<div class="box-body">--}}
        {{--<canvas id="graph-disembodiment" style="width: 100%" class="well"></canvas>--}}
        {{--</div><!-- /.box-body -->--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>
</section>
<!-- /.content-wrapper -->

@endsection
      