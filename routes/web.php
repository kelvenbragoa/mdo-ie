<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/set-locale',[App\Http\Controllers\GlobalController::class, 'locale']);



Route::get('/', function () {
    return view('welcome');
});

Route::post('/excel/upload',[App\Http\Controllers\GlobalController::class, 'excel']);



Route::get('/auxiliar-create-meeting/{roleid}', [App\Http\Controllers\GlobalController::class, 'auxiliardatameeting']);

Route::get('/auxiliar-create-users', [App\Http\Controllers\GlobalController::class, 'auxiliardata']);
Route::get('/auxiliar-create-users/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatacity']);

Route::get('/auxiliar-create-equipments', [App\Http\Controllers\GlobalController::class, 'auxiliardataequipment']);
Route::get('/auxiliar-create-equipments/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardataequipmentaccount']);

Route::get('/auxiliar-create-mcscr', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscr']);

Route::get('/auxiliar-create-task-mcscr/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatataskmcscrrecommendation']);


Route::get('/auxiliar-create-mcscr-logistic/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatalogistic']);


Route::get('/auxiliar-create-mcscr/{id}/{destinationid}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrtypeequipmentdestination']);
Route::get('/auxiliar-create-mcscr/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrtypeequipment']);
// Route::get('/auxiliar-create-mcscr/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrtypeequipment']);

Route::get('/auxiliar-create-mcscr-components/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrcomponent']);
Route::get('/auxiliar-create-mcscr-subcomponents/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrsubcomponent']);
Route::get('/auxiliar-create-mcscr-type-equipment/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrdestinationtypeequipment']);


Route::get('/auxiliar-create-mcscr-plantask/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatataskmcscr']);
Route::get('/auxiliar-create-mcscr-subtask/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatataskplantaskmcscr']);



Route::get('/auxiliar-create-mcscr-reason', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrreasons']);
Route::get('/auxiliar-create-mcscr-cause', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrcauses']);
Route::get('/auxiliar-create-mcscr-solution', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrsolutions']);
Route::get('/auxiliar-create-mcscr-consequence', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrconsequences']);
Route::get('/auxiliar-create-mcscr-recommendation', [App\Http\Controllers\GlobalController::class, 'auxiliardatamcscrrecommendations']);

Route::get('/auxiliar-create-products', [App\Http\Controllers\GlobalController::class, 'auxiliardataproducts']);


Route::get('/auxiliar-create-inventory-product/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliardatainventoryproduct']);
Route::get('/auxiliar-create-inventory', [App\Http\Controllers\GlobalController::class, 'auxiliardatainventory']);
Route::get('auxiliar-create-technicians', [\App\Http\Controllers\GlobalController::class,'auxiliarcreatetechnician']);
Route::get('auxiliar-create-stockrequest', [\App\Http\Controllers\GlobalController::class,'auxiliarcreatestockrequest']);
Route::get('auxiliar-create-technicianrequest', [\App\Http\Controllers\GlobalController::class,'auxiliarcreatetechnicianrequest']);
Route::get('auxiliar-create-toolrequest', [\App\Http\Controllers\GlobalController::class,'auxiliarcreatetoolrequest']);
Route::get('auxiliar-create-schedule/{id}', [\App\Http\Controllers\GlobalController::class,'auxiliarcreateschedule']);


Route::get('/calendars', [App\Http\Controllers\Admin\TaskMcscrController::class, 'calendar']);
Route::get('/detailcalendar/{parms}', [App\Http\Controllers\Admin\TaskMcscrController::class, 'detailcalendar']);








Route::get('/auxiliar-create-requeststock/{id}', [App\Http\Controllers\GlobalController::class, 'auxiliarcreaterequest']);








// Route::group(['middleware'=>['auth','admin']], function(){
    Route::group(['middleware'=>['auth']], function(){

    Route::delete('/mcscr-resolution/{id}', [App\Http\Controllers\Admin\MCSCRController::class, 'deleteresolutions']);

    //Admins Route
    //rotas para CRUD Administradores
    Route::get('/admins/dashboard/getdashboarddata', [App\Http\Controllers\Admin\DashboardController::class, 'dashboarddata']);
    Route::get('/updatedashboard/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'updatedashboard']);
    
    Route::get('/admins/destination/dashboard/getdashboarddata', [App\Http\Controllers\Admin\DashboardController::class, 'dashboarddatadestination']);

    Route::get('/mcscrjobtask', [App\Http\Controllers\Admin\JobCardRecommendationTaskController::class, 'mcscrjobtask']);

    Route::resource('jobtasks', 'App\Http\Controllers\Admin\JobCardRecommendationTaskController');

    Route::resource('users', 'App\Http\Controllers\Admin\UsersController');
    Route::resource('areas', 'App\Http\Controllers\Admin\AreasController');
    Route::resource('departments', 'App\Http\Controllers\Admin\DepartmentsController');
    Route::resource('technicians', 'App\Http\Controllers\Admin\TechnicianController');
    Route::resource('tasks', 'App\Http\Controllers\Admin\TaskController');
    Route::resource('taskplans', 'App\Http\Controllers\Admin\TaskPlanController');
    Route::resource('taskplanequipments', 'App\Http\Controllers\Admin\TaskPlanEquipmentController');
    Route::resource('taskplantasks', 'App\Http\Controllers\Admin\TaskPlanTaskController');
    Route::resource('subtasks', 'App\Http\Controllers\Admin\SubTaskController');
    Route::resource('taskmaterials', 'App\Http\Controllers\Admin\TaskMaterialsController');
    Route::resource('taskdepartments', 'App\Http\Controllers\Admin\TaskDepartmentsController');
    Route::resource('malfunctions', 'App\Http\Controllers\Admin\MalfunctionsController');
    Route::resource('destinations', 'App\Http\Controllers\Admin\DestinationsController');
    Route::resource('centercost', 'App\Http\Controllers\Admin\CenterCostController');
    Route::resource('centercostaccount', 'App\Http\Controllers\Admin\CenterCostAccountController');
    Route::resource('equipments', 'App\Http\Controllers\Admin\EquipmentsController');
    Route::resource('type_equipments', 'App\Http\Controllers\Admin\TypeEquipmentsController');
    Route::resource('fleets', 'App\Http\Controllers\Admin\FleetController');
    Route::resource('suppliers', 'App\Http\Controllers\Admin\SuppliersController');
    Route::resource('equipmentcomponent', 'App\Http\Controllers\Admin\EquipmentComponentsController');
    Route::resource('equipmentsubcomponent','App\Http\Controllers\Admin\EquipmentSubComponentController');
    Route::resource('typeequipmentcomponent', 'App\Http\Controllers\Admin\TypeEquipmentComponentsController');
    Route::resource('typeequipmentsubcomponent','App\Http\Controllers\Admin\TypeEquipmentSubComponentController');
    Route::resource('reasons', 'App\Http\Controllers\Admin\ReasonsController');
    Route::resource('causes', 'App\Http\Controllers\Admin\CausesController');
    Route::resource('solutions', 'App\Http\Controllers\Admin\SolutionsController');
    Route::resource('consequences', 'App\Http\Controllers\Admin\ConsequencesController');
    Route::resource('recommendations', 'App\Http\Controllers\Admin\RecommendationsController');
    Route::resource('mcscr', 'App\Http\Controllers\Admin\MCSCRController');
    Route::resource('inspections', 'App\Http\Controllers\Admin\InspectionController');
    Route::resource('generalinspections', 'App\Http\Controllers\Admin\GeneralInspectionController');
    Route::resource('brands', 'App\Http\Controllers\Admin\ProductBrandController');
    Route::resource('categories', 'App\Http\Controllers\Admin\ProductCategoryController');
    Route::resource('products', 'App\Http\Controllers\Admin\ProductController');
    Route::resource('taskmcscr', 'App\Http\Controllers\Admin\TaskMcscrController');
    Route::resource('stockcenters', 'App\Http\Controllers\Admin\StockCentersController');
    Route::resource('inventories', 'App\Http\Controllers\Admin\InventoryController');
    Route::resource('exitnotes', 'App\Http\Controllers\Admin\ExitNoteController');
    Route::resource('shifts', 'App\Http\Controllers\Admin\ShiftController');
    Route::resource('entrynotes', 'App\Http\Controllers\Admin\EntryNoteController');
    Route::resource('stocksuppliers', 'App\Http\Controllers\Admin\StockSupplierController');
    Route::resource('stocktransfers', 'App\Http\Controllers\Admin\StockTransferController');
    Route::resource('stockrequests', 'App\Http\Controllers\Admin\RequestStockController');
    Route::resource('technicianrequests', 'App\Http\Controllers\Admin\RequestTechnicianController');
    Route::resource('toolrequests', 'App\Http\Controllers\Admin\RequestToolController');
    Route::resource('toolshops', 'App\Http\Controllers\Admin\ToolShopController');
    Route::resource('notifications', 'App\Http\Controllers\Admin\NotificationController');
    Route::resource('hourdistances', 'App\Http\Controllers\Admin\HoursDistanceEquipmentController');
    Route::resource('schedulework', 'App\Http\Controllers\Admin\ScheduleWorkController');

    Route::resource('quotation', 'App\Http\Controllers\Admin\QuotationController');

    Route::get('/calendarquotation', [App\Http\Controllers\Admin\QuotationController::class, 'calendar']);

    Route::resource('quotationitem', 'App\Http\Controllers\Admin\QuotationItemController');
    Route::resource('scheduleworkitem', 'App\Http\Controllers\Admin\ScheduleWorkItemController');
    Route::resource('fuel', 'App\Http\Controllers\Admin\FuelController');
    Route::resource('typedocuments', 'App\Http\Controllers\Admin\TypeDocumentController');
    Route::resource('documents', 'App\Http\Controllers\Admin\DocumentController');
    Route::resource('trips', 'App\Http\Controllers\Admin\TripController');
    Route::resource('tripexpenses', 'App\Http\Controllers\Admin\TripExpensesController');

    Route::resource('driver', 'App\Http\Controllers\Admin\DriverController');
    Route::resource('logisticdestination', 'App\Http\Controllers\Admin\LogisticDestinationController');
    Route::resource('logistictrip', 'App\Http\Controllers\Admin\LogisticTripController');
    Route::resource('destinationexpense', 'App\Http\Controllers\Admin\LogisticDestinationExpenseController');
    Route::resource('tripexpense', 'App\Http\Controllers\Admin\LogisticTripExpenseController');


    Route::resource('logisticcustomer', 'App\Http\Controllers\Admin\LogisticCustomerController');
    Route::resource('logisticquotation', 'App\Http\Controllers\Admin\LogisticQuotationController');


    Route::resource('meeting', 'App\Http\Controllers\Admin\MeetingController');
    Route::resource('meetingtype', 'App\Http\Controllers\Admin\MeetingTypeController');
    Route::resource('meetingattachment', 'App\Http\Controllers\Admin\MeetingAttachmentController');
    Route::resource('meetingparticipant', 'App\Http\Controllers\Admin\MeetingParticipantController');
    Route::resource('meetingtask', 'App\Http\Controllers\Admin\MeetingTaskController');
    Route::post('/copymeetingtask',[App\Http\Controllers\Admin\MeetingTaskController::class, 'copy']);

    Route::get('/mcscr/{id}/upload',[App\Http\Controllers\Admin\MCSCRController::class, 'viewupload']);
    Route::post('/mcscr/upload',[App\Http\Controllers\Admin\MCSCRController::class, 'upload']);
    Route::delete('/mcscr/upload/{id}',[App\Http\Controllers\Admin\MCSCRController::class, 'deleteupload']);

    Route::get('/equipments/{id}/upload',[App\Http\Controllers\Admin\EquipmentsController::class, 'viewupload']);
    Route::post('/equipments/upload',[App\Http\Controllers\Admin\EquipmentsController::class, 'upload']);
    Route::delete('/equipments/upload/{id}',[App\Http\Controllers\Admin\EquipmentsController::class, 'deleteupload']);


    Route::get('profile',[App\Http\Controllers\GlobalController::class, 'profile']);
    Route::post('/profile/upload',[App\Http\Controllers\GlobalController::class, 'uploadsignature']);

    Route::post('/excel/upload',[App\Http\Controllers\Admin\ProductController::class, 'excel']);
    

    Route::get('/equipments/reconciliation/{id}',[App\Http\Controllers\Admin\EquipmentsController::class, 'reconciliation']);

    Route::get('/destinationsfleet/{fleet_id}/destination/{destination_id}', [App\Http\Controllers\Admin\FleetDestinationsController::class, 'show']);

    Route::get('/fleets/{id}/mcscrcount',[App\Http\Controllers\Admin\FleetController::class,'mcscrcount']);
    Route::get('/fleets/{id}/taskcount',[App\Http\Controllers\Admin\FleetController::class,'taskcount']);
    Route::get('/fleets/{id}/fuelcount',[App\Http\Controllers\Admin\FleetController::class,'fuelcount']);
    Route::get('/fleets/{id}/hourdistancecount',[App\Http\Controllers\Admin\FleetController::class,'hourdistancecount']);
    Route::get('/equipments/{id}/mcscrcount',[App\Http\Controllers\Admin\EquipmentsController::class,'mcscrcount']);
    Route::get('/equipments/{id}/taskcount',[App\Http\Controllers\Admin\EquipmentsController::class,'taskcount']);
    Route::get('/equipments/{id}/fuelcount',[App\Http\Controllers\Admin\EquipmentsController::class,'fuelcount']);
    Route::get('/equipments/{id}/hourdistancecount',[App\Http\Controllers\Admin\EquipmentsController::class,'hourdistancecount']);
    Route::get('/taskplantasks/{id}/copy',[App\Http\Controllers\Admin\TaskPlanTaskController::class,'copytask']);

    Route::get('/taskplans/{id}/copy',[App\Http\Controllers\Admin\TaskPlanController::class,'copytask']);


    Route::get('/download-mcscr/{id}', [App\Http\Controllers\Admin\MCSCRController::class, 'download']);
    Route::get('/download-taskmcscr/{id}', [App\Http\Controllers\Admin\TaskMcscrController::class, 'download']);

    Route::resource('groupshift', 'App\Http\Controllers\Admin\GroupShiftController');
    Route::resource('groupshiftoperator', 'App\Http\Controllers\Admin\GroupShiftOperatorController');
    Route::resource('shiftequipmentrequest', 'App\Http\Controllers\Admin\ShiftEquipmentRequestController');
    Route::resource('shiftequipmentrequestitem', 'App\Http\Controllers\Admin\ShiftEquipmentRequestItemController');

    Route::get('/destination-calendars', [App\Http\Controllers\Destination\TaskMcscrController::class, 'calendar']);
    Route::get('/destination-detailcalendar/{parms}', [App\Http\Controllers\Admin\TaskMcscrController::class, 'detailcalendar']);

    Route::get('/meeting-task-calendars', [App\Http\Controllers\Admin\MeetingTaskController::class, 'calendar']);
    Route::get('/meeting-task-detailcalendar/{parms}', [App\Http\Controllers\Admin\MeetingTaskController::class, 'detailcalendar']);

    


    Route::resource('tirelayouts', 'App\Http\Controllers\Admin\TireLayoutController');




    //ROUTES FOR DESTINATION
    Route::resource('destination-equipments', 'App\Http\Controllers\Destination\EquipmentController');
    Route::resource('destination-type_equipments', 'App\Http\Controllers\Destination\TypeEquipmentsController');
    Route::resource('destination-hourdistances', 'App\Http\Controllers\Destination\HoursDistanceController');
    Route::resource('destination-fuel', 'App\Http\Controllers\Destination\FuelController');
    Route::resource('destination-taskmcscr', 'App\Http\Controllers\Destination\TaskMcscrController');
    Route::resource('destination-mcscr', 'App\Http\Controllers\Destination\McscrController');
    Route::resource('destination-quotation', 'App\Http\Controllers\Destination\QuotationController');

});




//Ultima rota

Route::get('{view}', ApplicationController::class)->where('view','(.*)')->middleware('auth');

