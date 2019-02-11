<div class="modal fade bd-example-modal-xl" id="myaccountModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered  modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{Auth::user()->first_name}}, {{Auth::user()->last_name}} <br> <span class="badge badge-pill badge-success">{{Auth::user()->status}}</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body loader">
        <div class="row">
          <div class="col-md-1">
          </div>
          <div class="col-md-10">
            <form id="myaccountForm" data-parsley-validate>
              @csrf
              <div class="form-group">
                <p>Fields with (<span class="text-danger">*</span>) are required.</p>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>USERNAME <span class="text-danger">*</span></strong></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="username" required value="{{Auth::user()->username}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>FIRST NAME <span class="text-danger">*</span></strong></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="first_name" required value="{{Auth::user()->first_name}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>LAST NAME <span class="text-danger">*</span></strong></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="last_name" required value="{{Auth::user()->last_name}}">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>CONTACT NUMBER <span class="text-danger">*</span></strong></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" name="contact_number" required value="{{Auth::user()->contact_number}}">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label float-right"><strong>STATUS</strong></label>
                <div class="col-sm-8">
                  <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" name="status" class="custom-control-input myaccountstatus" id="customCheck2" {!! Auth::user()->status == "Active" ? "checked" : "" !!}>
                    <label class="custom-control-label" for="customCheck2">Active</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btnUpdate" data-loading-text="<i class='fas fa-circle-notch fa-spin'></i> Saving...">Update</button>
        <button type="button" class="btn btn-danger btnResetMyaccountPassword" data-loading-text="<i class='fas fa-circle-notch fa-spin'></i> Resetting...">Change password</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>