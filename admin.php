<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['adusername'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user info from session
$adfirstname = $_SESSION['adfirstname'];
$adsurname = $_SESSION['adsurname'];
$healthWorker = $_SESSION['adfirstname'] . ' ' . $_SESSION['adsurname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STA. MARIA HCMS</title>
    <link rel="stylesheet" href="mamamoadmin.css">
    <link rel="stylesheet" href="style3.css">
   
</head>
<body>

<header>
    <h1>BRGY STA. MARIA HEALTH CENTER</h1>
</header>

<!-- SIDE BAR -->
<div id="sidebar">
    <div id="logo">
        <img src="mary.jpg" alt="Logo">
    </div>
    
    <p class="healthworker-info">
    <span class="healthworker-label">HEALTH WORKER:</span>
    <span class="healthworker-name"><?php echo htmlspecialchars($adfirstname . ' ' . $adsurname); ?></span>
</p>

    
    <ul>
    <li><a href="#" onclick="setActiveSection('dashboard')">Dashboard</a></li>
        <li><a href="#" onclick="setActiveSection('medical_supplies-inventory')">Medical & Emergency Supplies Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('medicine-inventory')">Medicine Inventory</a></li>
        <li><a href="#" onclick="setActiveSection('patient-list')">Patient List</a></li>
        <li><a href="#" onclick="setActiveSection('patient-appointment')">Patient Appointment</a></li>
        <li><a href="#" onclick="setActiveSection('patient-med')">Patient - Medication</a></li>
        
       
    </ul>
    <button id="logoutBtn">Logout</button>
</div>


<div id="content">
<section id="dashboard" class="section">
    <h2>DASHBOARD</h2>
    
    <div class="card-container">
        <div class="card">
            <h3>Total Registered Patients</h3>
            <p id="total-patients">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medicines</h3>
            <p id="total-medicines">Loading...</p>
        </div>
        <div class="card">
            <h3>Appointments Today</h3>
            <p id="appointments-today">Loading...</p>
        </div>
        <div class="card">
            <h3>Total Medical Supplies</h3>
            <p id="total-medications">Loading...</p>
        </div>
    </div>
  </section>

<?php
        
        include 'PATIENTLIST/patientlist.php';
        include 'MEDICAL_SUPPLY/medicalsupply.php';
        include 'MEDICINES/medicinelist.php';
        include 'APPOINTMENT/appointmentlist.php';
        include 'P_MEDICATION/pmedication.php';
?>




</div>
<script>const healthWorkerName = "<?php echo htmlspecialchars($healthWorker); ?>";</script>

<script src="functionforMEDSUPP.js"></script>
<script src="functionforMEDICINE.js"></script>
<script src="functionforPATIENTLIST.js"></script>
<script src="functionforMEDICATION.js"></script>
<script src="SEARCH_FILTER.js"> </script>
<script> 



  // Function to set and activate the desired section based on navigation clicks
function setActiveSection(sectionId) {
    window.location.hash = sectionId;  // Set URL hash
    toggleSection(sectionId);  // Show the selected section
}

// Function to toggle visibility of sections
function toggleSection(sectionId) {
    var sections = document.querySelectorAll('.section');
    sections.forEach(function(section) {
        if (section.id === sectionId) {
            section.style.display = 'block';  // Show the selected section
        } else {
            section.style.display = 'none';  // Hide other sections
        }
    });
}

// Logout
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("logoutBtn").addEventListener("click", function() {
        window.location.href = "logout.php";
    });
});

// When the page loads, show the appropriate section based on the URL hash
window.onload = function() {
    var hash = window.location.hash.substring(1);  // Get hash value
    if (hash) {
        // Show the section corresponding to the hash
        toggleSection(hash);
    } else {
        // Default to 'dashboard' section if no hash is present
        setActiveSection('dashboard');
    }
};


//DASHBOARD
function updateDashboard() {
    fetch('DASHBOARD/get_dashboard_counts.php')
    .then(response => response.text())  // Fetch the raw text response
    .then(text => {
        console.log('Raw Response Text:', text);  // Log the raw text for debugging
        
        // Parse the raw text into a JSON object
        const data = JSON.parse(text);
        
        // Update the counts on the dashboard
        document.getElementById('total-patients').textContent = data.total_patients || 0;
        document.getElementById('total-medicines').textContent = data.total_meds || 0;
        document.getElementById('appointments-today').textContent = data.total_appointments || 0;
        document.getElementById('total-medications').textContent = data.total_medications || 0;
    })
    .catch(error => {
        console.error('Error fetching dashboard data:', error);
        // Fallback to '0' in case of error
        document.getElementById('total-patients').textContent = 0;
        document.getElementById('total-medicines').textContent = 0;
        document.getElementById('appointments-today').textContent = 0;
        document.getElementById('total-medications').textContent = 0;
    });
}


// Call the function to update the dashboard on page load
updateDashboard();


//APPOINTMENT 

// Function to open the Add Appointment modal
function openAddAppointmentModal() {
    // Fetch patient names and populate dropdown
    fetch('APPOINTMENT/fetch_patient.php')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log("check result: ", result.data);
                populatePatientDropdown2(result.data);
                // Set the health worker's name
                document.getElementById('healthWorker').value = healthWorkerName;
                document.getElementById('addAppointmentModal').style.display = 'block';
            } else {
                alert('Error fetching patients: ' + result.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to populate the patient dropdown
function populatePatientDropdown2(patients) {
    var dropdown = document.getElementById('patientName');
    dropdown.innerHTML = ''; // Clear existing options

    var defaultOption = document.createElement('option');
    defaultOption.text = 'Select a patient';
    defaultOption.value = '';
    dropdown.add(defaultOption);

    // Iterate over the list of patient names
    patients.forEach(patient => {
        var option = document.createElement('option');
        option.text = patient; // Use the patient name directly
        option.value = patient; // Use the patient name directly as the value
        dropdown.add(option);
    });
}

// Function to close the Add Appointment modal
function closeAddAppointmentModal() {
    document.getElementById('addAppointmentModal').style.display = 'none';
}

// Function to handle form submission for adding an appointment
function submitAddAppointmentForm(event) {
    event.preventDefault();

    // Append health worker to form data
    var formData = new FormData(document.getElementById('addAppointmentForm'));
    formData.append('healthWorker', healthWorkerName);

    fetch('APPOINTMENT/add_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeAddAppointmentModal();
            updateAppointmentTable(result.data); // Refresh the table with the updated data
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to format date and time
function formatDateTime(datetime) {
    const date = new Date(datetime);
    // Format date to "Month Day, Year Time AM/PM"
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };
    return date.toLocaleString('en-US', options);
}

// Function to update the appointment table
function updateAppointmentTable(appointments) {
    var tableBody = document.querySelector('#patient-appointment table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    appointments.forEach(appointment => {
        // Format datetime before displaying
        const formattedDateTime = formatDateTime(appointment.datetime);

        var row = document.createElement('tr');
        row.innerHTML = `
            <td>${appointment.p_name}</td>
            <td>${appointment.p_purpose}</td>
            <td>${formattedDateTime}</td>
            <td>${appointment.a_healthworker}</td>
            <td>
                <a href='#' class='edit-btn' onclick="openEditAppointmentModal(
                    '${appointment.id}',
                    '${appointment.p_name}',
                    '${appointment.p_purpose}',
                    '${appointment.datetime}',
                    '${appointment.a_healthworker}'
                )">
                    <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                </a>
                <a href='#' class='delete-btn' onclick="deleteAppointment('${appointment.id}')">
                    <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                </a>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Function to open the Edit Appointment modal
function openEditAppointmentModal(id, patientName, purpose, datetime, healthWorker) {
    console.log(id, patientName, purpose, datetime, healthWorker);

    document.getElementById('editAppointmentId').value = id;
    document.getElementById('editPatientName').value = patientName;
    document.getElementById('editPurpose').value = purpose;

    // Convert datetime to 'YYYY-MM-DDTHH:MM' format
    const formattedDateTime = new Date(datetime).toISOString().slice(0, 16);
    document.getElementById('editAppointmentDateTime').value = formattedDateTime;

    document.getElementById('editHealthWorker').value = healthWorker;
    document.getElementById('editAppointmentModal').style.display = 'block';
}

// Function to close the modal
function closeEditAppointmentModal() {
    document.getElementById('editAppointmentModal').style.display = 'none';
}

// Function to handle the form submission for editing an appointment
function submitEditAppointmentForm(event) {
    event.preventDefault(); // Prevent the default form submission

    console.log("Submitting form...");

    const id = document.getElementById('editAppointmentId').value;
    const patientName = document.getElementById('editPatientName').value;
    const purpose = document.getElementById('editPurpose').value;
    const appointmentDateTime = document.getElementById('editAppointmentDateTime').value;
    const healthWorker = document.getElementById('editHealthWorker').value;

    console.log("Form data:", { id, patientName, purpose, appointmentDateTime, healthWorker });

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `/HCMS/APPOINTMENT/edit_appointment.php?editAppointmentId=${encodeURIComponent(id)}&editPatientName=${encodeURIComponent(patientName)}&editPurpose=${encodeURIComponent(purpose)}&editAppointmentDateTime=${encodeURIComponent(appointmentDateTime)}&editHealthWorker=${encodeURIComponent(healthWorker)}`, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log("Response:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                updateAppointmentTable(response.appointments);
                alert(response.message);
                document.getElementById('editAppointmentModal').style.display = 'none';
            } else {
                alert(response.message);
            }
        } else {
            alert('Error: ' + xhr.statusText);
        }
    };

    xhr.onerror = function() {
        alert('Request failed.');
    };

    xhr.send();
}

// Delete function
function deleteAppointment(appointmentId) {
    if (confirm('Are you sure you want to delete this appointment?')) {
        fetch('APPOINTMENT/delete_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'appointmentId': appointmentId
            })
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Unexpected content type: ' + contentType);
            }
        })
        .then(data => {
            if (data.success) {
                updateAppointmentTable(data.appointments); // Refresh the table
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error deleting record:', error));
    }
}



</script>
<footer>
    <p>&copy; 2024 BRGY STA. MARIA HEALTH CENTER. All rights reserved.</p>
</footer>

</body>
</html>