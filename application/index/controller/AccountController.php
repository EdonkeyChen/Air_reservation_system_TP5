<?php
namespace app\index\controller;

use app\index\model\Account;
use think\Controller;
use think\Request;
use app\index\model\City;

/**
 *
 */
class AccountController extends Controller
{
    public function jumpToLogin()
    {
        return $this->fetch('client/Login');
    }

    public function jumpToRegister()
    {
        return $this->fetch('client/Register');
    }

    public function jumpToLogout()
    {
        return $this->fetch('client/Logout');
    }

    public function findAccountByTel()
    {
        $loginFlag = false;
        $url = "client/Main";

        $account_tel = Request::instance() -> param('account_tel');
        $account_pwd = Request::instance() -> param('account_pwd');
        $result = Account::getByAccountTel($account_tel);
        if ($result != null) {
            $resultArray = $result->toArray();
            if ($resultArray['account_tel'] == $account_tel && $resultArray['account_pwd'] == $account_pwd) {
                session('account_id',$resultArray['account_id']);
                session('account_name',$resultArray['account_name']);
                if ($resultArray['account_type'] == 1) {
                    session('account_type',$resultArray['account_type']);
                    $url = "admin/FunctionChoose";
                }else if ($resultArray['account_type'] == 2) {
                    session('account_type',$resultArray['account_type']);
                    $url = "airport/Airport";
                }else{
                    $result = City::all();
                    $allCity = [];
                    foreach ($result as $key => $value) {
                        $allCity[] = $value->toArray();
                    };
                    $this->assign('allCity',$allCity);
                    $url = "client/Main";
                    return $this->fetch($url);
                }
            }else{
                $loginFlag = true;
                $url = "client/Login";
            }
        }else{
            $loginFlag = true;
            $url = "client/Login";
        }
        session('loginFlag',$loginFlag);
        return $this->fetch($url);
    }

    public function addAccount()
    {
        $registerFlag = true;
        $registerCheckFlag = false;
        $url = "client/Register";

        $account_name = Request::instance() -> param('account_name');
        $account_tel = Request::instance() -> param('account_tel');
        $account_pwd = Request::instance() -> param('account_pwd');

        try {
            $account = Account::create([
                'account_tel' => $account_tel,
                'account_name' => $account_name,
                'account_pwd' => $account_pwd
            ]);
            session('account_id',$account -> account_id);
            session('account_name',$account -> account_name);
        } catch (\Exception $e) {
            $registerFlag = false;
            $registerCheckFlag = true;
        }
        session('registerFlag',$registerFlag);
        session('registerCheckFlag',$registerCheckFlag);
        return $this->fetch($url);
    }


}

 ?>
