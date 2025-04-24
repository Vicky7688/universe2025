<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\groups;
use App\Models\general_ledgers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MemberLoan;
use App\Models\LoanRecovery;
use DateTime;
use Carbon;

class BalanceSheetController extends Controller
{

    public function balanceSheetIndex(){
        $pagetitle = "Balance Sheet";
        $pageto = url('balancesheetindex');
        $formurl = url('balancesheetindex');
        $fyears = DB::table('yearly_session')->get();

        $data=compact('pagetitle','pageto','formurl');
        return view('Reports.balancesheet')->with($data);
    }
    public function getbalanceSheet(Request $request)
{
    // Process the input dates
    $startDate = date('Y-m-d', strtotime($request->startDate));
    $endDate = date('Y-m-d', strtotime($request->endDate));

    // Set financial year
    $sYear = date('Y', strtotime($startDate));
    $lYear = date('y', strtotime($endDate));
    $currentFinancialYear = $sYear . '-' . $lYear;

    // Fetch Assets Data
    $groupAssets = DB::table('group_masters')->where('type', 'Asset')->pluck('groupCode')->toArray();
    $assetsOpening = $this->getLedgerData($groupAssets, $startDate, $endDate);

    // dd($assetsOpening);

    // Fetch Liabilities Data
    $groupLiabilities = DB::table('group_masters')->where('type', 'Liability')->pluck('groupCode')->toArray();
    $liabilitiesOpening = $this->getLedgerData($groupLiabilities, $startDate, $endDate);



    // Fetch Expenses Data
    $groupExpenses = DB::table('group_masters')->whereIn('type', ['Direct Expenses','Indirect Expenses'])->pluck('groupCode');
    $expenses = $this->getIncomeExpenseData($groupExpenses, $startDate, $endDate);

    // Fetch Income Data
    $groupIncome = DB::table('group_masters')->whereIn('type', ['Direct Income','Indirect Income'])->pluck('groupCode');

    $incomes = $this->getIncomeExpenseData($groupIncome, $startDate, $endDate);

    // Fetch previous year data if available
    $previousYearData = $this->getPreviousYearData(session('sessionof'));

    $intt_recoverable = 0;
    $loanmasters = DB::table('member_loans')
        ->where('is_delete', '!=', 'Yes')
        ->where('status', 'Disbursed')
        ->whereDate('loanDate','<=',$endDate)
        ->get();

    if ($loanmasters->count() > 0) {
        $recovory = 0;
        foreach ($loanmasters as $loanmaster) {
            $loan_recovery = DB::table('loan_recoveries')
                ->where(['loanId' => $loanmaster->id])
                ->where('receiptDate', '<=', $endDate)
                ->sum('principal');

            $recovory = $loanmaster->loanAmount - $loan_recovery;
            $openingdate = new DateTime($loanmaster->loanDate);
            $currentdate = new DateTime($endDate);
            $interval = $openingdate->diff($currentdate);
            $totalDaysDifference = $interval->days + 1;
            $intt_recoverable += $recovory * $totalDaysDifference * $loanmaster->loanInterest / 36500;
        }
    }

    return response()->json([
        'status' => 'success',
        'assets' => $assetsOpening,
        'liabilities' => $liabilitiesOpening,
        'incomes' => $incomes,
        'expenses' => $expenses,
        'intt_recoverable' => $intt_recoverable ?? 0,
        'previousyearexpenses' => $previousYearData['expenses'] ?? [],
        'previous_intt_recoverable' => $previousYearData['previous_intt_recoverable'] ?? [],
        'previousyearincomes' => $previousYearData['incomes'] ?? [],
        'financialYear' => $previousYearData['financialYear'] ?? '',
        'currentfinancialYear' => $currentFinancialYear
    ]);
}

private function getLedgerData(array $groupCodes, $startDate, $endDate)
{
   return  DB::table('ledger_masters')
        ->leftJoin('general_ledgers', function ($join) use ($startDate, $endDate) {
            $join->on('ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
                ->whereDate('general_ledgers.transactionDate', '>=', $startDate)
                ->whereDate('general_ledgers.transactionDate', '<=', $endDate)
                ->orWhereNull('general_ledgers.transactionDate');
        })
        ->leftJoin('group_masters', 'group_masters.groupCode', '=', 'ledger_masters.groupCode')
        ->select(
            'ledger_masters.ledgerCode',
            'ledger_masters.name as lname',
            'ledger_masters.openingAmount',
            'ledger_masters.groupCode as ledger_groupCode',
            DB::raw('COALESCE(SUM(CASE WHEN general_ledgers.transactionType = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END), 0) as total_debit'),
            DB::raw('COALESCE(SUM(CASE WHEN general_ledgers.transactionType = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END), 0) as total_credit'),
            'group_masters.name as gname',
            'group_masters.groupCode'
        )
        ->whereIn('group_masters.groupCode', $groupCodes)
        ->groupBy('ledger_masters.ledgerCode', 'ledger_masters.name', 'ledger_masters.openingAmount', 'ledger_masters.groupCode', 'group_masters.name', 'group_masters.groupCode')
        ->get()
        ->map(function ($data) use ($startDate) {
            $drOpening = $this->getOpeningBalance($data->ledgerCode, $data->groupCode, 'Dr', $startDate);

            $crOpening = $this->getOpeningBalance($data->ledgerCode, $data->groupCode, 'Cr', $startDate);

            $openingCash = $data->openingAmount + $drOpening - $crOpening;
            // dd($openingCash);

            return [
                'groupCode' => $data->groupCode,
                'group_name' => $data->gname,
                'ledgerCode' => $data->ledgerCode,
                'ledger_name' => $data->lname,
                'opening_balance' => $openingCash,
                'total_debit' => $data->total_debit,
                'total_credit' => $data->total_credit,
            ];
            // dd($vicky);
        });

}


private function getOpeningBalance($ledgerCode, $groupCode, $transactionType, $startDate)
{
    return DB::table('general_ledgers')
        ->where('transactionType', $transactionType)
        ->where('groupCode', $groupCode)
        ->where('ledgerCode', $ledgerCode)
        ->where('transactionDate', '<=', $startDate)
        ->where('is_delete', '=', 'No')
        ->whereNull('deleted_at')
        ->sum('transactionAmount');


}

private function getIncomeExpenseData($groupCodes, $startDate, $endDate)
{
    return DB::table('general_ledgers')
        ->select(
            'ledger_masters.name as lname',
            'ledger_masters.ledgerCode as lgcode',
            DB::raw('SUM(CASE WHEN general_ledgers.transactiontype = "Dr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_debit'),
            DB::raw('SUM(CASE WHEN general_ledgers.transactiontype = "Cr" THEN general_ledgers.transactionAmount ELSE 0 END) as total_credit'),
            'group_masters.headName',
            'general_ledgers.is_delete'
        )
        ->leftJoin('ledger_masters', 'ledger_masters.ledgerCode', '=', 'general_ledgers.ledgerCode')
        ->leftJoin('group_masters', 'group_masters.groupCode', '=', 'general_ledgers.groupCode')
        ->whereIn('general_ledgers.groupCode', $groupCodes)
        ->whereDate('general_ledgers.transactionDate', '>=', $startDate)
        ->whereDate('general_ledgers.transactionDate', '<=', $endDate)
        ->where('general_ledgers.is_delete', '=', 'No')
        ->whereNull('general_ledgers.deleted_at')
        ->groupBy('general_ledgers.is_delete', 'ledger_masters.name', 'ledger_masters.ledgerCode', 'group_masters.headName')
        ->get();
}


    private function getPreviousYearData($currentSession)
    {
        if (!$currentSession) return [];

        $previousSessionId = $currentSession - 1;
        $previousSession = DB::table('yearly_session')->where('id', $previousSessionId)->first();
        if (!$previousSession) return [];

        $previousYearStartDate = date('Y-m-d', strtotime($previousSession->startdate));
        $previousYearEndDate = date('Y-m-d', strtotime($previousSession->enddate));

        $sYear = date('Y', strtotime($previousSession->startdate));
        $lYear = date('y', strtotime($previousSession->enddate));
        $financialYear = $sYear . '-' . $lYear;


        // Fetch previous year expenses and incomes
        $groupExpenses = DB::table('group_masters')->whereIn('type', ['Direct Expenses','Indirect Expenses'])->pluck('groupCode');
        $previousYearExpenses = $this->getIncomeExpenseData($groupExpenses, $previousYearStartDate, $previousYearEndDate);

        $groupIncome = DB::table('group_masters')->whereIn('type', ['Direct Income','Indirect Income'])->pluck('groupCode');
        $previousYearIncomes = $this->getIncomeExpenseData($groupIncome, $previousYearStartDate, $previousYearEndDate);



        $previous_intt_recoverable = 0 ;
        $loanmasters = DB::table('member_loans')
            ->where('is_delete', '!=', 'Yes')
            ->where('status','Disbursed')
            ->whereBetween('loanDate', [$previousYearStartDate,$previousYearEndDate])
            ->get();

        if(count($loanmasters) > 0){
            $recovory = 0;
            foreach($loanmasters as $loanmaster){
                 $loan_recovery = DB::table('loan_recoveries')
                    ->where(['loanId' => $loanmaster->id])
                    // ->where('is_delete', 'No')
                    ->where('receiptDate','<=',$previousYearEndDate)
                    ->sum('principal');
                //  $recoveryDate = DB::table('loan_recoveries')->where(['loanId'=>$loanmaster->id])->where('is_delete', 'No')->orderBy('receiptDate', 'DESC')->first('receiptDate');
                $recovory=$loanmaster->loanAmount - $loan_recovery;
                $openingdate = new DateTime($loanmaster->loanDate);
                $currentdate = new DateTime($previousYearEndDate);
                $interval = $openingdate->diff($currentdate);
                $totalDaysDifference = $interval->days + 1;
                $previous_intt_recoverable += $recovory * $totalDaysDifference * $loanmaster->loanInterest / 36500;
            }
        }

        return [
            'previous_intt_recoverable' => $previous_intt_recoverable,
            'expenses' => $previousYearExpenses,
            'incomes' => $previousYearIncomes,
            'financialYear' => $financialYear,
        ];
    }


}
