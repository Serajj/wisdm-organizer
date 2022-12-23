<?php 
$post_data = filter_var_array( $_POST, FILTER_UNSAFE_RAW ); // phpcs:ignore WordPress.Security.NonceVerification

print_r($post_data);
?>
<div class="row-fluid m-5">
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Add Event
</button>
</div>
<div class="container">
<div class="row-fluid m-5">

<table class="table mytable">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
</div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Add Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="">

      <div class="form-group">
    <label for="inputAddress">Event Title</label>
    <input type="text" name="title" class="form-control" id="inputAddress" placeholder="Start Date">
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Start Date</label>
      <input type="date" class="form-control" id="inputEmail4" placeholder="Start Date">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">End Date</label>
      <input type="date" class="form-control" id="inputPassword4" placeholder="End Date">
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress">Description</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="Description">
  </div>
  <div class="form-row">
    <div class="form-group col-md-4">
      <label for="inputCity">Label</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">Value</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-2 mt-2">
    <label for="inputAddress"></label>
    <button type="button" class="btn btn-info">Add Field</button>
    </div>
  </div>
 
  <button name="event_wsdm" type="submit" class="btn btn-primary">Add Now</button>
</form>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
