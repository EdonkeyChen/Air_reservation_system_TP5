<?php
namespace app\index\controller;

use think\Db;
use think\Controller;

use app\index\model\Account;
use app\index\model\Aircraft;
use app\index\model\AircraftSeat;
use app\index\model\Airport;
use app\index\model\City;
use app\index\model\DailyFlight;
use app\index\model\Flight;
use app\index\model\OrderDetail;
use app\index\model\OrderPrimary;
use app\index\model\Passenger;
use app\index\model\Report;
use app\index\model\SeatType;
use app\index\model\Terminal;
use app\index\model\TicketPrice;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    public function testAccount()
    {
        $result = Account::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testAircraft()
    {
        $result = Aircraft::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testAircraftSeat()
    {
        $result = AircraftSeat::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testAirport()
    {
        $result = DailyFlight::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testCity()
    {
        $result = City::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testDailyFlight()
    {
        $result = DailyFlight::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testFlight()
    {
        $result = Flight::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testOrderDetail()
    {
        $result = OrderDetail::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testOrderPrimary()
    {
        $result = OrderPrimary::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testPassenger()
    {
        $result = Passenger::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testReport()
    {
        $result = Report::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testSeatType()
    {
        $result = SeatType::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testTerminal()
    {
        $result = Terminal::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }

    public function testTicketPrice()
    {
        $result = TicketPrice::all();
        foreach ($result as $key => $value) {
            dump($value->toArray());
        }
    }
}
