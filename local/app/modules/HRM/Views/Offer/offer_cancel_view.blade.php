{{--Offer Cancel Complete--}}

@extends('template.master')
@section('title','Cancel Offer')
@section('breadcrumb')
    {!! Breadcrumbs::render('offer_cancel') !!}
@endsection
@section('content')
    <script>
        GlobalApp.controller('OfferCancelController', function ($scope, $http,$sce) {
            $scope.selectedDistrict = "";
            $scope.noAnsar = true;
            $scope.showLoadScreen = true;
            $scope.loadingAnsar = false;
            $scope.selectAnsar = []
            $scope.canceledAnsar = []
            $scope.selectAll = false;
            $scope.result = {};
            $scope.loadingUnit = true;
            $http({
                url: '{{URL::to('HRM/DistrictName')}}',
                type: 'get'
            }).then(function (response) {
                $scope.allDistrict = response.data;
                $scope.loadingUnit = false;
            }, function (response) {
                if(response.status==500){
                    $scope.errorVisible = true;
                }
                $scope.loadingUnit = false;
            })
            $scope.loadAnsar = function () {
                //alert($scope.selectedDistrict)
                $scope.loadingAnsar = true;
                $http({
                    url: '{{URL::to('HRM/get_offered_ansar_info')}}',
                    method: 'get',
                    params: {district_id: $scope.selectedDistrict}
                }).then(function (response) {
//                    /alert(JSON.stringify(response));
                    $scope.errorLoad=undefined;
                    $scope.selectAll = false;
                    if (response.data.length > 0) {
                        $scope.selectedAnsar = response.data;
                        $scope.noAnsar = false;
                        $scope.selectAnsar = Array.apply(null, new Array(response.data.length)).map(Boolean.prototype.valueOf, false);
                    }
                    else $scope.noAnsar = true;
                    $scope.loadingAnsar = false;
                }, function (response) {
                    $scope.errorLoad = $sce.trustAsHtml(response.data);
                    $scope.loadingAnsar = false;
                })
            }
            $scope.updateValue = function (value, isChecked) {
                var index = $scope.canceledAnsar.indexOf(value);
                if (isChecked) {
                    if (index <= -1) $scope.canceledAnsar.push(value);
                    if ($scope.canceledAnsar.length == $scope.selectAnsar.length) {
                        $scope.selectAll = true;
                    }
                }
                else {
                    $scope.canceledAnsar.splice(index, 1);
                    $scope.selectAll = false;
                }
            }
            $scope.updateSelected = function () {
                $scope.selectAnsar = Array.apply(null, new Array($scope.selectAnsar.length)).map(Boolean.prototype.valueOf, $scope.selectAll);
            }
            $scope.$watch(function (scope) {
                return scope.selectAnsar;
            }, function (n, o) {
                if (n.length == 0 && !$scope.selectAll) return;
                if (o.length == 0) return;
                for (var i = 0; i < $scope.selectedAnsar.length; i++) {
                    $scope.updateValue($scope.selectedAnsar[i].ansar_id, $scope.selectAnsar[i])
                }
            })
            $scope.cancelUpdate = function () {
                //alert($scope.canceledAnsar.length);
                $scope.showLoadScreen = false;
                $http(
                        {
                            url: "{{URL::to('HRM/cancel_offer_handle')}}",
                            data: angular.toJson({"ansar_ids":$scope.canceledAnsar}),
                            method:'post'
                        }
                ).then(function (response) {
                            //alert(JSON.stringify(response.data))
                            $scope.result = response.data;
                            //console.log($scope.result)
                            $scope.showLoadScreen = true;
                        }, function (response) {
                            console.log(response)
                            $scope.showLoadScreen = true;
                    $('body').notifyDialog({
                        type: 'error',
                        message: response.data
                    }).showDialog()
                        })
            }
        })
        GlobalApp.directive('notificationMessage', function () {
            return {
                restrict: 'ACE',
                link: function (scope, elem, attrs) {
                    scope.$watch('result', function (newValue, oldValue) {
                        if (Object.keys(newValue).length > 0) {
                            if (newValue.success > 0) {
                                $('body').notifyDialog({
                                    type: 'success',
                                    message: "Success " + newValue.success + ", Failed " + newValue.fail
                                }).showDialog()
                                scope.loadAnsar();
                            }
                            else {
                                $('body').notifyDialog({
                                    type: 'error',
                                    message: "Transfer Failed. Pleas try again later"
                                }).showDialog()
                            }
                            scope.result = {};
                        }
                    }, true)
                }
            }
        })
    </script>
    <div notification-message ng-controller="OfferCancelController">
        <section class="content">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-md-4">
                            <label class="control-label"> Select a district to cancel offer&nbsp;&nbsp;&nbsp;<i
                                        class="fa fa-spinner fa-pulse" ng-show="loadingAnsar"></i></label>
                            <select class="form-control" ng-model="selectedDistrict"
                                    ng-disabled="loadingAnsar||loadingUnit" ng-change="loadAnsar()">
                                <option value="">--Select a District--</option>
                                <option ng-repeat="d in allDistrict" value="[[d.id]]">[[d.unit_name_bng]]
                                </option>
                            </select>
                            <p ng-if="errorVisible" class="text text-danger">An error occur while loading district name <button class="btn btn-danger btn-xs"><i class="fa fa-refresh" ng-class="{'fa-pulse':loadingAnsar}"></i></button></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="pc-table">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Offer Send Date</th>
                                <th>Offer Expire Date</th>
                                <th>District</th>
                                <th>Sex</th>
                                <th>Designation</th>
                                <th>
                                    <div class="styled-checkbox">
                                        <input type="checkbox" id="all" ng-model="selectAll"
                                               ng-click="updateSelected()">
                                        <label for="all"></label>
                                    </div>
                                    &nbsp;&nbsp<span>Select All</span>
                                </th>
                            </tr>
                            <tr ng-show="noAnsar&&errorLoad==undefined" class="warning">
                                <td colspan="8">No Ansar Found to Send Offer</td>
                            </tr>
                            <tbody ng-if="errorLoad!=undefined" ng-bind-html="errorLoad"></tbody>
                            <tr ng-repeat="ansar in selectedAnsar" ng-hide="noAnsar&&errorLoad==undefined">
                                <td ansar-id="[[ansar.ansar_id]]">[[ansar.ansar_id]]</td>
                                <td>[[ansar.ansar_name_bng]]</td>
                                <td>[[ansar.sms_send_datetime]]</td>
                                <td>[[ansar.sms_end_datetime]]</td>
                                <td>[[ansar.unit_name_bng]]</td>
                                <td>[[ansar.sex]]</td>
                                <td>[[ansar.name_bng]]</td>
                                <td>
                                    <div class="styled-checkbox">
                                        <input type="checkbox" ng-model="selectAnsar[$index]"
                                               value="[[ansar.ansar_id]]" id="s_[[$index]]"
                                               ng-change="updateValue([[ansar.ansar_id]],selectAnsar[$index])">
                                        <label for="s_[[$index]]"></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary pull-right" ng-click="cancelUpdate()">
                <i ng-show="showLoadScreen" class="fa fa-remove"></i><i ng-hide="showLoadScreen"
                                                                        class="fa fa-spinner fa-pulse"></i> Cancel Offer
            </button>
            <div>[[error]]</div>
        </section>
    </div>
@stop