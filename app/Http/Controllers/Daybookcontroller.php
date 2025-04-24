<?php

namespace App\Http\Controllers;

use App\Models\general_ledgers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class Daybookcontroller extends Controller
{
    public function daybook()
    {
        $pagetitle = "Daybook";
        $pageto = url("daybook");
        $formurl = url("daybook");
        $data = compact("formurl", "pagetitle", "pageto");
        return view("daybook")->with($data);
    }


    public function daybookdata(Request $request)
    {
    $formattedFromDate = Carbon::createFromFormat("d-m-Y", $request->datefrom)->startOfDay()->toDateTimeString();
    $formattedToDate = Carbon::createFromFormat("d-m-Y", $request->dateto)->endOfDay()->toDateTimeString();


    $data["openingBalancepurchase"] = DB::table("general_ledgers")->where("transactionType", "=", "Dr")->where("transactionDate", "<", $formattedFromDate)->sum("transactionAmount");

    $data["purchaseold"] = DB::table("general_ledgers")
        ->join("group_masters", "general_ledgers.groupCode", "=", "group_masters.groupCode")
        ->join("ledger_masters", "general_ledgers.ledgerCode", "=", "ledger_masters.ledgerCode")
        ->where("general_ledgers.transactionType", "=", "Dr")
        ->where("general_ledgers.ledgerCode", "!=", "CAS001")
        ->where("general_ledgers.transactionDate", "<", $formattedFromDate)
        ->select(
            "general_ledgers.id",
            "general_ledgers.transactionAmount",
            "general_ledgers.transactionDate",
            "general_ledgers.accountNo",
            "general_ledgers.formName",
            "group_masters.name as group_name",
            "ledger_masters.name as ledgers_name"
        )
        ->orderBy("ledger_masters.name")
        ->orderBy("general_ledgers.transactionDate")
        ->get();
    $data["sum_transactionAmount"] = $data["purchaseold"]->sum("transactionAmount");


    $data["saleold"] = DB::table("general_ledgers")
        ->join("group_masters", "general_ledgers.groupCode", "=", "group_masters.groupCode")
        ->join("ledger_masters", "general_ledgers.ledgerCode", "=", "ledger_masters.ledgerCode")
        ->where("general_ledgers.transactionType", "=", "Cr")
        ->where("general_ledgers.ledgerCode", "!=", "CAS001")
        ->where("general_ledgers.transactionDate", "<", $formattedFromDate)
        ->select(
            "general_ledgers.id",
            "general_ledgers.transactionAmount",
            "general_ledgers.transactionDate",
            "general_ledgers.accountNo",
            "general_ledgers.formName",
            "group_masters.name as group_name",
            "ledger_masters.name as ledgers_name"
        )
        ->orderBy("ledger_masters.name")
        ->orderBy("general_ledgers.transactionDate")
        ->get();

    $data["sum_transactionAmount"] = $data["saleold"]->sum("transactionAmount");
    $data["openingBalancepurchase"] = $data["sum_transactionAmount"] - $data["sum_transactionAmount"];



    $data["purchase"] = DB::table("general_ledgers")
        ->join("group_masters", "general_ledgers.groupCode", "=", "group_masters.groupCode")
        ->join("ledger_masters", "general_ledgers.ledgerCode", "=", "ledger_masters.ledgerCode")
        ->join("agent_masters", "general_ledgers.agentId", "=", "agent_masters.id")
        ->where("general_ledgers.transactionType", "=", "Dr")
        ->where("general_ledgers.ledgerCode", "!=", "CAS001")
        ->whereBetween("general_ledgers.transactionDate", [
            $formattedFromDate,
            $formattedToDate,
        ])
        ->select(
            "general_ledgers.id",
            "general_ledgers.transactionAmount",
            "general_ledgers.transactionDate",
            "general_ledgers.accountNo",
            "general_ledgers.formName",
            "group_masters.name as group_name",
            "ledger_masters.name as ledgers_name",
            "agent_masters.id as agtId",
            "agent_masters.name as agent_name"
        )
        ->orderBy("ledger_masters.name")
        ->orderBy("general_ledgers.transactionDate")
        ->get();

    $data["sale"] = DB::table("general_ledgers")
        ->join("group_masters", "general_ledgers.groupCode", "=", "group_masters.groupCode")
        ->join("ledger_masters", "general_ledgers.ledgerCode", "=", "ledger_masters.ledgerCode")
        ->join("agent_masters", "general_ledgers.agentId", "=", "agent_masters.id")
        ->where("general_ledgers.transactionType", "=", "Cr")
        ->where("general_ledgers.ledgerCode", "!=", "CAS001")
        ->whereBetween("general_ledgers.transactionDate", [
            $formattedFromDate,
            $formattedToDate,
        ])
        ->select(
            "general_ledgers.id",
            "general_ledgers.transactionAmount",
            "general_ledgers.transactionDate",
            "general_ledgers.accountNo",
            "general_ledgers.formName",
            "group_masters.name as group_name",
            "ledger_masters.name as ledgers_name",
            "agent_masters.id as agtId",
            "agent_masters.name as agent_name"
        )
        ->orderBy("ledger_masters.name")
        ->orderBy("general_ledgers.transactionDate")
        ->get();

    return response()->json($data);
}

}
