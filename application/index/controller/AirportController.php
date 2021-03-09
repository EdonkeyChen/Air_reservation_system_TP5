<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 *
 */
class AirportController extends Controller
{
    public function jumpToAirport()
    {
        return $this->fetch('airport/Airport');
    }

    public function findAllDailyFlightForAirport()
    {
        $year = getdate()['year'];
        $mon = getdate()['mon'];
        $mday = getdate()['mday'];
        $hours = getdate()['hours'];
        $minutes = getdate()['minutes'];

        if ($mon < 10) {
            $mon = "0" . $mon;
        }
        if ($mday < 10) {
            $mday = "0" . $mday;
        }

        $start_time = $hours - 3;
        $end_time = $hours + 3;

        if ($start_time < 10) {
            $start_time = "0" . $start_time;
        }
        if ($end_time < 10) {
            $end_time = "0" . $end_time;
        }
        if ($minutes < 10) {
            $minutes = "0" . $minutes;
        }

        $year = $year . "-" . $mon . "-" . $mday;
        $start_time = $start_time . ":" . $minutes;
        $end_time = $end_time . ":" . $minutes;

        $query_sql = "select Daily_Flight.*,C.airport_name as dAirport_name,A.terminal_name as dTerminal_name,
		D.airport_name as aAirport_name,B.terminal_name as aTerminal_name
		from Flight,Terminal as A,Terminal as B,Airport as C,Airport as D,Daily_Flight
		where  Flight.terminal_no = A.terminal_no and A.airport_no = C.airport_no
		and Flight.Ter_terminal_no = B.terminal_no and B.airport_no = D.airport_no
		and Daily_Flight.flight_no = Flight.flight_no
		and flight_date = ?
		and estimated_departure_time between ? and ?
		order by scheduled_departure_time asc";

        $flight = Db::query($query_sql,[$year, $start_time, $end_time]);

        $this->assign('flight',$flight);

        return $this->fetch('airport/FlightDynamicStatus');
    }

    public function jumpToModifyFlightDynamic()
    {
        $daily_flight_no = Request::instance() -> param('daily_flight_no');
        $flight_no = Request::instance() -> param('flight_no');
        $flight_date = Request::instance() -> param('flight_date');

        $this->assign('daily_flight_no',$daily_flight_no);
        $this->assign('flight_no',$flight_no);
        $this->assign('flight_date',$flight_date);

        return $this->fetch('airport/ModifyFlightDynamic');
    }

    public function modifyFlightDynamic()
    {
        $actual_departure_time = Request::instance() -> param('actual_departure_time');
        $actual_arrival_time = Request::instance() -> param('actual_arrival_time');
        $daily_flight_status = Request::instance() -> param('daily_flight_status');
        $daily_flight_no = Request::instance() -> param('daily_flight_no');
        $flight_no = Request::instance() -> param('flight_no');
        $flight_date = Request::instance() -> param('flight_date');

        $update_sql_dailyFlight= "update Daily_FLight set
			actual_departure_time = ?,
			actual_arrival_time = ?,
			daily_flight_status = ?
			where daily_flight_no = ?";

        $update_sql_report = "update Report
			set daily_flight_status = ?
			where flight_no = ?
				and flight_date = ?";

        Db::execute($update_sql_dailyFlight,[$actual_departure_time,$actual_arrival_time,$daily_flight_status,$daily_flight_no]);
        Db::execute($update_sql_report,[$daily_flight_status,$flight_no,$flight_date]);

        return $this->fetch('airport/Airport');
    }
}

 ?>
