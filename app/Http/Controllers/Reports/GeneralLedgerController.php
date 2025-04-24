<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\brands;
use App\Models\categorys;
use App\Models\groups;
use App\Models\item_purchase;
use App\Models\item_sale;
use App\Models\items;
use App\Models\ledgers;
use App\Models\subcategorys;
use App\Models\general_ledgers;
use App\Models\tbl_ledger;
use App\Models\member_loans;
use Illuminate\Http\Request;
use App\Models\loan_recoveries;
use App\Models\MemberLoan;
use App\Models\GroupMaster;
use App\Models\GeneralLedger;
use App\Models\LoanRecovery;
use App\Models\SessionMaster;
use DateTime;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{

    public function itemledgerreport() {
        $groups = groups::orderBy('group_code', 'ASC')->get();
        $ledger = ledgers::orderBy('group_code', 'ASC')->get();
        $data['groups'] = $groups;
        $data['ledgers'] = $ledger;
        return view('main.Reports.itemledgerreport', $data);
    }

    public function itemledgerreportdetail(Request $request) {
        $startDate = date('Y-m-d', strtotime($request->datefrom));
        $endDate = date('Y-m-d', strtotime($request->dateto));

        $openingstock = DB::table('items')->where('itemcode', '=', $request->itemcode)->value('openingstock');
        $peropeningstock = DB::table('items')->where('itemcode', '=', $request->itemcode)->value('singleopeningstock');
        $data['unitquantity'] = DB::table('items')->where('itemcode', '=', $request->itemcode)->value('unitquantity');

        // Fetch purchase data and add a type identifier
        $purchases = DB::table('item_purchase')->where('baltype', '=', 'box')->where('itemcode', '=', $request->itemcode)->whereDate('bill_date', '<', $startDate)->sum('quantity');
        $perpurchases = DB::table('item_purchase')->where('baltype', '=', 'single')->where('itemcode', '=', $request->itemcode)->whereDate('bill_date', '<', $startDate)->sum('quantity');

        // Fetch sale data and add a type identifier
        $sales = DB::table('item_sale')->where('baltype', '=', 'box')->where('itemcode', '=', $request->itemcode)->whereDate('bill_date', '<', $startDate)->sum('quantity');
        $persales = DB::table('item_sale')->where('baltype', '=', 'single')->where('itemcode', '=', $request->itemcode)->whereDate('bill_date', '<', $startDate)->sum('quantity');

        $data['openingstock'] = $openingstock + $purchases - $sales;
        $data['peropeningstock'] = $peropeningstock + $perpurchases - $persales;

        // Fetch purchase data and add a type identifier
        $data['purchase'] = DB::table('item_purchase')
            ->where('itemcode', '=', $request->itemcode)
            ->whereDate('bill_date', '>=', $startDate)
            ->whereDate('bill_date', '<=', $endDate)
            ->select('itemcode', 'bill_date', 'baltype', 'quantity', 'pquantity', 'invoiceno', 'total', 'created_at', DB::raw('"purchase" as type'))
            ->get();

        // Fetch sale data and add a type identifier
        $data['sale'] = DB::table('item_sale')
            ->where('itemcode', '=', $request->itemcode)
            ->whereDate('bill_date', '>=', $startDate)
            ->whereDate('bill_date', '<=', $endDate)
            ->select('itemcode', 'bill_date', 'baltype', 'quantity', 'pquantity', 'invoiceno', 'total', 'created_at', DB::raw('"sale" as type'))
            ->get();
        // Combine both collections
        $ledger = collect([])->merge($data['purchase'])->merge($data['sale']);

        // Sort by date
        $sortedLedger = $ledger->sortBy('created_at')->values()->all();
        $data['sortedLedger'] = $sortedLedger;

        return response()->json($data);
    }

    public function itemledgerreportdetaill(Request $request) {
        $startDate = date('Y-m-d', strtotime($request->datefrom));
        $endDate = date('Y-m-d', strtotime($request->dateto));

        $itemCodes = DB::table('items')->pluck('itemcode');

        $data = [];

        foreach ($itemCodes as $itemCode) {
            $openingstock = DB::table('items')->where('itemcode', '=', $itemCode)->value('openingstock');
            $peropeningstock = DB::table('items')->where('itemcode', '=', $itemCode)->value('singleopeningstock');
            $unitquantity = DB::table('items')->where('itemcode', '=', $itemCode)->value('unitquantity');

            $purchases = DB::table('item_purchase')->where('baltype', '=', 'box')->where('itemcode', '=', $itemCode)->whereDate('bill_date', '<', $startDate)->sum('quantity');
            $perpurchases = DB::table('item_purchase')->where('baltype', '=', 'single')->where('itemcode', '=', $itemCode)->whereDate('bill_date', '<', $startDate)->sum('quantity');

            $sales = DB::table('item_sale')->where('baltype', '=', 'box')->where('itemcode', '=', $itemCode)->whereDate('bill_date', '<', $startDate)->sum('quantity');
            $persales = DB::table('item_sale')->where('baltype', '=', 'single')->where('itemcode', '=', $itemCode)->whereDate('bill_date', '<', $startDate)->sum('quantity');

            $openingstock = $openingstock + $purchases - $sales;
            $peropeningstock = $peropeningstock + $perpurchases - $persales;

            $purchaseData = DB::table('item_purchase')
                ->where('itemcode', '=', $itemCode)
                ->whereDate('bill_date', '>=', $startDate)
                ->whereDate('bill_date', '<=', $endDate)
                ->select('itemcode', 'bill_date', 'baltype', 'quantity', 'pquantity', 'invoiceno', 'total', 'created_at', DB::raw('"purchase" as type'))
                ->get();

            $saleData = DB::table('item_sale')
                ->where('itemcode', '=', $itemCode)
                ->whereDate('bill_date', '>=', $startDate)
                ->whereDate('bill_date', '<=', $endDate)
                ->select('itemcode', 'bill_date', 'baltype', 'quantity', 'pquantity', 'invoiceno', 'total', 'created_at', DB::raw('"sale" as type'))
                ->get();

            $ledger = collect([])->merge($purchaseData)->merge($saleData);
            $sortedLedger = $ledger->sortBy('created_at')->values()->all();

            $data[$itemCode] = [
                'unitquantity' => $unitquantity,
                'openingstock' => $openingstock,
                'peropeningstock' => $peropeningstock,
                'sortedLedger' => $sortedLedger
            ];
        }

        return response()->json($data);
    }


    public function generalLedgerReport()
    {
        $groups = groups::orderBy('groupCode', 'ASC')->get();
        $ledger = ledgers::orderBy('ledgerCode', 'ASC')->get();


        $pagetitle = "General Ledger";
        $pageto = url('generalLedgerReport');
        $formurl = url('generalLedgerReport');
        $data = compact('groups', 'ledger', 'pagetitle', 'pageto', 'formurl');
        return view('Reports.generalledger', $data);
    }

    public function groupledgers(Request $request)
    {
        $data['substates'] = ledgers::where("groupCode", $request->id)->get(["name", "ledgerCode"]);
        // dd($data);
        return response()->json($data);
    }


    public function ledgerwiseDetails(Request $request){

        $startDate = date('Y-m-d', strtotime($request->datefrom));
        $endDate = date('Y-m-d', strtotime($request->dateto));
        $groupCode = $request->group;
        $ledgerCode = $request->ledger;
        // dd($groupCode,$ledgerCode);


        // $balances = tbl_ledger::select('tbl_ledger.id', 'tbl_ledger.entry_date','tbl_ledger.account_name','tbl_ledger.narration','tbl_ledger.narration')
        //     ->where('group_code', $groupCode)
        //     ->where('gr_rr', $ledgerCode)
        //     ->where('entry_date', '>=', $startDate)
        //     ->where('entry_date', '<=', $endDate)
        //     ->selectRaw('SUM(CASE WHEN dr_cr = "Dr" THEN dr_amount ELSE 0 END) as total_debit')
        //     ->selectRaw('SUM(CASE WHEN dr_cr = "Cr" THEN cr_amount ELSE 0 END) as total_credit')
        //     ->groupBy('tbl_ledger.id', 'tbl_ledger.entry_date','tbl_ledger.account_name','tbl_ledger.narration','tbl_ledger.narration') // Grouping by id and entry_date
        //     ->get();


        $balances = general_ledgers::select('general_ledgers.*')
            ->where('groupCode', $groupCode)
            ->where('ledgerCode', $ledgerCode)
            ->where('transactionDate', '>=', $startDate)
            ->where('transactionDate', '<=', $endDate)
            ->orderBy('transactionDate', 'ASC')
            ->get();


        $openingBalance = general_ledgers::select('general_ledgers.groupCode', 'general_ledgers.ledgerCode', 'general_ledgers.transactionDate', 'general_ledgers.formName', 'general_ledgers.narration', 'general_ledgers.transactionType')
            ->where('groupCode', $groupCode)
            ->where('ledgerCode', $ledgerCode)
            ->where('transactionDate', '<', $startDate)
            ->selectRaw('SUM(CASE WHEN transactionType = "Dr" THEN transactionAmount ELSE 0 END) as total_debit')
            ->selectRaw('SUM(CASE WHEN transactionType = "Cr" THEN transactionAmount ELSE 0 END) as total_credit')
            ->groupBy('general_ledgers.groupCode', 'general_ledgers.ledgerCode', 'general_ledgers.transactionDate', 'general_ledgers.formName', 'general_ledgers.narration', 'general_ledgers.transactionType')
            ->orderBy('transactionDate', 'ASC')
            ->first();

        if ($openingBalance) {
            if ($openingBalance->dr_cr === 'dr') {
                $openingBalance =  $openingBalance->total_debit - $openingBalance->total_credit;
            } else {
                $openingBalance =  $openingBalance->total_credit - $openingBalance->total_debit;
            }
        } else {
            $openingBalance = 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => $balances,
            'openingBalance' => $openingBalance

        ]);
    }



    /****Trial Balance*****/
    public function agentsreport(Request $request)
    {
        if (!empty($request->all())) {

            $validated = $request->validate([
                'datefrom' => 'required|date',
                'dateto' => 'required|date',
            ]);

            $dateFrom = \DateTime::createFromFormat('d-m-Y', $validated['datefrom'])->format('Y-m-d');
            $dateTo = \DateTime::createFromFormat('d-m-Y', $validated['dateto'])->format('Y-m-d');
            $agentId = $request->agentid;

            if($agentId === 'All'){
                if ($request->viewtype == 'compact') {
                    $results = DB::table('loan_recoveries')
                        ->join('agent_masters as agents', 'loan_recoveries.agentId', '=', 'agents.id')
                        ->join('member_loans as memberloans', 'loan_recoveries.loanId', '=', 'memberloans.id')
                        ->join('loan_masters as loanmasters', 'memberloans.loanType', '=', 'loanmasters.id')
                        ->join('member_accounts as memberaccounts', 'memberloans.accountNo', '=', 'memberaccounts.customer_Id')
                        ->select(
                            // 'loan_recoveries.agentId',
                            'agents.name as agentName',
                            DB::raw('SUM(loan_recoveries.principal) as totalPrincipal'),
                            DB::raw('COUNT(DISTINCT loan_recoveries.id) as numberOfTransactions'),
                            DB::raw('GROUP_CONCAT(DISTINCT memberaccounts.customer_Id) as customerIds')
                        )
                        ->whereBetween('loan_recoveries.receiptDate', [$dateFrom, $dateTo])
                        ->groupBy('loan_recoveries.agentId', 'agents.name')
                        ->get();



                    $loan_advancements = DB::table('member_loans')
                        ->join('agent_masters as agents', 'member_loans.agentId', '=', 'agents.id')
                        ->join('loan_masters as loanmasters', 'member_loans.loanType', '=', 'loanmasters.id')
                        ->join('member_accounts as memberaccounts', 'member_loans.accountNo', '=', 'memberaccounts.customer_Id')
                        ->select('memberaccounts.customer_Id','memberaccounts.name as cutomername',
                            'member_loans.agentId','loanmasters.loanname','agents.name as agentName',
                            DB::raw('SUM(member_loans.loanAmount) as totalLoanAmount'),
                            DB::raw('COUNT(DISTINCT member_loans.id) as numberOfTransactions'),
                            DB::raw('GROUP_CONCAT(DISTINCT memberaccounts.customer_Id) as customerIds')
                        )
                        ->whereBetween('member_loans.loanDate', [$dateFrom, $dateTo])
                        ->groupBy('memberaccounts.customer_Id','memberaccounts.name','member_loans.agentId','loanmasters.loanname','agents.name')
                        ->get();

                } else {
                    $results = DB::table('loan_recoveries')
                        ->join('agent_masters as agents', 'loan_recoveries.agentId', '=', 'agents.id')
                        ->join('member_loans as memberloans', 'loan_recoveries.loanId', '=', 'memberloans.id')
                        ->join('loan_masters as loanmasters', 'memberloans.loanType', '=', 'loanmasters.id')
                        ->join('member_accounts as memberaccounts', 'memberloans.accountNo', '=', 'memberaccounts.customer_Id')
                        ->select(
                                'memberaccounts.customer_Id','memberaccounts.name as cutomername','loan_recoveries.receiptDate',
                                'loan_recoveries.agentId','loan_recoveries.principal','agents.name as agentName','loanmasters.loanname',
                            )
                        ->whereBetween('loan_recoveries.receiptDate', [$dateFrom, $dateTo])
                        ->get();


                    $loan_advancements = DB::table('member_loans')
                        ->join('agent_masters as agents', 'member_loans.agentId', '=', 'agents.id')
                        ->join('loan_masters as loanmasters', 'member_loans.loanType', '=', 'loanmasters.id')
                        ->join('member_accounts as memberaccounts', 'member_loans.accountNo', '=', 'memberaccounts.customer_Id')
                        ->select(
                                'memberaccounts.customer_Id','memberaccounts.name as cutomername','agents.name as agentName',
                                'loanmasters.loanname','member_loans.loanAmount','member_loans.loanDate'
                            )
                        ->whereBetween('member_loans.loanDate', [$dateFrom, $dateTo])
                        ->get();
                }
            }else{


            if ($request->viewtype == 'compact') {
                $results = DB::table('loan_recoveries')
                    ->join('agent_masters as agents', 'loan_recoveries.agentId', '=', 'agents.id')
                    ->join('member_loans as memberloans', 'loan_recoveries.loanId', '=', 'memberloans.id')
                    ->join('loan_masters as loanmasters', 'memberloans.loanType', '=', 'loanmasters.id')
                    ->join('member_accounts as memberaccounts', 'memberloans.accountNo', '=', 'memberaccounts.customer_Id')
                    ->select(
                        // 'loan_recoveries.agentId',
                        'agents.name as agentName',
                        DB::raw('SUM(loan_recoveries.principal) as totalPrincipal'),
                        DB::raw('COUNT(DISTINCT loan_recoveries.id) as numberOfTransactions'),
                        DB::raw('GROUP_CONCAT(DISTINCT memberaccounts.customer_Id) as customerIds')
                    )
                    ->where('loan_recoveries.agentId','=',$agentId)
                    ->whereBetween('loan_recoveries.receiptDate', [$dateFrom, $dateTo])
                    ->groupBy('loan_recoveries.agentId', 'agents.name')
                    ->get();



                $loan_advancements = DB::table('member_loans')
                    ->join('agent_masters as agents', 'member_loans.agentId', '=', 'agents.id')
                    ->join('loan_masters as loanmasters', 'member_loans.loanType', '=', 'loanmasters.id')
                    ->join('member_accounts as memberaccounts', 'member_loans.accountNo', '=', 'memberaccounts.customer_Id')
                    ->select('memberaccounts.customer_Id','memberaccounts.name as cutomername',
                        'member_loans.agentId','loanmasters.loanname','agents.name as agentName',
                        DB::raw('SUM(member_loans.loanAmount) as totalLoanAmount'),
                        DB::raw('COUNT(DISTINCT member_loans.id) as numberOfTransactions'),
                        DB::raw('GROUP_CONCAT(DISTINCT memberaccounts.customer_Id) as customerIds')
                    )
                    ->whereBetween('member_loans.loanDate', [$dateFrom, $dateTo])
                    ->where('member_loans.agentId','=',$agentId)
                    ->groupBy('memberaccounts.customer_Id','memberaccounts.name','member_loans.agentId','loanmasters.loanname','agents.name')
                    ->get();

            } else {
                $results = DB::table('loan_recoveries')
                    ->join('agent_masters as agents', 'loan_recoveries.agentId', '=', 'agents.id')
                    ->join('member_loans as memberloans', 'loan_recoveries.loanId', '=', 'memberloans.id')
                    ->join('loan_masters as loanmasters', 'memberloans.loanType', '=', 'loanmasters.id')
                    ->join('member_accounts as memberaccounts', 'memberloans.accountNo', '=', 'memberaccounts.customer_Id')
                    ->select(
                            'memberaccounts.customer_Id','memberaccounts.name as cutomername','loan_recoveries.receiptDate',
                            'loan_recoveries.agentId','loan_recoveries.principal','agents.name as agentName','loanmasters.loanname',
                        )
                    ->whereBetween('loan_recoveries.receiptDate', [$dateFrom, $dateTo])
                    ->where('loan_recoveries.agentId','=',$agentId)
                    ->get();


                $loan_advancements = DB::table('member_loans')
                    ->join('agent_masters as agents', 'member_loans.agentId', '=', 'agents.id')
                    ->join('loan_masters as loanmasters', 'member_loans.loanType', '=', 'loanmasters.id')
                    ->join('member_accounts as memberaccounts', 'member_loans.accountNo', '=', 'memberaccounts.customer_Id')
                    ->select(
                            'memberaccounts.customer_Id','memberaccounts.name as cutomername','agents.name as agentName',
                            'loanmasters.loanname','member_loans.loanAmount','member_loans.loanDate'
                        )
                    ->whereBetween('member_loans.loanDate', [$dateFrom, $dateTo])
                    ->where('member_loans.agentId','=',$agentId)
                    ->get();
            }
        }
            return response()->json(['status' => 'success','loan_advancements' => $loan_advancements,'results' => $results]);

        } else {
            $pagetitle = "Agents Report";
            $pageto = url('agentsreport');
            $formurl = url('agentsreport');
            $agents = DB::table('agent_masters')->orderBy('agent_code','ASC')->get();
            $data = compact('pagetitle', 'pageto', 'formurl','agents');
            return view('Reports.agentsreport')->with($data);
        }
    }





    /****Trial Balance*****/
    public function receiptDisbursment()
    {
        $pagetitle = "Receipt & Disbursment";
        $pageto = url('receiptDisbursment');
        $formurl = url('receiptDisbursment');
        $data = compact('pagetitle', 'pageto', 'formurl');
        return view('Reports.receiptDisbursment')->with($data);
    }

    public function receiptDisbursmentgetData(Request $request)
    {
        $startDate = date('Y-m-d', strtotime($request->datefrom));
        $endDate = date('Y-m-d', strtotime($request->dateto));
        $groups_ledger = $request->groupledger;


        $entryCash = DB::table('ledger_masters')->where('groupCode', 'C002')->value('openingAmount');

        // Calculate opening cash
        $drOpening = DB::table('general_ledgers')->where('transactionType', 'Dr')->where('groupCode', 'C002')
            ->where('transactionDate', '<', $startDate)->sum('transactionAmount');
        $crOpening = DB::table('general_ledgers')->where('transactionType', 'Cr')->where('groupCode', 'C002')
            ->where('transactionDate', '<', $startDate)->sum('transactionAmount');

        $openingCash = $entryCash + $drOpening - $crOpening;

        // CAS001
        // Calculate closing cash
        $drClosing = DB::table('general_ledgers')->where('transactionType', 'Dr')->where('groupCode', 'C002')
            ->where('transactionDate', '<=', $endDate)->sum('transactionAmount');
        $crClosing = DB::table('general_ledgers')->where('transactionType', 'Cr')->where('groupCode', 'C002')
            ->where('transactionDate', '<=', $endDate)->sum('transactionAmount');
        $closingCash = $entryCash + $drClosing - $crClosing;





        if ($groups_ledger == 'group') {
            $groups = DB::table('group_masters')
                ->leftJoin('general_ledgers', 'general_ledgers.groupCode', '=', 'group_masters.groupCode')
                ->where('general_ledgers.transactionDate', '>=', $startDate)
                ->where('general_ledgers.transactionDate', '<=', $endDate)
                ->where('general_ledgers.groupCode', '!=', 'C002')
                ->select('group_masters.name', 'group_masters.groupCode')
                ->selectRaw('SUM(CASE WHEN general_ledgers.transactionType = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END) AS total_debit')
                ->selectRaw('SUM(CASE WHEN general_ledgers.transactionType = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END) AS total_credit')
                ->groupBy('group_masters.name', 'group_masters.groupCode')
                ->get();

            return response()->json([
                'status' => 'success',
                'groups' => $groups,
                'openingCash' => $openingCash,
                'closingCash' => $closingCash

            ]);
        } else {
            $ledgers = DB::table('ledger_masters')
                ->leftJoin('general_ledgers', 'general_ledgers.ledgerCode', '=', 'ledger_masters.ledgerCode')
                ->where('general_ledgers.transactionDate', '>=', $startDate)
                ->where('general_ledgers.transactionDate', '<=', $endDate)
                ->where('ledger_masters.ledgerCode', '!=', 'CAS001')
                ->select('ledger_masters.name', 'ledger_masters.ledgerCode')
                ->selectRaw('SUM(CASE WHEN general_ledgers.transactionType = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END) AS total_debit')
                ->selectRaw('SUM(CASE WHEN general_ledgers.transactionType = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END) AS total_credit')
                ->groupBy('ledger_masters.name', 'ledger_masters.ledgerCode')
                ->get();

            return response()->json([
                'status' => 'sucessss',
                'ledgers' => $ledgers,
                'openingCash' => $openingCash,
                'closingCash' => $closingCash

            ]);
        }
    }



    /******Re-Order Level******/ /******Re-Order Level******/ /******Re-Order Level******/
    public function reOrder()
    {
        $brands = brands::orderBy('name', 'ASC')->where('status', '=', 'active')->get();
        $categories = categorys::orderBy('name', 'ASC')->where('status', '=', 'active')->get();
        $subcategories = subcategorys::orderBy('name', 'ASC')->where('status', '=', 'active')->get();
        $data['brands'] = $brands;
        $data['categories'] = $categories;
        $data['subcategories'] = $subcategories;
        return view('main.Reports.reorder', $data);
    }






    public function reorderData(Request $request)
    {
        $endDate = date('Y-m-d', strtotime($request->datefrom));
        $reorder = $request->reorder;




        $items = items::where('status', 'active')
            ->where('reorderlable', '<=', $reorder)
            ->select('itemcode', 'reorderlable', 'name', 'singleopeningstock')
            ->get();

        $remainingQuantities = [];
        foreach ($items as $item) {
            $purchaseQuantity = DB::table('item_purchase')
                ->where('itemcode', $item->itemcode)
                ->sum('pquantity');

            $saleQuantity = DB::table('item_sale')
                ->where('itemcode', $item->itemcode)
                ->sum('pquantity');

            // Calculate remaining quantity
            if ($saleQuantity > $purchaseQuantity) {
                $remainingQuantity = $item->singleopeningstock - ($saleQuantity - $purchaseQuantity);
            } else {
                $remainingQuantity = $item->singleopeningstock - ($purchaseQuantity - $saleQuantity);
            }

            // Only include items where reorder is <= remainingQuantity
            if ($reorder >= $remainingQuantity) {
                $remainingQuantities[$item->itemcode] = $remainingQuantity;
            }
        }
        $itemsToShow = $items->whereIn('itemcode', array_keys($remainingQuantities))->all();



        // $remainingQuantities now contains the remaining quantity for each itemcode




        return response()->json([
            'status' => 'success',
            'items' => $itemsToShow,
            'remainingQuantities' => $remainingQuantities, // Include remaining quantities in JSON response
        ]);
    }
    /******Re-Order Level******/ /******Re-Order Level******/ /******Re-Order Level******/
    public function reOrderfetchdata(Request $request)
    {
        $brandId = $request->brandsId;
        $categorys = categorys::where('brand', $brandId)
            ->where('status', '=', 'active')
            ->get();

        if ($categorys->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'category' => $categorys
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No categories found'
        ]);
    }

    public function reOrderfetchdataForCategory(Request $request)
    {
        $brandId = $request->brandId;
        $categoryId = $request->categoryId;

        $subcategories = subcategorys::where('category', $categoryId)
            ->where('brand', $brandId)
            ->where('status', '=', 'active')
            ->get();

        if ($subcategories->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'subcategory' => $subcategories
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No subcategories found'
        ]);
    }





    /*Trading A/c*/
    public function tradingac()
    {
        return view('main.Reports.tradingacc');
    }

    public function tradingacgetData(Request $post)
    {
        $endDate = date('Y-m-d', strtotime($post->date));

        $opening_stock = DB::table('items')
            ->selectRaw('SUM(CASE WHEN items.op_stock_pc_amount IS NOT NULL THEN items.op_stock_pc_amount ELSE 0 END) as total_pcs_amount')
            ->selectRaw('SUM(CASE WHEN items.openingstock_amount IS NOT NULL THEN items.openingstock_amount ELSE 0 END) as total_boxes_amount')
            ->get();


        $purchases = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->select(
                'groups.name',
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit'),
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            )
            ->where('tbl_ledger.group_code', '=', 'GPR002')
            ->where('entry_date', '<=', $endDate)
            ->groupBy('groups.name')
            ->first();


        $direct_expences = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->select(
                'groups.name',
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit'),
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            )
            ->where('tbl_ledger.group_code', '=', 'GRD001')
            ->where('entry_date', '<=', $endDate)
            ->groupBy('groups.name')
            ->first();

        $sales = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->select(
                'groups.name',
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit'),
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            )
            ->where('tbl_ledger.group_code', '=', 'GPS002')
            ->where('entry_date', '<=', $endDate)
            ->groupBy('groups.name')
            ->first();


        $direct_incomes = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->select(
                'groups.name',
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit'),
                DB::raw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            )
            ->where('tbl_ledger.group_code', '=', 'GRD003')
            ->where('entry_date', '<=', $endDate)
            ->groupBy('groups.name')
            ->first();




        $indirect_expences = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->leftJoin('ledgers', 'tbl_ledger.gr_rr', '=', 'ledgers.ledger_code')
            ->select('ledgers.name')
            ->selectRaw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit')
            ->selectRaw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            ->where('tbl_ledger.group_code', '=', 'GRI001')
            ->where('entry_date', '<=', $endDate)
            ->groupBy('ledgers.name')
            ->get();



        $indirect_incomes = DB::table('tbl_ledger')
            ->leftJoin('groups', 'tbl_ledger.group_code', '=', 'groups.group_code')
            ->leftJoin('ledgers', 'tbl_ledger.gr_rr', '=', 'ledgers.ledger_code')
            ->select('ledgers.name')
            ->selectRaw('SUM(CASE WHEN tbl_ledger.dr_cr = "Dr" THEN tbl_ledger.dr_amount ELSE 0 END) AS total_debit')
            ->selectRaw('SUM(CASE WHEN tbl_ledger.dr_cr = "Cr" THEN tbl_ledger.cr_amount ELSE 0 END) AS total_credit')
            ->where('tbl_ledger.group_code', '=', 'GRI002')
            ->where('tbl_ledger.entry_date', '<=', $endDate)
            ->groupBy('ledgers.name')
            ->get();
        // dd($indirect_incomes);

        // $as_on_dates = date('d-m-Y',strtotime($endDate));


        return response()->json([
            'status' => 'success',
            'sales' => $sales,
            'purchases' => $purchases,
            'indirect_incomes' => $indirect_incomes,
            'indirect_expences' => $indirect_expences,
            'direct_incomes' => $direct_incomes,
            'direct_expences' => $direct_expences,
            'previousStock' => $opening_stock
        ]);
    }




    public function reOrdergetData(Request $request)
    {
        dd($request->all());
    }


    public function partywiseledger()
    {
        return view('main.Reports.personalledger');
    }



    public function profitlossIndex(){
        $pagetitle = "Profit & Loss";
        $pageto = url('profitandloss');
        $formurl = url('profitandloss');
        $data = compact('pagetitle', 'pageto', 'formurl');
        return view('Reports.profitandloss')->with($data);
    }

    public function profitandloss(Request $request){

        if (!empty($request->all())) {
            $start_date = date('Y-m-d', strtotime($request->startdate));
            $end_date = date('Y-m-d', strtotime($request->enddate));
            // dd($start_date,$end_date);
            $previostart_date = date('Y-m-d', strtotime('-1 year', strtotime($request->startdate)));
            $previoend_date = date('Y-m-d', strtotime('-1 year', strtotime($request->enddate)));

            $incomes = general_ledgers::select(
                'ledger_masters.name as ledger_name',
                'ledger_masters.ledgerCode',
                DB::raw('SUM(CASE WHEN general_ledgers.transactionType = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_income')
            )
                ->leftJoin('ledger_masters', 'ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
                ->whereIn('general_ledgers.groupCode', ['DINC01','IINC01'])
                ->where('general_ledgers.is_delete', '=', 'No')
                ->whereBetween('general_ledgers.transactionDate', [$start_date, $end_date])
                ->groupBy('ledger_masters.name', 'ledger_masters.ledgerCode')
                ->get();




            $expenses = general_ledgers::select(
                'ledger_masters.name as ledger_name',
                'ledger_masters.ledgerCode',
                DB::raw('SUM(CASE WHEN general_ledgers.transactionType = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_expenses')
            )
                ->leftJoin('ledger_masters', 'ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
                ->whereIn('general_ledgers.groupCode',['DEXP01','IDEXP01'])
                ->where('general_ledgers.is_delete', '=', 'No')
                ->whereNull('general_ledgers.deleted_at')
                ->whereBetween('general_ledgers.transactionDate', [$start_date, $end_date])
                ->groupBy('ledger_masters.name', 'ledger_masters.ledgerCode')
                ->get();



            //_______Loan Interest Recoverables
            $recoverableAmountTotal = 0;
            $loanmasters = member_loans::where('is_delete', '!=', 'Yes')
                ->where('status', 'Disbursed')
                ->whereDate('loanDate','<=',$end_date)
                ->get();

            if ($loanmasters->count() > 0) {
                foreach ($loanmasters as $loanmaster) {
                    $loan_recovery = loan_recoveries::where('loanId', $loanmaster->id)
                        ->where('is_delete', 'No')
                        ->where('receiptDate', '<=', $end_date)
                        ->sum('principal');
                    $openingdate = new DateTime($loanmaster->loanDate);
                    $currentdate = new DateTime($end_date);
                    $interval = $openingdate->diff($currentdate);
                    $totalDaysDifference = $interval->days + 1;
                    $recoverableAmount = $loanmaster->loanAmount - $loan_recovery;
                    $perdayinterest = $loanmaster->loanInterest / 365;
                    $calculateformula = (($recoverableAmount * $totalDaysDifference) * $perdayinterest) / 100;
                    $recoverableAmountTotal += round($calculateformula, 0);
                }
            }


            $previousyearexpenses = 0;
            $previousyearincomes = 0;
            $previous_intt_recoverable = 0;

            $financialYear = '';

            if (session("sessionof")) {
                $previousid= DB::table('yearly_session')->where('startdate','>=',$previostart_date)->where('enddate','>=',$previoend_date)->value('id');

                // dd($previousid);
                if ($previousid) {


                        $sYear = date('Y', strtotime($previostart_date));
                        $lYear = date('y', strtotime($previoend_date));

                        $financialYear = $sYear . '-' . $lYear;

                        // Fetch previous year's expenses
                        // $groupExpenses = DB::table('group_masters')->where('type', 'Direct Expenses')->pluck('groupCode');
                        // $previousyearexpenses = general_ledgers::select(
                        //     'ledger_masters.name as ledger_name',
                        //     'ledger_masters.ledgerCode',
                        //     DB::raw('SUM(CASE WHEN general_ledgers.transactionType = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_expenses'),
                        //     'group_masters.name as group_name'
                        // )
                        //     ->leftJoin('ledger_masters', 'ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
                        //     ->leftJoin('group_masters', 'group_masters.groupCode', '=', 'general_ledgers.groupCode')
                        //     ->whereIn('general_ledgers.groupCode', $groupExpenses)
                        //     ->whereBetween('general_ledgers.transactionDate', [$previouseyearstartDate, $previouseyearendDate])
                        //     ->groupBy('ledger_masters.name', 'ledger_masters.ledgerCode', 'group_masters.name')
                        //     ->where('general_ledgers.is_delete', 'No')
                        //     ->get();




                        // // Fetch previous year's income
                        // $groupIncome = DB::table('group_masters')->where('type', 'Direct Expenses')->pluck('groupCode');
                        // $previousyearincomes = general_ledgers::select(
                        //         'ledger_masters.name as ledger_name',
                        //         'ledger_masters.ledgerCode',
                        //         DB::raw('SUM(CASE WHEN general_ledgers.transactionType = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_income'),
                        //         'group_masters.name as group_name'
                        //     )
                        //     ->leftJoin('ledger_masters', 'ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
                        //     ->leftJoin('group_masters', 'group_masters.groupCode', '=', 'general_ledgers.groupCode')
                        //     ->whereIn('general_ledgers.groupCode', $groupIncome)
                        //     ->whereBetween('general_ledgers.transactionDate', [$previouseyearstartDate, $previouseyearendDate])
                        //     ->groupBy('ledger_masters.name', 'ledger_masters.ledgerCode', 'group_masters.name')
                        //     ->where('general_ledgers.is_delete', 'No')
                        //     ->get();



                        //_______Loan Interest Recoverables
                        $previous_intt_recoverable = 0 ;
                        $loanmasters = DB::table('member_loans')
                            ->where('is_delete', '!=', 'Yes')
                            ->where('status','Disbursed')
                            ->whereBetween('loanDate',  [$previostart_date, $previoend_date])
                            ->get();

                        if(count($loanmasters) > 0){
                            $recovory = 0;
                            foreach($loanmasters as $loanmaster){
                                $loan_recovery = DB::table('loan_recoveries')
                                    ->where(['loanId' => $loanmaster->id])
                                    // ->where('is_delete', 'No')
                                    ->where('receiptDate','<=',$previoend_date)
                                    ->sum('principal');
                                //  $recoveryDate = DB::table('loan_recoveries')->where(['loanId'=>$loanmaster->id])->where('is_delete', 'No')->orderBy('receiptDate', 'DESC')->first('receiptDate');
                                $recovory=$loanmaster->loanAmount - $loan_recovery;
                                $openingdate = new DateTime($loanmaster->loanDate);
                                $currentdate = new DateTime($previoend_date);
                                $interval = $openingdate->diff($currentdate);
                                $totalDaysDifference = $interval->days + 1;
                                $previous_intt_recoverable += $recovory * $totalDaysDifference * $loanmaster->loanInterest / 36500;
                            }
                        }

                }
            }
            return response()->json([
                'status' => 'success',
                'incomes' => $incomes,
                'expenses' => $expenses,
                // 'previousyearexpenses' => $previousyearexpenses,
                // 'previousyearincomes' => $previousyearincomes,
                'intt_recoverable' => floor($recoverableAmountTotal),
                'previous_intt_recoverable' => $previous_intt_recoverable,
                'financialYear' => $financialYear
            ]);
        }
    }
}
