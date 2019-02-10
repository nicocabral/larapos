<div class="modal fade bd-example-modal-xl" id="itemModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered  modal-xl" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Items List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body loader">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-condensed table-hover" width="100%" id="itemTable">
              <thead>
                <tr>
                  <th>SKU/CODE</th>
                  <th>NAME</th>
                  <th>QTY</th>
                  <th>PRICE</th>
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