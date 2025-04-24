<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');





















Route::any('/banner', [ApiController::class, 'banner']);
Route::any('/getlogin', [ApiController::class, 'getlogin']);
Route::any('/login', [ApiController::class, 'authenticate']);
Route::any('/allcustomer',[ApiController::class, 'getallCustomer']);
Route::any('/searchallcustomer',[ApiController::class, 'searchgetallCustomer']);
Route::any('/getdatacustomer',[ApiController::class, 'getdatacustomer']);
Route::any('/allloans', [ApiController::class, 'allloans']);
Route::any('/allpurpose', [ApiController::class, 'allpurpose']);

Route::any('/disbursedloan',[ApiController::class, 'disbursedloan']);
Route::any('/searchdisbursedloan',[ApiController::class, 'searchdisbursedloan']);

Route::any('/getinstallments',[ApiController::class, 'getinstallments']);
Route::any('/loanadvancement',[ApiController::class, 'loanadvancement']);
Route::any('/geteditloan',[ApiController::class, 'geteditloan']);
Route::any('/updateloanadvancement',[ApiController::class, 'updateloanadvancement']);
Route::any('/getrecovery',[ApiController::class, 'getrecovery']);
Route::any('/takerecovery',[ApiController::class, 'takerecovery']);
Route::any('/getpaidrecovery',[ApiController::class, 'getpaidrecovery']);
Route::any('/deleteloan',[ApiController::class, 'deleteloan']);
Route::any('/deleterecovery',[ApiController::class, 'deleterecovery']);
Route::any('/registerForm',[ApiController::class, 'account_opening']);
Route::any('/getcustomer',[ApiController::class, 'getcustomer']);
Route::any('/state',[ApiController::class, 'state']);
Route::any('/district',[ApiController::class, 'district']);
Route::any('/tehsil',[ApiController::class, 'tehsil']);
Route::any('/postoffice',[ApiController::class, 'postoffice']);
Route::any('/village',[ApiController::class, 'village']);
Route::any('/editrecovery',[ApiController::class, 'editrecovery']);
Route::any('/updatetakerecovery',[ApiController::class, 'updatetakerecovery']);
Route::any('/processForgotPassword',[ApiController::class, 'processForgotPassword']);
Route::any('/searchgetcustomer',[ApiController::class, 'searchgetcustomer']);
Route::any('/payrecovery',[ApiController::class, 'payrecovery']);
Route::any('/editaddrecovery',[ApiController::class, 'editaddrecovery']);
Route::any('/getquickrecovery',[ApiController::class, 'getquickrecovery']);
Route::any('/getdatarecovery',[ApiController::class, 'getdatarecovery']);
Route::any('/deleterecovery',[ApiController::class, 'recconfirmDeletere']);

Route::any('/customelogin',[ApiController::class, 'login']);
Route::any('/changeuserpassword',[ApiController::class, 'changeuserpassword']);
Route::any('/changepassword',[ApiController::class, 'changepassword']);
Route::any('/todaycollection',[ApiController::class, 'todaycollection']);
Route::any('/verifyPayment',[ApiController::class, 'verifyPayment']);


Route::any('/editcustomer',[ApiController::class, 'editcustomer']);
Route::any('/updatecustomer',[ApiController::class, 'updatecustomer']);

//__________Loan Advancement Edit/Update/Delete
Route::any('/editloandisbursement',[ApiController::class, 'editloandisbursement']);
Route::any('/updateloanadvancement',[ApiController::class, 'updateloanadvancement']);
Route::any('/loandvancementdelete',[ApiController::class, 'loandvancementdelete']);
Route::any('/editloanrecovery',[ApiController::class, 'editloanrecovery']);
Route::any('/getallloanrecoveries',[ApiController::class, 'getallloanrecoveries']);
Route::any('/updateloanrecovery',[ApiController::class, 'updateloanrecovery']);
Route::any('/deleteloanrecovery',[ApiController::class, 'deleteloanrecovery']);


