<?php

    include_once '../authentication.php';
    include_once '../includes.php';

?>

<!-- ------------------------------------------------- -->
<input type="hidden" id="selectedYearLevelTypes" name="selectedYearLevelTypes" value="">
<!-- POS Book Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Search Books</h5>
            </div>
              <div class="modal-body">
                <form>
                      <div class="form-group">
                        <label for="searchType">Select Book Type:</label>
                        <select class="form-control" id="searchType">
                            <option value="all">All</option>
                            <?php
                            // Fetch data from your tbl_yearlevels table
                            $query = "SELECT year_level_type FROM tbl_yearlevels GROUP BY year_level_type";
                            $result = $db_connection->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Output an <option> for each year level
                                    echo '<option value="' . $row['year_level_type'] . '">' . $row['year_level_type'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                      </div>
                    <div class="form-group">
                        <label for="searchTitle">Title:</label>
                        <input type="text" class="form-control" id="searchTitle" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="searchAuthor">Author:</label>
                        <input type="text" class="form-control" id="searchAuthor" placeholder="Enter author">
                    </div>
                    <div class="form-group">
                      <label for="searchPublicationYear">Publication Year:</label>
                      <input type="text" class="form-control" id="searchPublicationYear" placeholder="Enter publication year" pattern="\d{4}" title="Please enter a valid 4-digit year">
                  </div>
                    <div class="form-group">
                        <label for="searchSubjectCode">Subject Code:</label>
                        <select class="form-control" id="searchSubjectCode" placeholder="Enter subject code">
                            <option value="all" selected>All</option>
                            <?php

                            $query = "SELECT subject_id, subject_code FROM tbl_subjects";
                            $result = $db_connection->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    echo '<option value="' . $row['subject_id'] . '">' . $row['subject_code'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="searchYearLevel">Year Level:</label>
                        <select class="form-control" id="searchYearLevel" placeholder="Year Level">
                            <option value="all">All</option>
                            <!-- Year level options will be populated dynamically using JavaScript -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------- VIEW INFO -->

<div class="modal fade" id="viewInfo" tabindex="-1" role="dialog" aria-labelledby="viewBookModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewBookModalLabel">Book Information</h5>
      </div>
      <div class="modal-body">
        <table class="table">
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
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- VIEW IMAGE ENLARGED -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="enlargedImg" src="" alt="Enlarged Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

	<!-- RECEIPT NUMBER INFO -->
	<div class="modal fade" id="receiptInfoModal" tabindex="-1" role="dialog" aria-labelledby="receiptInfoModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="receiptInfoModalLabel">Receipt Number Information</h5>
				</div>
				<div class="modal-body">
        <p>You can get the receipt number from the official receipts that are provided during transactions. If you don't have one, you can type in 0. If you enter 0, the system will automatically generate a unique identification number for you.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>