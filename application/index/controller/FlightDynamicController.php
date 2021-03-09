<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\City;
/**
 *
 */
class FlightDynamicController extends Controller
{
    public function getAllCity()
    {
        $result = City::all();
        $allCity = [];
        foreach ($result as $key => $value) {
            $allCity[] = $value->toArray();
        };
        $this->assign('allCity',$allCity);
        // return $this->fetch('client/Main');
    }

    function findAllDailyFlightForDynamic()
    {
        $this -> getAllCity();

        $queryinformation['Departure'] = Request::instance() -> param('Departure');
        $queryinformation['Arrival'] = Request::instance() -> param('Arrival');
        $queryinformation['DepartureDate'] = Request::instance() -> param('Return-Date');
        //dump($ReturnDate);

        $sql = "select Daily_Flight.*,C.airport_name as dAirport_name,A.terminal_name as dTerminal_name,
        D.airport_name as aAirport_name,B.terminal_name as aTerminal_name
        from Flight,Terminal as A,Terminal as B,Airport as C,Airport as D,Daily_Flight
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
        and Daily_Flight.flight_no = Flight.flight_no
        and flight_date = ?
        order by scheduled_departure_time asc";

        $DB=new Db;
        $flights=$DB::query($sql,[$queryinformation['Departure'] ,$queryinformation['Arrival'],$queryinformation['DepartureDate']]);
        // dump($flights);
        $this->assign('queryinformation',$queryinformation);
        $this->assign('flights',$flights);
        session('FlightDynamicFlag',true);
        return $this->fetch('client/FlightDynamic');
    }

    function findAllDailyFlightByFlightNumber(){
        $this -> getAllCity();

        $queryinformation['FlightNumber'] = Request::instance() -> param('Flight-Number');
        $queryinformation['DepartureDate'] = Request::instance() -> param('Return-Date');

        $sql = "select Daily_Flight.*,C.airport_name as dAirport_name,A.terminal_name as dTerminal_name,
            D.airport_name as aAirport_name,B.terminal_name as aTerminal_name,E.city_name as dCity,F.city_name as aCity
            from Flight,Terminal as A,Terminal as B,Airport as C,Airport as D,City as E,City as F,Daily_Flight
            where Flight.flight_no = ?
            and Flight.terminal_no = A.terminal_no and A.airport_no = C.airport_no
            and Flight.Ter_terminal_no = B.terminal_no and B.airport_no = D.airport_no
            and C.city_no = E.city_no and D.city_no = F.city_no
            and Daily_Flight.flight_no = Flight.flight_no
            and flight_date = ?
            order by scheduled_departure_time asc";

        $DB=new Db;
        $flights=$DB::query($sql,[$queryinformation['FlightNumber'],$queryinformation['DepartureDate']]);
        // dump($flights);
        $queryinformation['Departure'] = $flights[0]['dCity'];
        $queryinformation['Arrival'] = $flights[0]['aCity'];
        $this->assign('queryinformation',$queryinformation);
        $this->assign('flights',$flights);
        session('FlightDynamicFlag',false);
        return $this->fetch('client/FlightDynamic');

    }
}

?>
