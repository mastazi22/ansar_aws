{{--User: Shreya--}}
{{--Date: 12/24/2015--}}
{{--Time: 5:43 PM--}}
@extends('template.master')
@section('title','Total number of Ansars who will reach 50 years of age within the next 3 months')
{{--@section('small_title','Total number of Ansars who will reach 50 years of age within next 3 months')--}}
@section('breadcrumb')
    {!! Breadcrumbs::render('dashboard_menu_50_year',$total) !!}
@endsection
@section('content')
    <script>
        GlobalApp.controller('AnsarFiftyYearsReachedListController', function ($scope, $http,$sce) {
            $scope.total = 0;
            $scope.numOfPage = 0;
            $scope.selectedDistrict = "all";
            $scope.selectedThana = "all"
            $scope.districts = [];
            $scope.thanas = [];
            $scope.itemPerPage = 20
            $scope.currentPage = 0;
            $scope.ansars = $sce.trustAsHtml("");
            $scope.pages = [];
            $scope.loadingDistrict = true;
            $scope.loadingThana = false;
            $scope.loadingPage = [];
            $scope.allLoading = true;
            $scope.errorFound=0;
            $scope.loadPagination = function(){
                $scope.pages = [];
                for (var i = 0; i < $scope.numOfPage; i++) {
                    $scope.pages.push({
                        pageNum: i,
                        offset: i * $scope.itemPerPage,
                        limit: $scope.itemPerPage
                    })
                    $scope.loadingPage[i]=false;
                }
                if($scope.numOfPage>0)$scope.loadPage($scope.pages[0]);
                else $scope.loadPage({pageNum:0,offset:0,limit:$scope.itemPerPage,view:'view'});
            }
            $scope.loadPage = function (page,$event) {
                if($event!=undefined)  $event.preventDefault();
                $scope.currentPage = page.pageNum;
                $scope.loadingPage[page.pageNum]=true;
                $scope.allLoading = true;
                $http({
                    url: '{{URL::route('ansar_reached_fifty_details')}}',
                    method: 'get',
                    params: {
                        offset: page.offset,
                        limit: page.limit,
                        unit:$scope.selectedDistrict,
                        thana:$scope.selectedThana,
                        view:'view'
                    }
                }).then(function (response) {
                    $scope.ansars = $sce.trustAsHtml(response.data);
                    $scope.loadingPage[page.pageNum]=false;
                    $scope.allLoading = false;
                })
            }
            $scope.loadTotal = function () {
                $scope.allLoading = true;
                $http({
                    url: '{{URL::route('ansar_reached_fifty_details')}}',
                    method: 'get',
                    params: {
                        unit:$scope.selectedDistrict,
                        thana:$scope.selectedThana,
                        view:'count'
                    }
                }).then(function (response) {
                    $scope.total = response.data.total;
                    $scope.numOfPage = Math.ceil($scope.total/$scope.itemPerPage);
                    $scope.loadPagination();
                    //alert($scope.total)
                }, function (response) {
                    $scope.total = 0;
                    $scope.ansars = $sce.trustAsHtml("<tr class='warning'><td colspan='"+$('.table').find('tr').find('th').length+"'>"+response.data+"</td></tr>");
                    $scope.allLoading = false;
                    $scope.pages = [];
                })
            }
            $scope.filterMiddlePage = function (value, index, array) {
                var minPage = $scope.currentPage-3<0?0:($scope.currentPage>array.length-4?array.length-8:$scope.currentPage-3);
                var maxPage = minPage+7;
                if (value.pageNum >= minPage && value.pageNum <= maxPage) {
                    return true;
                }
            }
            $http({
                method:'get',
                url:'{{URL::to('HRM/DistrictName')}}'
            }).then(function (response) {
                $scope.districts = response.data;
                $scope.loadingDistrict = false;
            })
            $scope.loadThana = function (d_id) {
                $scope.loadingThana = true;
                $scope.allLoading = true;
                $http({
                    method: 'get',
                    url: '{{URL::to('HRM/ThanaName')}}',
                    params: {id: d_id}
                }).then(function (response) {
                    $scope.thanas = response.data;
                    $scope.selectedThana = "all";
                    $scope.loadingThana = false;
                    $scope.loadTotal()
                })
            }
            $scope.loadTotal()
        })
    </script>
    <div ng-controller="AnsarFiftyYearsReachedListController">
        <section class="content">
            <div class="box box-solid">
                <div class="overlay" ng-if="allLoading">
                    <span class="fa">
                        <i class="fa fa-refresh fa-spin"></i> <b>Loading...</b>
                    </span>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Select a Unit&nbsp;
                                    <img ng-show="loadingDistrict" src="{{asset('dist/img/facebook.gif')}}"
                                         width="16"></label>
                                <select class="form-control" ng-model="selectedDistrict" ng-change="loadThana(selectedDistrict)">
                                    <option value="all">All</option>
                                    <option ng-repeat="d in districts" value="[[d.id]]">[[d.unit_name_bng]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Select a Thana&nbsp;
                                    <img ng-show="loadingThana" src="{{asset('dist/img/facebook.gif')}}"
                                         width="16">
                                </label>
                                <select class="form-control" ng-model="selectedThana" ng-change="loadTotal()">
                                    <option value="all">All</option>
                                    <option ng-repeat="t in thanas" value="[[t.id]]">[[t.thana_name_bng]]</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h4>Total Ansars: [[total.toLocaleString()]]</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL. No</th>
                                <th>Ansar id</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Unit</th>
                                <th>Thana</th>
                                <th>Date of Birth</th>
                                <th>Sex</th>
                            </tr>
                            <tbody ng-bind-html="ansars" style="border:none;">
                            <tr>
                                <td class="warning" colspan="8">No Ansar Found</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="table_pagination" ng-if="pages.length>1">
                            <ul class="pagination">
                                <li ng-class="{disabled:currentPage == 0}">
                                    <a href="#" ng-click="loadPage(pages[0],$event)">&laquo;&laquo;</a>
                                </li>
                                <li ng-class="{disabled:currentPage == 0}">
                                    <a href="#" ng-click="loadPage(pages[currentPage-1],$event)">&laquo;</a>
                                </li>
                                <li ng-repeat="page in pages|filter:filterMiddlePage"
                                    ng-class="{active:page.pageNum==currentPage&&!loadingPage[page.pageNum],disabled:!loadingPage[page.pageNum]&&loadingPage[currentPage]}">
                                    <span ng-show="currentPage == page.pageNum&&!loadingPage[page.pageNum]">[[page.pageNum+1]]</span>
                                    <a href="#" ng-click="loadPage(page,$event)" ng-hide="currentPage == page.pageNum||loadingPage[page.pageNum]">[[page.pageNum+1]]</a>
                                    <span ng-show="loadingPage[page.pageNum]"  style="position: relative"><i class="fa fa-spinner fa-pulse" style="position: absolute;top:10px;left: 50%;margin-left: -9px"></i>[[page.pageNum+1]]</span>
                                </li>
                                <li ng-class="{disabled:currentPage==pages.length-1}">
                                    <a href="#" ng-click="loadPage(pages[currentPage+1],$event)">&raquo;</a>
                                </li>
                                <li ng-class="{disabled:currentPage==pages.length-1}">
                                    <a href="#" ng-click="loadPage(pages[pages.length-1],$event)">&raquo;&raquo;</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop