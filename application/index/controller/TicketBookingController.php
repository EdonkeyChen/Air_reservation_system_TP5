<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\City;

/**
 *
 */
class TicketBookingController extends Controller
{
    public function getAllCity()
    {
        $result = City::all();
        $allCity = [];
        foreach ($result as $key => $value) {
            $allCity[] = $value->toArray();
        };
        $this->assign('allCity',$allCity);
    }
    public function findAllDailyFlight()
    {
        $this -> getAllCity();

        $queryinformation['Departure'] = Request::instance() -> param('Departure');
        $queryinformation['Arrival'] = Request::instance() -> param('Arrival');
        $queryinformation['DepartureDate'] = Request::instance() -> param('Departure-Date');

        $sql="select Flight.*,C.airport_name as dAirport_name,A.terminal_name as dTerminal_name,
		D.airport_name as aAirport_name,B.terminal_name as aTerminal_name,aircraft_name,
		Ticket_Price.*,seat_type_name,luggage_limit,refund_change_rules
		from Flight,Terminal as A,Terminal as B,Airport as C,Airport as D,Aircraft,Daily_Flight,Ticket_Price,Seat_Type
		where Flight.flight_no in (
			select flight_no from Daily_Flight
				where
					flight_no in (
						select flight_no from Flight where
						terminal_no in (
							select terminal_no from Terminal where airport_no in (
								select airport_no from Airport where city_no in (
									select city_no from City where city_name = ?)))
						and
						Ter_terminal_no in (
							select terminal_no from Terminal where airport_no in (
								select airport_no from Airport where city_no in (
									select city_no from City where city_name = ?)))
					)
			)
		and Flight.terminal_no = A.terminal_no and A.airport_no = C.airport_no
		and Flight.Ter_terminal_no = B.terminal_no and B.airport_no = D.airport_no
		and Flight.aircraft_no = Aircraft.aircraft_no
		and Daily_Flight.flight_no = Flight.flight_no
		and flight_date = ?
		and Daily_Flight.daily_flight_no = Ticket_Price.daily_flight_no
		and Ticket_Price.seat_type_no = Seat_Type.seat_type_no
		order by scheduled_departure_time asc, price asc";

        $DB = new Db;
        $flights=$DB::query($sql,[$queryinformation['Departure'] ,$queryinformation['Arrival'],$queryinformation['DepartureDate']]);
        $thicketBookings = [];
        $index = 0;
        foreach ($flights as $key => $value) {
            if ($thicketBookings == null){
                $thicketBookings[] = array_slice($value,0,13);
                $thicketBookings[0] += array("list" => array(array_slice($value,13,21)));
            }
            elseif ($thicketBookings[$index]['flight_no'] == $value['flight_no']) {
                $thicketBookings[$index]['list'][] = array_slice($value,13,21);
            }
            else {
                $thicketBookings[] = array_slice($value,0,13);
                $index = $index + 1;
                $thicketBookings[$index] += array("list" => array(array_slice($value,13,21)));
            }
        }

        $this->assign('queryinformation',$queryinformation);
        $this->assign('thicketBookings',$thicketBookings);
        session('TickerBookingFlag',true);
        // dump($thicketBookings);
        return $this->fetch('client/TicketBooking');
    }
}

 ?>
