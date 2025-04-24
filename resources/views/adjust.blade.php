@include('include.header')
<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ $formurl }}" method="POST">
          @csrf
          <div class="row">
            <div class="mb-2 col-md-4">
              <label class="form-label">Select Item</label>
              <select id="selectize-optgroup" name="item"  placeholder="Select Item" required>
                <option value="">Select Item</option>
                      @if(sizeof($items)>0)
                      @foreach ($items as $itemslist)
                         <option  @if(!empty($categorysid->brand)) @if($categorysid->brand==$itemslist->id) @selected(true) @endif  @endif value="{{ $itemslist->itemcode }}">({{ $itemslist->itemcode }}){{ $itemslist->name }}</option>
                      @endforeach
                      @endif
              </select>
            </div>
            </div>
            <div class="row">
            <div class="mb-2 col-md-4">
              <label class="form-label">Unit Type</label>
              <select  name="unittype" required class="form-select" required>
              <option value="">Select Unit Type</option>
              <option value="box">Box</option>
              <option value="pieces">Pieces</option>
              </select>
            </div>
            <div class="mb-2 col-md-4">
              <label class="form-label">Adjustment Type</label>
              <select  name="adjustmenttype" required class="form-select" required>
              <option value="">Select Adjustment Type</option>
              <option value="sale">Sale</option>
              <option value="purchase">Purchase</option>
              </select>
            </div>
            <div class="mb-2 col-md-4">
              <label class="form-label">Quantity</label>
              <input type="text" name="quantity" class="form-control" placeholder="Quantity" required>

             </div>
          </div>
          <div class="row mt-4">
            <div class="mb-2 col-md-4">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@include('include.footer')
