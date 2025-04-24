<?php

namespace App\DataTables;

use App\Models\salemaster;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Yajra\DataTables\DataTables
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('actions', function ($invoice) {
                return '<a target="_blank" href="' . url('generate-pdf/' . $invoice->invoiceno) . '"><i class="bx bxs-printer"></i></a>';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Invoice $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return salemaster::query();
    }

    /**
     * Optional method if you want to use the DataTables library to customize the datatable.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns([
                ['data' => 'invoicenodate', 'name' => 'invoicenodate', 'title' => 'Date'],
                ['data' => 'invoiceno', 'name' => 'invoiceno', 'title' => 'Invoice No'],
                ['data' => 'accountname', 'name' => 'accountname', 'title' => 'Party Name'],
                ['data' => 'mode', 'name' => 'mode', 'title' => 'Payment Type'],
                ['data' => 'grandtotal', 'name' => 'grandtotal', 'title' => 'Amount'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false]
            ])
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['copy', 'csv', 'excel', 'pdf', 'print'],
                'order' => [[0, 'desc']]
            ]);
    }
}
