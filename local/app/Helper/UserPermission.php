<?php
/**
 * Created by PhpStorm.
 * User: arafat
 * Date: 7/26/2016
 * Time: 12:22 AM
 */

namespace App\Helper;


use App\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UserPermission
{
    private $permissionFile = 'test_list.json';
    private $permissionList;
    private $currentUserPermission;
    private $search;

    public function __construct()
    {
        $permissions = file_get_contents(storage_path("user/permission/{$this->permissionFile}"));
        $this->permissionList = Config::get('permission.permission_list');
        $this->currentUserPermission = Auth::user()->userPermission->permission_list;
        $this->search = '';
    }

    public function getPermissionList()
    {
        return $this->permissionList->all();
    }

    public function isPermissionExists($name)
    {
        $status = false;
        foreach($this->permissionList as $search){
            $status = preg_match('/('.$name.')/',$search);
            if($status) break;
        }
        return $status;
    }
    public function userPermissionExists($name)
    {
        if (is_null($this->currentUserPermission)) {
            if(Auth::user()->type==11||Auth::user()->type==33)
                return true;
            else return false;
        }
        $status = false;
        $p = $this->currentUserPermission;
        foreach(json_decode($p) as $search){
            $status = preg_match('/('.$name.')/',$search);
            if($status) break;
        }
        return $status;
    }
    public function isUserMenuExists($name,$p)
    {
//        return false;
        Log::info("Found:".$name);
        $status = false;
        foreach($p as $search){
            $status = preg_match('/('.$name.')/',$search);
            if($status) break;
        }

        return $status;
    }

    public function isMenuExists($value)
    {
        if (is_null($this->currentUserPermission)) {
            if(Auth::user()->type==11||Auth::user()->type==33)
            return true;
            else return false;
        }
        $p = json_decode($this->currentUserPermission);
        if (is_array($value)) {
            return $this->checkMenu($value,$p);
        }
        else return $this->isUserMenuExists($value,$p);
    }

    public function getTotal()
    {
        return $this->permissionList->count();
    }

    public function checkMenu($array,$p)
    {
        foreach($array as $a){
            if($a['route']=="#"){
                return $this->checkMenu($a['children'],$p);
            }
            else if($this->isUserMenuExists($a['route'],$p)){
                return true;
            }
        }
        return false;
    }

    public function getPageItem($page, $count)
    {
        return $this->permissionList->forPage($page, $count)->all();
    }

    public function getCurrentUserPermission()
    {
        if (is_null($this->currentUserPermission)) {
            return null;
        } else return json_decode($this->currentUserPermission);
    }

    public function getUserPermission($id)
    {
        $p = User::find($id)->userPermission->permission_list;
        //var_dump($p);
        if (!$p) {
            return null;
        } else return json_decode($p);
    }

}