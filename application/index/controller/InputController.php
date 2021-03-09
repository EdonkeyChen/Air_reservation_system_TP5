<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

use app\index\model\Aircraft;
use app\index\model\AircraftSeat;
use app\index\model\Airport;
use app\index\model\City;
use app\index\model\DailyFlight;
use app\index\model\Flight;
use app\index\model\SeatType;
use app\index\model\Terminal;
use app\index\model\TicketPrice;

 /**
  *
  */
class InputController extends Controller
{

    public function jumpToFunctionChoose()
    {
        return $this->fetch('admin/FunctionChoose');
    }

    public function initCityAndAirport()
    {
        $resultCity = City::all();
        $allCity = [];
        foreach ($resultCity as $key => $value) {
            $allCity[] = $value->toArray();
        };

        $resultAirport = Airport::all();
        $allAirport = [];
        foreach ($resultAirport as $key => $value) {
            $allAirport[] = $value->toArray();
        };

        $this->assign('allCity',$allCity);
        $this->assign('allAirport',$allAirport);
    }

    //Airport
    public function jumpToAirportInput()
    {
        $resultCity = City::all();
        $allCity = [];
        foreach ($resultCity as $key => $value) {
            $allCity[] = $value->toArray();
        };

        $resultAirport = Airport::all();
        $allAirport = [];
        foreach ($resultAirport as $key => $value) {
            $allAirport[] = $value->toArray();
        };

        $this->assign('allCity',$allCity);
        $this->assign('allAirport',$allAirport);

        return $this->fetch('admin/AirportInput');
    }

    public function addAirport()
    {
        $city_name = Request::instance() -> param('city_name');
        $airport_name = Request::instance() -> param('airport_name');

        $insert_sql = "insert into Airport (city_no, airport_name)
			values((select city_no from City where city_name = ?),?)";

        Db::execute($insert_sql,[$city_name,$airport_name]);

        session('addAirportFlag',true);

        $this->initCityAndAirport();

        return $this->fetch('admin/AirportInput');
    }

    public function addTerminal()
    {
        $airport_name = Request::instance() -> param('airport_name');
        $terminal_name = Request::instance() -> param('terminal_name');

        $insert_sql = "insert into Terminal (airport_no, terminal_name)
			values ((select airport_no from Airport where airport_name = ?), ?)";

        Db::execute($insert_sql,[$airport_name,$terminal_name]);

        session('addTerminalFlag',true);

        $this->initCityAndAirport();

        return $this->fetch('admin/AirportInput');
    }

    //Aircraft
    public function jumpToAircraftInput()
    {
        return $this->fetch('admin/AircraftInput');
    }

    public function addAircraft()
    {
        $aircraft_name = Request::instance() -> param('aircraft_name');

        $insert_sql = "insert into Aircraft (aircraft_name) values (?)";

        Db::execute($insert_sql,[$aircraft_name]);

        session('addAircraftFlag',true);

        return $this->fetch('admin/AircraftInput');
    }

    //SeatType
    public function jumpToSeatTypeInput()
    {
        return $this->fetch('admin/SeatTypeInput');
    }

    public function addSeatType($value='')
    {
        $seat_type_name = Request::instance() -> param('seat_type_name');

        $change1 = Request::instance() -> param('change1');
        $refund1 = Request::instance() -> param('refund1');
        $change2 = Request::instance() -> param('change2');
        $refund2 = Request::instance() -> param('refund2');
        $change3 = Request::instance() -> param('change3');
        $refund3 = Request::instance() -> param('refund3');
        $change4 = Request::instance() -> param('change4');
        $refund4 = Request::instance() -> param('refund4');

        $refund_change_rules =
			"7 days before take-off&#9;&#9;&#9;" . $change1 . "%&#9;&#9;" . $refund1 . "%" . "\n" .
			"7 days and 48 hours before take-off&#9;" . $change2 . "%&#9;&#9;" . $refund2 . "%" . "\n" .
			"48 hours and 4 hours before take-off&#9;" . $change3 . "%&#9;&#9;" . $refund3 . "%" . "\n" .
			"4 hours before take-off&#9;&#9;&#9;" . $change4 . "%&#9;&#9;" . $refund4 . "%";

        $real_type = Request::instance() -> param('real_type');

        $insert_sql = "insert into Seat_Type (seat_type_name, luggage_limit, refund_change_rules,real_type)
			values (?,'Free baggage allowance of 20kg',?,?)";

        Db::execute($insert_sql,[$seat_type_name,$refund_change_rules,$real_type]);

        session('addSeatTypeFlag',true);

        return $this->fetch('admin/SeatTypeInput');
    }

    //FlightInput
    public function jumpToFlightInput()
    {
        $resultCity = City::all();
        $allCity = [];
        foreach ($resultCity as $key => $value) {
            $allCity[] = $value->toArray();
        };

        $this->assign('allCity',$allCity);

        return $this->fetch('admin/FlightInput');
    }

    public function findAllAirportForFlightInput()
    {
        $departure_city = Request::instance() -> param('departure_city');
        $arrival_city = Request::instance() -> param('arrival_city');

        $query_sql_d = "select * from Airport where city_no in (select city_no from City where city_name = ?)";
        $query_sql_a = "select * from Airport where city_no in (select city_no from City where city_name = ?)";

        $dAirport = Db::query($query_sql_d,[$departure_city]);
        $aAirport = Db::query($query_sql_a,[$arrival_city]);

        $this->assign('dAirport',$dAirport);
        $this->assign('aAirport',$aAirport);

        session('flightInputSwitch',2);

        return $this->fetch('admin/FlightInput');
    }

    public function findAllTerminalForFlightInput()
    {
        $departure_airport = Request::instance() -> param('departure_airport');
        $arrival_airport = Request::instance() -> param('arrival_airport');

        $query_sql_d = "select * from Terminal where airport_no in (select airport_no from Airport where airport_name = ?)";
        $query_sql_a = "select * from Terminal where airport_no in (select airport_no from Airport where airport_name = ?)";
        $query_sql_aircraft = "select * from Aircraft";

        $dTerminal = Db::query($query_sql_d,[$departure_airport]);
        $aTerminal = Db::query($query_sql_a,[$arrival_airport]);
        $allAircraft = Db::query($query_sql_aircraft);

        $this->assign('dTerminal',$dTerminal);
        $this->assign('aTerminal',$aTerminal);
        $this->assign('allAircraft',$allAircraft);

        session('flightInputSwitch',3);

        return $this->fetch('admin/FlightInput');
    }

    public function addFlight()
    {
        $flight_no = Request::instance() -> param('flight_no');
        $terminal_no = Request::instance() -> param('terminal_no');
        $Ter_terminal_no = Request::instance() -> param('Ter_terminal_no');
        $aircraft_no = Request::instance() -> param('aircraft_no');
        $scheduled_departure_time = Request::instance() -> param('scheduled_departure_time');
        $scheduled_arrival_time = Request::instance() -> param('scheduled_arrival_time');
        $catering = Request::instance() -> param('catering');
        $flight_time = Request::instance() -> param('flight_time');

        $insert_sql = "insert into Flight (flight_no, terminal_no, Ter_terminal_no, aircraft_no,
			scheduled_departure_time, scheduled_arrival_time, catering, flight_time) values (?,?,?,?,?,?,?,?)";

        Db::execute($insert_sql,[$flight_no,$terminal_no,$Ter_terminal_no,$aircraft_no,$scheduled_departure_time,$scheduled_arrival_time,$catering,$flight_time]);

        session('flightInputSwitch',1);
        session('addFlightFlag',true);

        $allCity = Db::query("select * from City");
        $this->assign('allCity',$allCity);

        return $this->fetch('admin/FlightInput');
    }

    //DailyFlightInput
    public function jumpToDailyFlightInput()
    {
        $resultCity = City::all();
        $allCity = [];
        foreach ($resultCity as $key => $value) {
            $allCity[] = $value->toArray();
        };

        $this->assign('allCity',$allCity);

        return $this->fetch('admin/DailyFlightInput');
    }

    public function findAllFlightForDailyFlightInput()
    {
        $departure_city = Request::instance() -> param('departure_city');
        $arrival_city = Request::instance() -> param('arrival_city');

        $query_sql = "select * from Flight where
		terminal_no in (select Terminal_no from Terminal where Airport_no in (
			select Airport_no from Airport where City_no in (
				select City_no from City where City_name = ?)))
		and
		Ter_terminal_no in (
			select Terminal_no from Terminal where Airport_no in (
				select Airport_no from Airport where City_no in (
					select City_no from City where City_name = ?)))
		order by scheduled_departure_time asc";

        $flight = Db::query($query_sql,[$departure_city,$arrival_city]);

        $this->assign('flight',$flight);

        session('dailyFlightInputSwitch',2);

        return $this->fetch('admin/DailyFlightInput');
    }

    public function addDailyFlight()
    {
        $flight_no = Request::instance() -> param('flight_no');
        $flight_date = Request::instance() -> param('flight_date');
        $actual_departure_time = Request::instance() -> param('actual_departure_time');
        $actual_arrival_time = Request::instance() -> param('actual_arrival_time');
        $estimated_departure_time = Request::instance() -> param('estimated_departure_time');
        $estimated_arrival_time = Request::instance() -> param('estimated_arrival_time');

        $insert_sql = "insert into Daily_Flight (flight_no, flight_date, actual_departure_time,actual_arrival_time,
			estimated_departure_time, estimated_arrival_time, daily_flight_status)
		values (?, ?, ?, ?, ?, ?, 'Schedule')";

        Db::execute($insert_sql,[$flight_no,$flight_date,$actual_departure_time,$actual_arrival_time,$estimated_departure_time,$estimated_arrival_time]);

        session('dailyFlightInputSwitch',1);
        session('addDailyFlightFlag',true);

        $allCity = Db::query("select * from City");
        $this->assign('allCity',$allCity);

        return $this->fetch('admin/DailyFlightInput');
    }

    //TicketPriceInput
    public function jumpToTicketPriceInput()
    {
        $resultCity = City::all();
        $allCity = [];
        foreach ($resultCity as $key => $value) {
            $allCity[] = $value->toArray();
        };

        $this->assign('allCity',$allCity);

        return $this->fetch('admin/TicketPriceInput');
    }

    public function findAllDailyFlightForTicketPriceInput()
    {
        $dCity = Request::instance() -> param('dCity');
        $aCity = Request::instance() -> param('aCity');
        $flight_date = Request::instance() -> param('flight_date');

        $query_sql = "select * from Daily_Flight
		where
			flight_no in (
				select flight_no from Flight where
				terminal_no in (select Terminal_no from Terminal where Airport_no in (
					select Airport_no from Airport where City_no in (
						select City_no from City where City_name = ?)))
				and
				Ter_terminal_no in (select Terminal_no from Terminal where Airport_no in (
					select Airport_no from Airport where City_no in (
						select City_no from City where City_name = ?)))
			) and
			flight_date = ? order by estimated_departure_time asc";

        $daliyFlight = Db::query($query_sql,[$dCity,$aCity,$flight_date]);

        $this->assign('daliyFlight',$daliyFlight);

        session('ticketPriceInputSwitch',2);

        $seatType = Db::query("select * from Seat_Type");

        $this->assign('seatType',$seatType);

        return $this->fetch('admin/TicketPriceInput');
    }

    public function addTicketPrice()
    {
        $daily_flight_no = Request::instance() -> param('daily_flight_no');
        $seat_type_no = Request::instance() -> param('seat_type_no');
        $price = Request::instance() -> param('price');
        $ticket_num = Request::instance() -> param('ticket_num');

        $insert_sql = "insert into Ticket_Price (daily_flight_no, seat_type_no, price, ticket_num)
		values (?,?,?,?)";

        Db::execute($insert_sql,[$daily_flight_no,$seat_type_no,$price,$ticket_num]);

        $allCity = Db::query("select * from City");
        $this->assign('allCity',$allCity);

        session('ticketPriceInputSwitch',1);
        session('addTicketPriceFlag',true);

        return $this->fetch('admin/TicketPriceInput');
    }
}

?>
