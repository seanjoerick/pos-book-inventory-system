//////////////////////////////// SWAL
// Add an event listener to the "Cancel Transaction" button
const cancelTransactionButton = document.getElementById('cancelTransaction');

cancelTransactionButton.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to cancel the transaction?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms, clear the cart on the server-side
            window.location.href = 'pos.php?clearCart=true';
        }
    });
});

document.getElementById("paymentAmount").addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
    }
});

document.getElementById("receiptNumber").addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const totalAmount = parseFloat(document.getElementById('totalAmount').dataset.totalAmount);
    const confirmTransactionButton = document.getElementById('confirmTransactionButton');

    confirmTransactionButton.addEventListener('click', function () {
        confirmTransaction();
    });

    confirmTransactionButton.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            confirmTransaction();
        }
    });

    async function confirmTransaction() {
        const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
        const receiptNumberInput = document.getElementById('receiptNumber');
        let receiptNumber = receiptNumberInput.value;
    
        console.log('Payment Amount:', paymentAmount);
        console.log('Total Amount:', totalAmount);
    
        // Update the total first
        await updateTotal();
    
        // Use the updated total for further checks
        const updatedTotalAmount = calculateTotalWithDiscounts();

        console.log('Total Updated Amount:', updatedTotalAmount);

        if (isNaN(paymentAmount)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Payment Amount',
                text: 'Please enter a valid payment amount.',
            });
            return;
        }
    
        if (paymentAmount < updatedTotalAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Insufficient Payment',
                text: 'The payment amount is less than the updated total. Please enter a sufficient payment amount.',
            });
            return;
        }
    
        // Check if a customer is selected
        const selectedCustomerId = document.getElementById('existingStudentNumber').value;
        if (typeof selectedCustomerId === 'undefined' || selectedCustomerId === null || selectedCustomerId === '') {
            Swal.fire({
                icon: 'error',
                title: 'Customer Not Selected',
                text: 'Please select a customer before finalizing the transaction.',
            });
            return;
        }

        const selectedAdvisoryTeacherId = document.getElementById('advisoryTeacher').value;

        if (typeof selectedAdvisoryTeacherId === 'undefined' || selectedAdvisoryTeacherId === null || selectedAdvisoryTeacherId === '') {
            Swal.fire({
                icon: 'error',
                title: 'Advisory Teacher Not Selected',
                text: 'Please select an advisory teacher before finalizing the transaction.',
            });
            return;
        }

        const selectedCustomerName = document.getElementById('existingCustomerName').value;
        if (typeof selectedCustomerName === 'undefined' || selectedCustomerName === '') {
            Swal.fire({
                icon: 'error',
                title: 'Customer Not Selected',
                text: 'Please select a customer before finalizing the transaction.',
            });
            return;
        }

        else {
            // Check if the receipt number is 0
            if (receiptNumber === '0') {
                // Generate a random 10-digit number and check if it already exists
                do {
                    receiptNumber = Math.floor(1000000000 + Math.random() * 9000000000).toString();
                } while (await checkReceiptNumberExists(receiptNumber));
            } else {
                // Check if the provided receipt number already exists
                if (await checkReceiptNumberExists(receiptNumber)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Receipt Number',
                        text: 'The entered receipt number already exists. Please enter a different one.',
                    });
                    return;
                }
            }

            Swal.fire({
                title: 'Finalize Transaction?',
                text: 'Are you sure you want to finalize the transaction?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('About to send AJAX request');
                    // Get other necessary data
                    const change = paymentAmount - updatedTotalAmount;
                    const existingStudentNumber = document.getElementById('existingStudentNumber').value;
                    const advisoryTeacher = document.getElementById('advisoryTeacher').value;
                    const existingCustomerName = document.getElementById('existingCustomerName').value;
            
                    // Get discounts from each discount input
                    const discountInputs = document.querySelectorAll('.discount-input');
                    const discounts = Array.from(discountInputs).map(input => parseFloat(input.value) || 0);
            
                    // Add discounts to the cartData array
                    const updatedCartData = cartData.map((item, index) => ({
                        ...item,
                        discount: discounts[index], // Assuming discounts is an array of discount values
                        subtotal: calculateDiscountedSubtotal(item.price, item.quantity, discounts[index]),
                        // Add other properties as needed
                    }));

                    // Function to calculate discounted subtotal
                    function calculateDiscountedSubtotal(price, quantity, discount) {
                        const discountedPrice = price * (1 - discount / 100);
                        return quantity * discountedPrice;
                    }
    
                    // Send AJAX request
                    $.ajax({
                        // AJAX configuration options...
                        success: function (response) {
                            console.log('Success:', response);
                
                            // Store data in session
                            $.ajax({
                                url: 'process_receipt.php',
                                method: 'POST',
                                data: {
                                    receiptNumber: receiptNumber,
                                    existingCustomerName: existingCustomerName,
                                    existingStudentNumber: existingStudentNumber,
                                    advisoryTeacher: advisoryTeacher,
                                    paymentAmount: paymentAmount,
                                    totalAmount: updatedTotalAmount, // Use the updated total amount
                                    change: change,
                                    cartData: JSON.stringify(updatedCartData),
                                },
                                success: function (storeResponse) {
                                    console.log('Data stored in session:', storeResponse);
                                    // Redirect to receipt.php or handle the response accordingly
                                    window.location.href = 'receipt.php';
                                },
                                error: function (storeError) {
                                    console.log('Error storing data in session:', storeError);
                                },
                            });
                        },
                        error: function (error) {
                            console.log('Error:', error);
                        },
                    });
                }
            });
        }
    }
    
    function calculateTotalWithDiscounts() {
        var total = 0;
        var subtotalCells = document.querySelectorAll('.subtotal');
    
        subtotalCells.forEach(function (subtotalCell) {
            total += parseFloat(subtotalCell.innerText.substring(1));
        });
    
        return total;
    }

    function checkReceiptNumberExists(receiptNumber) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'receipt_check.php',
                method: 'POST',
                data: {
                    receiptNumber: receiptNumber,
                },
                success: function (response) {
                    resolve(JSON.parse(response).exists);
                },
                error: function (error) {
                    console.log('Error checking receipt number:', error);
                    reject(error);
                },
            });
        });
    }

});

document.addEventListener("DOMContentLoaded", function () {

    const newStudentSection = document.getElementById("newStudentSection");
    const newStudentForm = document.getElementById('newStudentForm');

    var isStudentDropdown = document.getElementById("isStudent");
    var studentTypeDivDropdown = document.getElementById("studentTypeDiv");
    var customerTypeDivDropdown = document.getElementById("customerTypeDiv");
    var studentTypeDropdown = document.getElementById("studentType");
    var customerTypeDropdown = document.getElementById("customerType");
    var studentDataDisplay = document.getElementById("studentDataDisplay");
    var customerDataDisplay = document.getElementById("customerDataDisplay");
    var existingStudentSection = document.getElementById("existingStudentSection");
    var existingCustomerSection = document.getElementById("existingCustomerSection");
    var confirmTransactionButton = document.getElementById("confirmTransactionButton");

    function fetchStudentData(customer_id) {
        if (customer_id === 'na') {
            document.getElementById('studentCard').style.display = 'none';
            return;
        }
    
        // Make an AJAX request to fetch student data based on the selected student number
        fetch('customerpicker.php?customer_id=' + customer_id)
        .then(response => response.json())
        .then(customerData => {
            document.getElementById('studentCard').style.display = 'block';
            studentDataDisplay.innerHTML = `
                <div style="border-radius: 10px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
                    <h2 style="margin-bottom: 10px; font-size: 1.2em;">Student Information</h2>
                    <div style="display: flex; display:none; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Student ID:</div>
                        <div style="flex: 2;">${customerData.customer_id}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Student Number:</div>
                        <div style="flex: 2;">${customerData.student_number}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Name:</div>
                        <div style="flex: 2;">${customerData.first_name} ${customerData.last_name}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Email:</div>
                        <div style="flex: 2;">${customerData.email}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="flex: 1; font-weight: bold;">Phone Number:</div>
                        <div style="flex: 2;">${customerData.phone_number}</div>
                    </div>
                </div>
            `;
            checkIfBothSelected(); 
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function fetchCustomerData(customer_id) {
        if (customer_id === 'na') {
            document.getElementById('customerCard').style.display = 'none';
            return;
        }
    
        // Make an AJAX request to fetch student data based on the selected student number
        fetch('customerpicker.php?customer_id=' + customer_id)
        .then(response => response.json())
        .then(customerData => {
            document.getElementById('customerCard').style.display = 'block';
            customerDataDisplay.innerHTML = `
                <div style="border-radius: 10px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
                    <h2 style="margin-bottom: 10px; font-size: 1.2em;">Customer Information</h2>
                    <div style="display: flex; display:none; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Student ID:</div>
                        <div style="flex: 2;">${customerData.customer_id}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Name:</div>
                        <div style="flex: 2;">${customerData.first_name} ${customerData.last_name}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <div style="flex: 1; font-weight: bold;">Email:</div>
                        <div style="flex: 2;">${customerData.email}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="flex: 1; font-weight: bold;">Phone Number:</div>
                        <div style="flex: 2;">${customerData.phone_number}</div>
                    </div>
                </div>
            `;
            checkIfCustomerSelected()
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    isStudentDropdown.addEventListener("change", function () {
        if (isStudentDropdown.value === "student") {
            customerTypeDivDropdown.style.display = "none";
            studentTypeDivDropdown.style.display = "block";
            newCustomerSection.style.display = "none";
            existingCustomerSection.style.display = "none";
            document.getElementById('customerCard').style.display = 'none';
            confirmTransactionButton.style.display = "none";
            customerTypeDropdown.value = "na";

            studentTypeDropdown.addEventListener("change", function () {
                if (studentTypeDropdown.value === "existing") {

                    existingStudentSection.style.display = "block";
                    studentDataDisplay.innerHTML = '';
                    newStudentSection.style.display = "none";

                } else if (studentTypeDropdown.value === "new") {

                    newStudentSection.style.display = "block";
                    document.getElementById('studentCard').style.display = 'none';
                    existingStudentSection.style.display = "none";
                    studentDataDisplay.innerHTML = ''; 

                }
                    var advisoryTeacherSelect = document.getElementById("advisoryTeacher");
                    var existingStudentDropdown = document.getElementById("existingStudentNumber");
                    advisoryTeacherSelect.value="na";
                    existingStudentDropdown.value = "na";
                    confirmTransactionButton.style.display = "none";
            });

            } else if (isStudentDropdown.value === "non-student"){
                customerTypeDivDropdown.style.display = "block";
                studentTypeDivDropdown.style.display = "none";
                newStudentSection.style.display = "none";
                existingStudentSection.style.display = "none";
                document.getElementById('studentCard').style.display = 'none';
                confirmTransactionButton.style.display = "none";
                studentTypeDropdown.value = "na";

                customerTypeDropdown.addEventListener("change", function () {
                    if (customerTypeDropdown.value === "existings") {
                        // Display existing customer section
                        existingCustomerSection.style.display = "block";
                        customerDataDisplay.innerHTML = '';
                        // Hide new customer section
                        newCustomerSection.style.display = "none";
                    } else if (customerTypeDropdown.value === "news") {
                        // Display new customer section
                        newCustomerSection.style.display = "block";
                        document.getElementById('customerCard').style.display = 'none';
                        // Hide existing customer section
                        existingCustomerSection.style.display = "none";
                        customerDataDisplay.innerHTML = ''; 
    
                    }
                        var existingCustomerDropdown = document.getElementById("existingCustomerName");
                        advisoryTeacherSelect.value="na";
                        existingCustomerDropdown.value = "na";
                        confirmTransactionButton.style.display = "none";
                });



                
            }
        });

    // STUDENT REGISTRATION

    newStudentForm.addEventListener('submit', function (e) {
        e.preventDefault();
    
        $.ajax({
            url: 'register_student.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Display success Swal with checkmark
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration successful!',
                        text: 'You can now use this student for checkout.',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm'
                    }).then((result) => {
                        // Check if the Confirm button is clicked
                        if (result.isConfirmed) {
                            // Reload the page
                            location.reload(true);
                        }
                    });
    
                    // Reset the form
                    newStudentForm.reset();
                } else {
                    // Display SweetAlert for registration failure (optional)
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration failed',
                        text: response.message
                    });
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    });

        // CUSTOMER REGISTRATION

        newCustomerForm.addEventListener('submit', function (e) {
            e.preventDefault();
        
            $.ajax({
                url: 'register_customer.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log('Success:', response);
                    if (response.success) {
                        // Display success Swal with checkmark
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration successful!',
                            text: 'You can now use this customer for checkout.',
                            showCancelButton: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Confirm'
                        }).then((result) => {
                            // Check if the Confirm button is clicked
                            if (result.isConfirmed) {
                                // Reload the page
                                location.reload(true);
                            }
                        });
        
                        // Reset the form
                        newCustomerForm.reset();
                    } else {
                        // Display SweetAlert for registration failure (optional)
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration failed',
                            text: response.message
                        });
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        });

    var existingStudentDropdown = document.getElementById("existingStudentNumber");

    existingStudentDropdown.addEventListener("change", function () {
        var selectedStudentNumber = existingStudentDropdown.value;
        if (selectedStudentNumber !== 'na') {
            fetchStudentData(selectedStudentNumber);
        } else {
            studentDataDisplay.innerHTML = '';
        }
    });

    var existingCustomerDropdown = document.getElementById("existingCustomerName");

    existingCustomerDropdown.addEventListener("change", function () {
        var selectedCustomerNumber = existingCustomerDropdown.value;
        if (selectedCustomerNumber !== 'na') {
            fetchCustomerData(selectedCustomerNumber);
        } else {
            customerDataDisplay.innerHTML = '';
        }
    });

    var advisoryTeacherSelect = document.getElementById("advisoryTeacher");

    advisoryTeacherSelect.addEventListener("change", checkIfBothSelected);

    function checkIfBothSelected() {
        if (existingStudentDropdown.value !== 'na' && advisoryTeacherSelect.value !== 'na') {
            confirmTransactionButton.style.display = 'block';
        } else {
            confirmTransactionButton.style.display = 'none';
        }
    }

    function checkIfCustomerSelected() {

        var existingCustomerDropdown = document.getElementById("existingCustomerName");

        if (existingCustomerDropdown.value !== 'na') {
            confirmTransactionButton.style.display = 'block';
        } else {
            confirmTransactionButton.style.display = 'none';
        }
    }

});

        // PREVIEW OF LARGE IMAGE
        document.addEventListener('DOMContentLoaded', function () {
            const previewImages = document.querySelectorAll('.preview-img');
            const enlargedImg = document.getElementById('enlargedImg');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    
            previewImages.forEach(img => {
                img.addEventListener('click', function () {
                    enlargedImg.src = this.src;
                    imageModal.show();
                });
            });
    
            imageModal._element.addEventListener('hidden.bs.modal', function () {
                enlargedImg.src = ''; // Clear the image source when the modal is closed
            });
    
            // jQuery script
            $(document).ready(function () {
                // Assuming you have a click event to open the modal
                $('.preview-img').click(function () {
                    var imageUrl = $(this).attr('src');
                    $('#enlargedImg').attr('src', imageUrl);
                    $('#imageModal').modal('show');
                });
            });
        });

// Function to validate the discount input dynamically
function validateDiscount(input) {
    // Remove non-numeric characters
    let value = input.value.replace(/[^0-9]/g, '');

    // Ensure the value is within the range of 0-100
    value = Math.min(100, Math.max(0, parseInt(value)));

    // Update the input value
    input.value = value;

    // Trigger the discounted price update
    updateDiscountedPrice(input);
}

// Function to update the discounted price based on the discount input
function updateDiscountedPrice(input) {
    // Ensure the discount input is a valid number
    let discountInput = parseInt(input.value);

    // Ensure the discount is a number and limit it to a maximum of 100 and a minimum of 0
    if (isNaN(discountInput) || discountInput < 0 || discountInput > 100) {
        // Set a default value of 0 if the input is invalid
        discountInput = 0;
    }

    // Limit the discount input to at most 3 digits
    discountInput = Math.min(100, discountInput);

    // Update the input value
    input.value = discountInput;

    var row = input.closest('tr');
    var price = parseFloat(row.cells[3].innerText.substring(1)); // Extract numeric price
    var quantity = parseInt(row.cells[2].innerText); // Extract quantity
    var subtotalCell = row.cells[5];

    // Calculate discounted subtotal for all quantities
    var discountedSubtotal = quantity * price * (1 - discountInput / 100);
    subtotalCell.innerText = '₱' + discountedSubtotal.toFixed(2);

    // Update total
    updateTotal();
}

async function updateTotal() {
    var total = 0;
    var subtotalCells = document.querySelectorAll('.subtotal');

    // Check if subtotalCells is a valid NodeList and has a length
    if (subtotalCells && subtotalCells.length) {
        await Promise.all(Array.from(subtotalCells).map(async function (subtotalCell) {
            total += parseFloat(subtotalCell.innerText.substring(1));
        }));
    }

    // Display the updated total
    document.querySelector('.total').innerText = '₱' + total.toFixed(2);
    totalAmount = total; // Update the global variable
}

function filterOptions() {
    // Get input value and convert to lowercase for case-insensitive matching
    var input = document.getElementById('searchInput').value.toLowerCase();
    var select = document.getElementById('existingStudentNumber');
    
    // Loop through each option in the select element
    for (var i = 0; i < select.options.length; i++) {
        // Get the text content of the option, convert to lowercase
        var optionText = select.options[i].text.toLowerCase();
        
        // Check if the input is found in the option text
        if (optionText.includes(input)) {
            // If found, display the option
            select.options[i].style.display = '';
        } else {
            // If not found, hide the option
            select.options[i].style.display = 'none';
        }
    }
}

function filterTeacherOptions() {
    const input = document.getElementById('teacherSearchInput');
    const select = document.getElementById('advisoryTeacher');
    const filter = input.value.toLowerCase();

    for (let i = 0; i < select.options.length; i++) {
        const option = select.options[i];
        const text = option.text.toLowerCase();
        const match = text.includes(filter);
        option.style.display = match ? 'block' : 'none';
    }
}

function filterCustomerOptions() {
    const input = document.getElementById('customerNameSearchInput').value.toUpperCase();
    const select = document.getElementById('existingCustomerName');
    const options = select.getElementsByTagName('option');

    for (let i = 0; i < options.length; i++) {
        const textValue = options[i].text.toUpperCase();
        if (textValue.indexOf(input) > -1) {
            options[i].style.display = '';
        } else {
            options[i].style.display = 'none';
        }
    }
}