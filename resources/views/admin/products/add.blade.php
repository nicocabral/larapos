<div class="modal fade bd-example-modal-xl" id="modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered  modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body loader">
        <div class="row">
          <div class="col-md-1">
          </div>
          <div class="col-md-10">
            <form id="productForm" data-parsley-validate>
              @csrf
              <div class="form-group">
                <p>Fields with (<span class="text-danger">*</span>) are required.</p>
              </div>
              <div class="form-group row">
                <label  class="col-sm-2 col-form-label float-right"><strong>CATEGORY <span class="text-danger">*</span></strong></label>
                <div class="col-sm-6">
                 <select name="category_id" class="custom-select">
                  <option value="">--SELECT--</option>  
                    @if(isset($cat))
                      @foreach($cat as $k => $v) 
                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>SKU/CODE <span class="text-danger">*</span></strong></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="sku" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>NAME <span class="text-danger">*</span></strong></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>DESCRIPTION</strong></label>
                <div class="col-sm-10">
                  <textarea name="description" class="form-control"rows="5"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>QTY <span class="text-danger">*</span></strong></label>
                <div class="col-sm-3">
                  <input type="number" class="form-control number" name="qty" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>PRICE <span class="text-danger">*</span></strong></label>
                <div class="col-sm-3">
                  <input type="number" class="form-control number" name="price" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>STATUS</strong></label>
                <div class="col-sm-10">
                  <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" name="status" class="custom-control-input status" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Active</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btnSave" data-loading-text="<i class='fas fa-circle-notch fa-spin'></i> Saving...">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>