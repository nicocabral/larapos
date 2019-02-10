<div class="modal fade bd-example-modal-xl" id="salesModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered  modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sales List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body loader">
        <div class="row">
          <div class="col-md-9">
            <h6 class="float-right mt-2"><strong><i class="fas fa-filter"></i> Filter by</strong></h6>
          </div>
          <div class="col-md-3">
           <input type="text" class="form-control datepicker filterSales" name="filterSales">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-condensed table-hover table-striped" width="100%" id="salesTable">
              <thead>
                <tr>
                  <th></th>
                  <th>ID</th>
                  <th>TOTAL</th>
                  <th>PAID AMOUNT</th>
                  <th>STATUS</th>
                  <th>DATE</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>