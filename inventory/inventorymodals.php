<!----------------------------------------------- VIEW MODAL INVENTORY  ----------------------------------------- -->
<div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog" aria-labelledby="viewBookModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewBookModalLabel">Book Info</h5>
      </div>
      <div class="modal-body">
        <table class="table">
          <tr>
            <td width="30%"><label for="isBn">ISBN</label></td>
            <td width="70%"><span id="isBn"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="title">Title</label></td>
            <td width="70%"><span id="title"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="author">Author</label></td>
            <td width="70%"><span id="author"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="publicationYear">Publication Year</label></td>
            <td width="70%"><span id="publicationYear"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="quantityAvailable">Quantity Available</label></td>
            <td width="70%"><span id="quantityAvailable"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="price">Price</label></td>
            <td width="70%"><span id="price"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="subjectCodes">Subject Code</label></td>
            <td width="70%"><span id="subjectCodes"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="yearlevelName">Year Levels</label></td>
            <td width="70%"><span id="yearlevelName"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="yearlevelType">Book Type</label></td>
            <td width="70%"><span id="yearlevelType"></span></td>
          </tr>
          <tr id="programNamesRow">
            <td width="30%"><label for="programNames">Programs</label></td>
            <td width="70%"><span id="programNames" style="white-space: pre-line;"></span></td>
          </tr>
          <tr id="strandNamesRow">
            <td width="30%"><label for="strandNames">Strands</label></td>
            <td width="70%"><span id="strandNames" style="white-space: pre-line;"></span></td>
          </tr>
          <tr>
            <td width="30%"><label for="status">Status</label></td>
            <td width="70%"><span id="status"></span></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!----------------------------------------------- EDIT MODAL INVENTORY  ------------------------------------------->
<div class="modal fade" id="editBookModal" tabindex="-1" role="dialog" aria-labelledby="editBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Left column -->
          <div class="col-md-6">
            <div class="form-group" id="collegebookEditOptionGroup">
              <label for="collegebookEditOption">Edit Option</label>
              <select class="form-control" id="collegebookEditOption">
                <option value="collegebookEdit">Select Program (College)</option>
                <option value="bookEditprogram">Edit Program</option>
              </select>
            </div>
            <div class="form-group" id="seniorhighbookEditOptionGroup">
              <label for="seniorhighbookEditOption">Edit Option</label>
              <select class="form-control" id="seniorhighbookEditOption">
                <option value="seniorhighbookEdit">Select Strand (Senior High)</option>
                <option value="bookEditstrand">Edit Strand</option>
              </select>
            </div>
            <form action="book_edit.php" method="POST" id="editBookForm" enctype="multipart/form-data">
              <div class="form-group">
                <label for="bookID">Book ID</label>
                <input type="text" class="form-control" id="bookID" name="bookID" readonly>
              </div>
              <div class="form-group">
                <label for="bookyearLevelType">Book Type</label>
                <input type="text" class="form-control" id="bookyearLevelType" name="bookyearLevelType" readonly>
              </div>
              <div class="form-group">
                <label for="bookIsbn">ISBN</label>
                <input type="text" class="form-control" id="bookIsbn" name="bookIsbn" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
              <div class="form-group">
                <label for="bookYearlevels">Year Levels</label>
                <select class="form-control" id="bookYearlevels" name="bookYearlevels">
                </select>
              </div>
              <div class="form-group">
                <label for="bookSubject">Subject Code</label>
                <select class="form-control" id="bookSubject" name="bookSubject">
                </select>
              </div>
              <div class="form-group">
                <label for="bookTitle">Title</label>
                <input type="text" class="form-control" id="bookTitle" name="bookTitle" required autocomplete="off">
              </div>
              <!-- <div style="border: 1px solid #ccc; margin-top: 20px; padding: 10px;">
                <p><strong>Important:</strong> Option to edit damaged books in this section. Please use the following fields to specify the condition of the book:</p>
                <div class="form-group mt-2">
                  <label for="bookCondition">Book Condition</label>
                  <select class="form-control" id="bookCondition" name="bookCondition">
                    <option value="bookConditionEdit">Select Condition</option>
                    <option value="slight_damage">Slight Damage</option>
                    <option value="moderate_damage">Moderate Damage</option>
                    <option value="severe_damage">Severe Damage</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="quantityDamaged">Quantity Damaged</label>
                  <input type="text" class="form-control" id="quantityDamaged" placeholder="Enter Damaged" name="quantityDamaged" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
              </div> -->
          </div>
          <!-- Right column -->
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="bookImage">Book Image</label>
              <input type="file" class="form-control" id="bookImage" name="bookImage">
            </div>
            <div class="form-group">
              <label for="bookAuthor">Author</label>
              <input type="text" class="form-control" id="bookAuthor" name="bookAuthor" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="bookPrice">Price</label>
              <input type="text" class="form-control" id="bookPrice" name="bookPrice" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="bookPublicationYear">Publication Year</label>
              <select class="form-control" id="bookPublicationYear" name="bookPublicationYear">
                <!-- Options will be dynamically populated here -->
              </select>
            </div>
            </form>
            <div class="form-group quantity-input-group">
              <label for="bookQuantity">Quantity Available</label>
              <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-sm btn-danger" onclick="decrementQuantity()">-</button>
                <input class="quantityInput form-control mx-2" type="text" name="bookQuantity" id="bookQuantity" style="width: 200px; border: 1px solid #ccc; padding: 7.1px; text-align: center;">
                <button class="btn btn-sm btn-success" onclick="incrementQuantity()">+</button>
              </div>
            </div>
            <p><strong>Important:</strong> To maintain accurate records, please review and update the fields related to the book you wish to edit</p>
            <!-- Hidden fields for book ID and book action -->
            <input type="hidden" id="bookID" name="bookID">
            <input type="hidden" id="bookaction" name="bookaction" value="editBook">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateBookButton">Update</button>
      </div>
    </div>
  </div>
</div>

<?php

?>
<!-- Edit Program Modal -->
<div class="modal fade" id="programEditModal" tabindex="-1" role="dialog" aria-labelledby="programEditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="programEditModalLabel">Edit Program Name</h5>
      </div>
      <div class="modal-body">
        <!-- Form for editing program ID with checkboxes -->
        <form action="" method="POST" id="editProgramForm">
          <div class="form-group">
            <label for="bookIDProgram">Book ID</label>
            <input type="text" class="form-control" id="bookIDProgram" name="bookID" readonly>
          </div>
          <div class="alert alert-danger" id="errorFeedback" style="display: none;"></div>
          <div class="form-group" id="programCheckboxes">

          </div>
          <!-- Hidden field for program action -->
          <input type="hidden" id="programAction" name="programAction" value="editProgram">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateProgramButton">Update</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Strand Modal -->
<div class="modal fade" id="strandEditModal" tabindex="-1" role="dialog" aria-labelledby="strandEditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="strandEditModalLabel">Edit Strand Name</h5>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="editStrandForm">
          <div class="form-group">
            <label for="bookIDStrand">Book ID</label>
            <input type="text" class="form-control" id="bookIDStrand" name="bookID" readonly>
          </div>
          <div class="alert alert-danger" id="errorFeedback" style="display: none;"></div>
          <div class="form-group" id="strandCheckboxes">
          </div>
          <!-- Hidden field for strand action -->
          <input type="hidden" id="strandAction" name="strandAction" value="editStrand">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateStrandButton">Update</button>
      </div>
    </div>
  </div>
</div>