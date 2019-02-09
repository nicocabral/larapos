<div class="modal fade bd-example-modal-xl" id="modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered  modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body loader">
        <div class="row">
          <div class="col-md-1">
          </div>
          <div class="col-md-10">
            <form id="categoryForm" data-parsley-validate>
              @csrf
              <div class="form-group">
                <p>Fields with (<span class="text-danger">*</span>) are required.</p>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>NAME <span class="text-danger">*</span></strong></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="name" required>
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