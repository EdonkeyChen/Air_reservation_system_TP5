<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 *
 */
class ReportController extends Controller
{
    public function jumpToFunctionChoose()
    {
        return $this->fetch('admin/FunctionChoose');
    }

    public function jumpToReportPrint()
    {
        return $this->fetch('admin/ReportPrint');
    }

    public function printReport()
    {
        $year = Request::instance() -> param('year');
        $month = Request::instance() -> param('month');

        if ($month < 10) {
            $month = "0" . $month;
        }else {
            $year = substr($year, 0, 5);
        }

        $query_sql = "select
    	flight_no,
    	sum(total_ticket) as 'total_ticket',
    	sum(total_sold_ticket) as 'total_sold_ticket',
        sum(total_tevenue) as 'total_tevenue',
    	sum(on_time_rate) / count(flight_no) as 'on_time_rate',
    	sum(flight_count) as 'flight_count'
    	from (select flight_no,
		       '92' as 'total_ticket',
		       sum(ticket_num) as 'total_sold_ticket',
		       sum(price) as 'total_tevenue',
		       round((count(daily_flight_status) -
		              count(case when (daily_flight_status = 'Delay') then 1 else null end)) /
		              count(daily_flight_status), 2) as 'on_time_rate',
		       '1' as 'flight_count'
		from Report
		where flight_date between concat(?,?,'-01') and concat(?,?,'-30')
		group by flight_no,flight_date) as a group by flight_no";



        $report = Db::query($query_sql,[$year, $month, $year, $month]);

        $year = substr($year, 0, 4);

        $this->assign('report',$report);
        $this->assign('year',$year);
        $this->assign('month',$month);

        session('reportPrintSwitch',2);

        return $this->fetch('admin/ReportPrint');
    }
}

 ?>
