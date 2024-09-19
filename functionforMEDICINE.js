var addMedicineModal = document.getElementById("addMedicineModal"); //ADD MEDICINE
    var editMedicineModal =document.getElementById("editMedicineModal") //EDIT MEDICINE

    //MEDICINE

    
    // FUNCTION FOR ADDING MEDICINE
    function submitMedicineForm(event) {
        event.preventDefault(); // Prevent the default form submission behavior
    
        // Get the form data from the form with ID 'addmedicine'
        var formData = new FormData(document.getElementById('addmedicine'));
    
        // Send a POST request to the 'add_meds.php' endpoint with the form data
        fetch('MEDICINES/add_meds.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Ensure the response is in JSON format
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            console.log('Success:', data);
    
            // Check if there is an error in the response
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                // Update the medicine table with the new data
                updateMedicineTable(data.data);
                
                // Close the modal form after successful submission
                closeAddMedicineModal();
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            alert('Error submitting form: ' + error.message); // Provide feedback to the user
        });
    }
    
    function updateMedicineTable(medicines) {
        var tableBody = document.querySelector('#medTable tbody');
        tableBody.innerHTML = ''; // Clear existing table rows
    
        if (medicines.length > 0) {
            medicines.forEach(med => {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${med.meds_number}</td>
                    <td>${med.meds_name}</td>
                    <td>${med.med_dscrptn}</td>
                    <td>${med.stock_in}</td>
                    <td>${med.stock_out}</td>
                    <td>${med.stock_exp}</td>
                    <td>${med.stock_avail}</td>
                    <td class='action-icons'>
                        <a href='#' class='edit-btn' onclick="openEditMed(
                            '${med.med_id}', 
                            '${med.meds_number}', 
                            '${med.meds_name}', 
                            '${med.med_dscrptn}', 
                            ${med.stock_in}, 
                            ${med.stock_out}, 
                            '${med.stock_exp}', 
                            ${med.stock_avail}
                        )">
                            <img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'>
                        </a>
                        <a href='#' class='delete-btn' onclick="deleteMedicine(${med.med_id})">
                            <img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'>
                        </a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="8">No medicines found</td></tr>'; // Adjust colspan to match the number of columns
        }
    }
    



function closeAddMedicineModal() {
    if (addMedicineModal) {
        addMedicineModal.style.display = 'none';
    }
}

function openAddMedicineModal() {
    if (addMedicineModal) {
        addMedicineModal.style.display = 'block'; 
    }
}

//Update MEds
function openEditMed(medId, medName, medDesc, stockIn, stockOut, stockExpired, stockAvailable) {
    document.getElementById('editMedId').value = medId;
    document.getElementById('editMedName').value = medName;
    document.getElementById('editMedDesc').value = medDesc;
    document.getElementById('editStockIn').value = stockIn;
    document.getElementById('editStockOut').value = stockOut;
    document.getElementById('editStockExp').value = stockExpired;
    document.getElementById('editStockAvail').value = stockAvailable;

    document.getElementById('editMedicineModal').style.display = 'block';
}

// Function to close the edit medicine modal
function closeEditMedModal() {
    var modal = document.getElementById("editMedicineModal");
    if (modal) {
        modal.style.display = 'none';
    }
}

// Function to submit the edit form data asynchronously
function submitEditMedicineForm(event) {
    event.preventDefault();  // Prevent form from reloading the page

    var formData = new FormData(document.getElementById('editForm2'));  

    fetch('MEDICINES/update_meds.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
        console.log('Success:', data);
        if (data.error) {
            alert('Error: ' + data.error);  
        } else {
            // Update table with the correct data field
            updateMedicineTable(data.medicines); 
            closeEditMedModal();  
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}

document.getElementById('editForm2').addEventListener('submit', submitEditMedicineForm);




// Function to handle delete medicine
function deleteMedicine(medId) {
    if (confirm('Are you sure you want to delete this item?')) {
        fetch('MEDICINES/delete_meds.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'medId': medId
            })
        })
        .then(response => {
            // Check if the response is in JSON format
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Unexpected content type: ' + contentType);
            }
        })
        .then(data => {
            if (data.success) {
                updateMedicineTable(data.medicines); 
                document.querySelector(`#medRow${medId}`).remove(); 
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error deleting record:', error));
    }
}


document.querySelectorAll('#medTable th .resizer2').forEach(resizer => {
    let startX, startWidth;

    resizer.addEventListener('mousedown', e => {
        startX = e.clientX;
        startWidth = resizer.parentElement.offsetWidth;
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', () => {
            document.removeEventListener('mousemove', handleMouseMove);
        });
    });

    function handleMouseMove(e) {
        const newWidth = startWidth + (e.clientX - startX);
        resizer.parentElement.style.width = `${newWidth}px`;
        const index = Array.from(resizer.parentElement.parentElement.children).indexOf(resizer.parentElement);
        Array.from(resizer.parentElement.parentElement.parentElement.querySelectorAll('tbody tr')).forEach(row => {
            row.children[index].style.width = `${newWidth}px`;
        });
    }
});
