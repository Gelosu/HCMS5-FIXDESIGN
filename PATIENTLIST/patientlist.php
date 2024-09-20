

<div id="content">
<section id="patient-list" class="section">
    <h2>Patient List</h2>

    <div class="search-and-add-container">
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="searchTable3(this.value);" placeholder="Search for patients...">
        </div>

        <div class="filter-container">
            <select id="categoryDropdown" onchange="filterByCategory(this.value)">
                <option value="">All</option> 
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select>
        </div>

        <div class="add-button-container">
            <button onclick="openAddPatientModal()">Add Patient</button>
        </div>
    </div>

    <div class="table-container">
        <table id="patientTable">
            <thead>
                <tr>
                    <th>Name<div class="resizer3"></div></th>
                    <th>Age<div class="resizer3"></div></th>
                    <th>Birthday<div class="resizer3"></div></th>
                    <th>Address<div class="resizer3"></div></th>
                    <th>Contact Number<div class="resizer3"></div></th>
                    <th>Contact Person<div class="resizer3"></div></th>
                    <th>Contact Person Number<div class="resizer3"></div></th>
                    <th>Type<div class="resizer3"></div></th>
                    <th>Actions<div class="resizer3"></div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connect.php';

                // Fetch patients from the database
                $sql = "SELECT * FROM patient";
                $result = $conn->query($sql);

                // Output each patient as a table row
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $datetime = new DateTime($row["p_bday"]);
                        $formattedDate = $datetime->format('F j, Y');
                        echo "<tr>
                                <td>" . $row["p_name"] . "</td>
                                <td>" . $row["p_age"] . "</td>
                                <td>" . $formattedDate . "</td>
                                <td>" . $row["p_address"] . "</td>
                                <td>" . $row["p_contnum"] . "</td>
                                <td>" . $row["p_contper"] . "</td>
                                <td>" . $row["p_contnumper"] . "</td>
                                <td>" . $row["p_type"] . "</td>
                                <td>
                                    <a href='#' class='edit-btn' onclick='openEditPatient(" . $row["p_id"] . ", \"" . addslashes($row["p_name"]) . "\", \"" . $row["p_age"] . "\", \"" . $row["p_bday"] . "\", \"" . addslashes($row["p_address"]) . "\", \"" . addslashes($row["p_contnum"]) . "\", \"" . addslashes($row["p_contper"]) . "\", \"" . addslashes($row["p_contnumper"]) . "\", \"" . addslashes($row["p_type"]) . "\")'><img src='edit_icon.png' alt='Edit' style='width: 20px; height: 20px;'></a>
                                    <a href='#' class='delete-btn' onclick='deletePatient(" . $row["p_id"] . ", \"" . addslashes($row["p_name"]) . "\", \"" . $row["p_age"] . "\", \"" . $row["p_bday"] . "\", \"" . addslashes($row["p_address"]) . "\", \"" . addslashes($row["p_contnum"]) . "\", \"" . addslashes($row["p_contper"]) . "\", \"" . addslashes($row["p_contnumper"]) . "\", \"" . addslashes($row["p_type"]) . "\")'><img src='delete_icon.png' alt='Delete' style='width: 20px; height: 20px;'></a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No patients found</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- MODALS SECTION --> 

<!-- Add Patient Modal -->
<div id="addPatientModal" class="modal3">
    <div class="modal-content3">
        <span class="close" onclick="closeAddPatientModal()">&times;</span>
        <h3>Add New Patient</h3>
        <form id="addpatient" onsubmit="submitPatientForm(event)">
            <label for="p_name">Name:</label>
            <input type="text" id="p_name" name="p_name" placeholder="SURNAME, FN, MN" required><br><br>
            
            <label for="p_age">Age:</label>
            <input type="number" id="p_age" name="p_age" required><br><br>
            
            <label for="p_bday">Birthday:</label>
            <input type="date" id="p_bday" name="p_bday" required><br><br>
            
            <label for="p_address">Address:</label>
            <input type="text" id="p_address" name="p_address" required><br><br>
            
            <label for="p_contnum">Contact Number:</label>
<input type="text" id="p_contnum" name="p_contnum" required><br><br>
            
            <label for="p_contper">Contact Person:</label>
            <input type="text" id="p_contper" name="p_contper" required><br><br>
            
            <label for="p_contnumper">Contact Person Number:</label>
<input type="text" id="p_contnumper" name="p_contnumper" required><br><br>
            
            <label for="p_type">Patient Type:</label>
            <select id="p_type" name="p_type" required>
                <option value="">Select Patient Type</option>
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

<!-- Edit Patient Modal -->
<div id="editPatientModal" class="modal3">
    <div class="modal-content3">
        <span class="close" onclick="closeEditPatientModal()">&times;</span>
        <h3>Edit Patient</h3>
        <form id="editPatientForm" onsubmit="submitEditPatientForm(event)">
            <input type="hidden" id="editPatientId" name="patientId">
            
            <label for="editName">Name:</label>
            <input type="text" id="editName" name="name" required><br><br>
            
            <label for="editAge">Age:</label>
            <input type="number" id="editAge" name="age" required><br><br>
            
            <label for="editBirthday">Birthday:</label>
            <input type="date" id="editBirthday" name="birthday" required><br><br>
            
            <label for="editAddress">Address:</label>
            <input type="text" id="editAddress" name="address" required><br><br>
            
            <label for="editContactNumber">Contact Number:</label>
            <input type="text" id="editContactNumber" name="contactNumber" required><br><br>
            
            <label for="editContactPerson">Contact Person:</label>
            <input type="text" id="editContactPerson" name="contactPerson" required><br><br>
            
            <label for="editContactPersonNumber">Contact Person Number:</label>
            <input type="text" id="editContactPersonNumber" name="contactPersonNumber" required><br><br>
            
            <label for="editType">Patient Type:</label>
            <select id="editType" name="type" required>
                <option value="">Select Patient Type</option>
                <option value="Pedia">Pedia</option>
                <option value="Senior">Senior</option>
                <option value="PWD">PWD</option>
                <option value="OPD">OPD</option>
            </select><br><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>
