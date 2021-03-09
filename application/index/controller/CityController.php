<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\index\model\City;
/**
 *
 */
class CityController extends Controller
{
    public function getAllCity()
    {
        $result = City::all();
        $allCity = [];
        foreach ($result as $key => $value) {
            $allCity[] = $value->toArray();
        };
        $this->assign('allCity',$allCity);
        return $this->fetch('client/Main');
    }
}

 ?>
