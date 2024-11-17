<?php
include_once 'admin_dashboard/admin_dashboard.js';
include_once 'includes.php';
?>
<nav>
    <div style="display: flex; align-items: center; margin-left: auto;">
        <div id="pst" class="pst" style="font-size: 18px; margin-right: 10px; color: orange;"></div>
        <!-- <input type="checkbox" id="switch-mode" hidden style="margin-right: 20px;">
        <label for="switch-mode" class="switch-mode" style="margin-right: 20px;"></label> -->

        <!-- <a href="#" class="notification" style="margin-right: 20px;">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a> -->

        <a href="#" class="profile">
            <img src="/finalcapstone/images/neustlogo.png">
        </a>
    </div>
</nav>

<script>
    // Displays the day, date and time for Philippines        
    function updateTime() {
        const pstElement = document.getElementById('pst');
        const now = new Date();
        const options = {
            timeZone: 'Asia/Manila',
            weekday: 'short',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const pstTime = now.toLocaleString('en-US', options);
        pstElement.textContent = "PST (Philippine Standard Time): " + pstTime;
    }

    updateTime();
    setInterval(updateTime, 1000);
    // Update the time initially and then every second


    document.addEventListener('DOMContentLoaded', async function() {
        // Fetch transaction data from the API
        const response = await fetch('/finalcapstone/admin_dashboard/transactions.php');
        const transactionsData = await response.json();

        // Extract intervals and total amounts from the data
        const groupedData = groupTransactionsByDate(transactionsData);
        const labels = Object.keys(groupedData);
        const amounts = labels.map(date => calculateTotalAmount(groupedData[date]));

        // Chart data
        const salesData = {
            labels: labels,
            datasets: [{
                label: 'Sales',
                data: amounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
            }]
        };

        // Get the canvas element
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Create and render the chart
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: salesData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return '₱' + value.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                return label + ': ₱' + value;
                            }
                        }
                    }
                }
            }
        });
    });

    // Function to group transactions by date
    function groupTransactionsByDate(transactions) {
        const groupedData = {};

        transactions.forEach(transaction => {
            const date = formatDate(new Date(transaction.transaction_date));

            if (!groupedData[date]) {
                groupedData[date] = [];
            }

            groupedData[date].push(transaction);
        });

        return groupedData;
    }

    // Function to calculate the total amount for an array of transactions
    function calculateTotalAmount(transactions) {
        return transactions.reduce((total, transaction) => total + parseFloat(transaction.total_amount), 0);
    }

    // Helper function to format dates as MM/DD/YY
    function formatDate(date) {
        const options = {
            month: 'numeric',
            day: 'numeric',
            year: '2-digit'
        };
        return date.toLocaleDateString(undefined, options);
    }
</script>