<script>
  // // // View Information
  $(document).on('click', '.infoIcon', function() {
    var button = $(this); // Button that triggered the modal
    var BookId = button.data('id'); // Extract the college book ID from the button's data attribute

    console.log('View button clicked for Book ID: ' + BookId);
    // Fetch college book type details using AJAX
    $.ajax({
      url: 'posview.php', // Replace with your PHP script to fetch book type details
      method: 'POST',
      data: {
        BookId: BookId
      },
      dataType: 'json',
      success: function(data) {
        // Update the modal content
        console.log('Data received:', data);

        // Manipulate the program names and strand names before displaying in the modal
        var programNames = data.bookDetails.programNames ? data.bookDetails.programNames.replace(/,(?=[^ ])/g, ',\n') : '';
        var strandNames = data.bookDetails.strandNames ? data.bookDetails.strandNames.replace(/,(?=[^ ])/g, ',\n') : '';
        console.log('Data received:', data);
        $('#bookId').text(data.bookDetails.bookId);
        $('#title').text(data.bookDetails.title);
        $('#author').text(data.bookDetails.author);
        $('#publicationYear').text(data.bookDetails.publicationYear);
        $('#quantityAvailable').text(data.bookDetails.quantityAvailable);
        $('#price').text('₱' + data.bookDetails.price);
        $('#subjectCodes').text(data.bookDetails.subjectCodes);
        $('#yearlevelName').text(data.bookDetails.yearlevelName);
        $('#yearlevelType').text(data.bookDetails.yearlevelType);
        $('#programNames').text(programNames);
        // Check the book type and hide the entire row for "Program Name" if it's High School or Senior High
        if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'Senior High') {
          $('#programNamesRow').hide(); // Hide the entire row
        } else {
          $('#programNamesRow').show(); // Show the entire row
        }
        $('#strandNames').text(strandNames);
        if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'College') {
          $('#strandNamesRow').hide(); // Hide the entire row
        } else {
          $('#strandNamesRow').show(); // Show the entire row
        }
        $('#status').text(data.bookDetails.status);
      },
      error: function(xhr, status, error) {
        // Handle errors, if necessary
      }
    });
  });

  ////


  // // // View Book Modal
  $(document).on('click', '.view-button', function() {
    var button = $(this); // Button that triggered the modal
    var BookId = button.data('book_id'); // Extract the college book ID from the button's data attribute

    console.log('View button clicked for Book ID: ' + BookId);
    // Fetch college book type details using AJAX
    $.ajax({
      url: 'book_view.php', // Replace with your PHP script to fetch book type details
      method: 'POST',
      data: {
        BookId: BookId
      },
      dataType: 'json',
      success: function(data) {
        // Update the modal content
        console.log('Data received:', data);

        // Manipulate the program names and strand names before displaying in the modal
        var programNames = data.bookDetails.programNames ? data.bookDetails.programNames.replace(/,(?=[^ ])/g, ',\n') : '';
        var strandNames = data.bookDetails.strandNames ? data.bookDetails.strandNames.replace(/,(?=[^ ])/g, ',\n') : '';
        console.log('Data received:', data);
        $('#bookId').text(data.bookDetails.bookId);
        $('#title').text(data.bookDetails.title);
        $('#author').text(data.bookDetails.author);
        $('#publicationYear').text(data.bookDetails.publicationYear);
        $('#quantityAvailable').text(data.bookDetails.quantityAvailable);
        $('#price').text('₱' + data.bookDetails.price);
        $('#subjectCodes').text(data.bookDetails.subjectCodes);
        $('#yearlevelName').text(data.bookDetails.yearlevelName);
        $('#yearlevelType').text(data.bookDetails.yearlevelType);
        $('#programNames').text(programNames);
        // Check the book type and hide the entire row for "Program Name" if it's High School or Senior High
        if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'Senior High') {
          $('#programNamesRow').hide(); // Hide the entire row
        } else {
          $('#programNamesRow').show(); // Show the entire row
        }
        $('#strandNames').text(strandNames);
        if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'College') {
          $('#strandNamesRow').hide(); // Hide the entire row
        } else {
          $('#strandNamesRow').show(); // Show the entire row
        }
        $('#status').text(data.bookDetails.status);
      },
      error: function(xhr, status, error) {
        // Handle errors, if necessary
      }
    });
  });

  // View button
  echo '<button class="btn btn-outline-info view-button" data-toggle="modal" data-target="#viewBookModal" title="View" data-book_id="'.$row["book_id"].
  '"><i class="bx bxs-show"></i></button>';

  echo '<button class="btn btn-secondary btn-sm infoIcon" data-toggle="modal" data-target="#viewInfo" data-id="'.$row['book_id'].
  '" title="Book Information">';
  echo '<i class="bx bxs-info-circle"></i>';
  echo '</button>';


  <
  div class = "modal fade"
  id = "viewInfo"
  tabindex = "-1"
  role = "dialog"
  aria - labelledby = "viewBookModalLabel"
  aria - hidden = "true" >
    <
    div class = "modal-dialog"
  role = "document" >
    <
    div class = "modal-content" >
    <
    div class = "modal-header" >
    <
    h5 class = "modal-title"
  id = "viewBookModalLabel" > Book Information < /h5> < /
    div > <
    div class = "modal-body" >
    <
    table class = "table" >
    <
    tr >
    <
    td width = "30%" > < label
  for = "title" > Title < /label></td >
    <
    td width = "70%" > < span id = "title" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "author" > Author < /label></td >
    <
    td width = "70%" > < span id = "author" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "publicationYear" > Publication Year < /label></td >
    <
    td width = "70%" > < span id = "publicationYear" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "quantityAvailable" > Quantity Available < /label></td >
    <
    td width = "70%" > < span id = "quantityAvailable" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "price" > Price < /label></td >
    <
    td width = "70%" > < span id = "price" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "subjectCodes" > Subject Code < /label></td >
    <
    td width = "70%" > < span id = "subjectCodes" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "yearlevelName" > Year Levels < /label></td >
    <
    td width = "70%" > < span id = "yearlevelName" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "yearlevelType" > Book Type < /label></td >
    <
    td width = "70%" > < span id = "yearlevelType" > < /span></td >
    <
    /tr> <
  tr id = "programNamesRow" >
    <
    td width = "30%" > < label
  for = "programNames" > Programs < /label></td >
    <
    td width = "70%" > < span id = "programNames"
  style = "white-space: pre-line;" > < /span></td >
    <
    /tr> <
  tr id = "strandNamesRow" >
    <
    td width = "30%" > < label
  for = "strandNames" > Strands < /label></td >
    <
    td width = "70%" > < span id = "strandNames"
  style = "white-space: pre-line;" > < /span></td >
    <
    /tr> < /
    table > <
    /div> <
  div class = "modal-footer" >
  <
  button type = "button"
  class = "btn btn-secondary"
  data - dismiss = "modal" > Close < /button> < /
    div > <
    /div> < /
    div > <
    /div>

    <
    !-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- - VIEW MODAL INVENTORY-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- - -- >
    <
    div class = "modal fade"
  id = "viewBookModal"
  tabindex = "-1"
  role = "dialog"
  aria - labelledby = "viewBookModalLabel"
  aria - hidden = "true" >
    <
    div class = "modal-dialog"
  role = "document" >
    <
    div class = "modal-content" >
    <
    div class = "modal-header" >
    <
    h5 class = "modal-title"
  id = "viewBookModalLabel" > Book Info < /h5> < /
    div > <
    div class = "modal-body" >
    <
    table class = "table" >
    <
    tr >
    <
    td width = "30%" > < label
  for = "title" > Title < /label></td >
    <
    td width = "70%" > < span id = "title" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "author" > Author < /label></td >
    <
    td width = "70%" > < span id = "author" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "publicationYear" > Publication Year < /label></td >
    <
    td width = "70%" > < span id = "publicationYear" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "quantityAvailable" > Quantity Available < /label></td >
    <
    td width = "70%" > < span id = "quantityAvailable" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "price" > Price < /label></td >
    <
    td width = "70%" > < span id = "price" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "subjectCodes" > Subject Code < /label></td >
    <
    td width = "70%" > < span id = "subjectCodes" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "yearlevelName" > Year Levels < /label></td >
    <
    td width = "70%" > < span id = "yearlevelName" > < /span></td >
    <
    /tr> <
  tr >
    <
    td width = "30%" > < label
  for = "yearlevelType" > Book Type < /label></td >
    <
    td width = "70%" > < span id = "yearlevelType" > < /span></td >
    <
    /tr> <
  tr id = "programNamesRow" >
    <
    td width = "30%" > < label
  for = "programNames" > Programs < /label></td >
    <
    td width = "70%" > < span id = "programNames"
  style = "white-space: pre-line;" > < /span></td >
    <
    /tr> <
  tr id = "strandNamesRow" >
    <
    td width = "30%" > < label
  for = "strandNames" > Strands < /label></td >
    <
    td width = "70%" > < span id = "strandNames"
  style = "white-space: pre-line;" > < /span></td >
    <
    /tr> < /
    table > <
    /div> <
  div class = "modal-footer" >
  <
  button type = "button"
  class = "btn btn-secondary"
  data - dismiss = "modal" > Close < /button> < /
    div > <
    /div> < /
    div > <
    /div>