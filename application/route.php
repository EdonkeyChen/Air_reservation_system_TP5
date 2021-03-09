<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],
//
// ];

use think\Route;
//Client
//Main
Route::rule('/','index/CityController/getAllCity');
//Login
Route::rule('login','index/AccountController/jumpToLogin');
Route::rule('loginCheck','index/AccountController/findAccountByTel');
//Register
Route::rule('register','index/AccountController/jumpToRegister');
Route::rule('registerCheck','index/AccountController/addAccount');
//Logout
Route::rule('logout','index/AccountController/jumpToLogout');
//FlightDynamic
Route::rule('findAllDailyFlightForDynamic','index/FlightDynamicController/findAllDailyFlightForDynamic');
Route::rule('findAllDailyFlightByFlightNumber','index/FlightDynamicController/findAllDailyFlightByFlightNumber');
//TicketBooking
Route::rule('findAllDailyFlight','index/TicketBookingController/findAllDailyFlight');
//OrderConfirm
Route::rule('fillOrder','index/OrderController/jumpToFillOrder');
Route::rule('orderConfirmPre','index/OrderController/orderConfirmPre');
Route::rule('orderConfirm','index/OrderController/orderConfirm');
Route::rule('orderConfirmComplete','index/OrderController/orderConfirmComplete');

//Admin
//FunctionChoose
Route::rule('functionChoose','index/InputController/jumpToFunctionChoose');
//AirportInput
Route::rule('airportInput','index/InputController/jumpToAirportInput');
Route::rule('addAirport','index/InputController/addAirport');
Route::rule('addTerminal','index/InputController/addTerminal');
//AircraftInput
Route::rule('aircraftInput','index/InputController/jumpToAircraftInput');
Route::rule('addAircraft','index/InputController/addAircraft');
//SeatTypeInput
Route::rule('seatTypeInput','index/InputController/jumpToSeatTypeInput');
Route::rule('addSeatType','index/InputController/addSeatType');
//FlightInput
Route::rule('flightInput','index/InputController/jumpToFlightInput');
Route::rule('findAllAirportForFlightInput','index/InputController/findAllAirportForFlightInput');
Route::rule('findAllTerminalForFlightInput','index/InputController/findAllTerminalForFlightInput');
Route::rule('addFlight','index/InputController/addFlight');
//DailyFlightInput
Route::rule('dailyFlightInput','index/InputController/jumpToDailyFlightInput');
Route::rule('findAllFlightForDailyFlightInput','index/InputController/findAllFlightForDailyFlightInput');
Route::rule('addDailyFlight','index/InputController/addDailyFlight');
//TicketPriceInput
Route::rule('ticketPriceInput','index/InputController/jumpToTicketPriceInput');
Route::rule('findAllDailyFlightForTicketPriceInput','index/InputController/findAllDailyFlightForTicketPriceInput');
Route::rule('addTicketPrice','index/InputController/addTicketPrice');
//ReportPrint
Route::rule('reportPrint','index/ReportController/jumpToReportPrint');
Route::rule('printReport','index/ReportController/printReport');

//Airport
Route::rule('airport','index/AirportController/jumpToAirport');
Route::rule('flightDynamicStatus','index/AirportController/findAllDailyFlightForAirport');
Route::rule('jumpToModifyFlightDynamic','index/AirportController/jumpToModifyFlightDynamic');
Route::rule('modifyFlightDynamic','index/AirportController/modifyFlightDynamic');
