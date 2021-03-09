<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 *
 */
class OrderController extends Controller
{

    public function jumpToFillOrder()
    {
        $flight_date = Request::instance() -> param('flight_date');
        $ticket_price_no = Request::instance() -> param('ticket_price_no');
        $ticket_price = Request::instance() -> param('ticket_price');
        $flight_no = Request::instance() -> param('flight_no');
        $dCity = Request::instance() -> param('dCity');
        $aCity = Request::instance() -> param('aCity');
        $aircraft = Request::instance() -> param('aircraft');

        $this->assign('flight_date',$flight_date);
        $this->assign('ticket_price_no',$ticket_price_no);
        $this->assign('ticket_price',$ticket_price);
        $this->assign('flight_no',$flight_no);
        $this->assign('dCity',$dCity);
        $this->assign('aCity',$aCity);
        $this->assign('aircraft',$aircraft);

        session('fillOrderSwitch',1);

        return $this->fetch('client/FillOrder');
    }

    public function orderConfirmPre()
    {
        $flight_date = Request::instance() -> param('flight_date');
        $ticket_price_no = Request::instance() -> param('ticket_price_no');
        $ticket_price = Request::instance() -> param('ticket_price');
        $flight_no = Request::instance() -> param('flight_no');
        $dCity = Request::instance() -> param('dCity');
        $aCity = Request::instance() -> param('aCity');
        $aircraft = Request::instance() -> param('aircraft');
        $passengerNum = Request::instance() -> param('passengerNum');

        $this->assign('flight_date',$flight_date);
        $this->assign('ticket_price_no',$ticket_price_no);
        $this->assign('ticket_price',$ticket_price);
        $this->assign('flight_no',$flight_no);
        $this->assign('dCity',$dCity);
        $this->assign('aCity',$aCity);
        $this->assign('aircraft',$aircraft);
        $this->assign('passengerNum',$passengerNum);

        session('fillOrderSwitch',2);

        return $this->fetch('client/FillOrder');
    }

    public function orderConfirm()
    {
        $flight_no = Request::instance() -> param('flight_no');
        $flight_date = Request::instance() -> param('flight_date');
        $ticket_price_no = Request::instance() -> param('ticket_price_no');
        $passengerNum = Request::instance() -> param('passengerNum');
        $ticket_price = Request::instance() -> param('ticket_price');
        $ticket_price_total = Request::instance() -> param('ticket_price_total');
        $passengerList = [];

        for ($i=0; $i < $passengerNum ; $i++) {
            $passengerList[$i]['passenger_name'] = Request::instance() -> param('passenger_name' . $i);
            $passengerList[$i]['certificate_type'] = Request::instance() -> param('certificate_type' . $i);
            $passengerList[$i]['certificate_no'] = Request::instance() -> param('certificate_no' . $i);
            $passengerList[$i]['passenger_tel'] = Request::instance() -> param('passenger_tel' . $i);
        }

        $this->assign('flight_no', $flight_no);
        $this->assign('flight_date', $flight_date);
        $this->assign('ticket_price_no', $ticket_price_no);
        $this->assign('passengerNum', $passengerNum);
        $this->assign('ticket_price', $ticket_price);
        $this->assign('ticket_price_total', $ticket_price_total);
        $this->assign('passengerList', $passengerList);

        return $this->fetch('client/OrderConfirm');
    }

    public function orderConfirmComplete()
    {
        $account_id = session('account_id');
        $account_name = session('account_name');
        $flight_no = Request::instance() -> param('flight_no');
        $flight_date = Request::instance() -> param('flight_date');
        $ticket_price_total = Request::instance() -> param('ticket_price_total');
        $passengerNum = Request::instance() -> param('passengerNum');
        $ticket_price_no = Request::instance() -> param('ticket_price_no');
        $ticket_price = Request::instance() -> param('ticket_price');
        $passengerList = session('passengerList');
        $payer_name = Request::instance() -> param('payer_name');
        $payer_tel = Request::instance() -> param('payer_tel');
        $payer_email = Request::instance() -> param('payer_email');

        $data_orderPrimary = [
            'account_id' => $account_id,
            'total_price' => $ticket_price_total,
            'order_date' => $flight_date,
            'passenger_num' => $passengerNum,
            'order_status' => '1',
            'payer_name' => $payer_name,
            'payer_tel' => $payer_tel,
            'payer_email' => $payer_email
        ];

        $order_primary_no = Db::name("OrderPrimary") -> insertGetId($data_orderPrimary);

        foreach ($passengerList as $key => $value) {
            $data_passenger = [
                'passenger_name' => $value['passenger_name'],
                'certificate_type' => $value['certificate_type'],
                'certificate_no' => $value['certificate_no'],
                'passenger_tel' => $value['passenger_tel']
            ];
            $passenger_no = Db::name("Passenger") -> insertGetId($data_passenger);

            $data_orderDetail = [
                'passenger_no' => $passenger_no,
                'ticket_price_no' => $ticket_price_no,
                'order_primary_no' => $order_primary_no,
                'ticket_price' => $ticket_price,
                'order_detail_status' => '0',
                'construction_fee' => '50',
                'fuel_surcharge' => '0'
            ];

            Db::name("OrderDetail") -> insert($data_orderDetail);
        }

        $query_sql_ticketPrice = "select ticket_price_no from Ticket_Price,Seat_Type
            where Ticket_Price.seat_type_no = Seat_Type.seat_type_no and ticket_price_no in(
                select ticket_price_no from Ticket_Price where daily_flight_no in (
                    select daily_flight_no from Ticket_Price where ticket_price_no = ?
                )
            ) and Seat_Type.real_type in(
                select Seat_Type.real_type from Ticket_Price,Seat_Type
                where Ticket_Price.seat_type_no = Seat_Type.seat_type_no
                and ticket_price_no = ?
            )";

        $list_ticketPrice = Db::query($query_sql_ticketPrice,[$ticket_price_no,$ticket_price_no]);
        foreach ($list_ticketPrice as $key => $value) {
            $update_sql_ticketPrice = "update Ticket_Price set ticket_num = ticket_num - ? where ticket_price_no = ?";
            Db::execute($update_sql_ticketPrice,[$passengerNum,$value['ticket_price_no']]);
        }

        $data_report = [
            'flight_no' => $flight_no,
            'flight_date' => $flight_date,
            'price' => $ticket_price,
            'ticket_num' => $passengerNum,
            'daily_flight_status' => 'Schedule'
        ];

        Db::name("Report") -> insert($data_report);

        session('orderConfirmFlag',true);

        return $this->fetch('client/OrderConfirmComplete');
    }
}

 ?>
