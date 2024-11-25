<?php include __DIR__ . '/../Header.php'; ?>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
    <!-- Vacations Tab -->
    <h3>My Vacation Requests</h3>
    <button class="create-user-btn" onclick="openCreateModal()">+ New Request</button>
    <?php if (!empty($data['vacation_requests'])): ?>
        <table>
            <thead>
            <tr>
                <th>Submitted At</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['vacation_requests'] as $vacation): ?>
                    <td><?= htmlspecialchars($vacation['updated_at']) ?></td>
                    <td><?= htmlspecialchars($vacation['start_date']) ?></td>
                    <td><?= htmlspecialchars($vacation['end_date']) ?></td>
                    <td><?= htmlspecialchars($vacation['reason']) ?></td>
                    <td><?= htmlspecialchars($vacation['status']) ?></td>
                    <td>
                        <?php if ($vacation['status'] === 'pending'): ?>
                            <button class="create-user-btn" onclick="openEditModal(<?=htmlspecialchars(json_encode($vacation))?>)">Edit</button>

                            <form method="POST" action="/employee/deleteVacationRequest" style="display:inline;">
                                <input type="hidden" name="vacation_id" value="<?=htmlspecialchars($vacation['id']) ?>" />
                                <button type="submit" class="delete-user-btn" onclick="return confirm('Are you sure you want to delete this vacation request?')">Delete</button>
                            </form>
                        <?php else: ?>
                            <span>N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No vacation requests found.</p>
    <?php endif; ?>
</div>

<!-- Update Vacation Request Modal -->
<div id="update-request-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Update Vacation Request</h2>
        <form id="update-vacation-form" method="POST" action="/employee/updateVacationRequest">
            <input type="hidden" id="vacation_id" name="vacation_id">

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="reason">Reason:</label>
            <textarea id="reason" name="reason" placeholder="Enter reason for vacation request" required></textarea>

            <button type="submit">Update</button>
        </form>
    </div>
</div>

<!-- Create Vacation Request Modal -->
<div id="create-request-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Create Vacation Request</h2>
        <form id="create-vacation-form" method="POST" action="/employee/createVacationRequest">
            <label for="create_start_date">Start Date:</label>
            <input type="date" id="create_start_date" name="start_date" required>

            <label for="create_end_date">End Date:</label>
            <input type="date" id="create_end_date" name="end_date" required>

            <label for="create_reason">Reason:</label>
            <textarea id="create_reason" name="reason" placeholder="Enter reason for vacation request" required></textarea>

            <button type="submit">Create</button>
        </form>
    </div>
</div>

<script>
    // Function to dynamically set the allowed date range
    // Function to dynamically set the allowed date range
    function setModalDateRange(startDateFieldId, endDateFieldId, minDate, maxDate) {
        const startDateField = document.getElementById(startDateFieldId);
        const endDateField = document.getElementById(endDateFieldId);

        // Set the min and max attributes for the start date field
        startDateField.min = minDate;
        startDateField.max = maxDate;

        // Set the min and max attributes for the end date field
        endDateField.min = minDate;
        endDateField.max = maxDate;

        // Add event listeners to synchronize date dependencies
        startDateField.addEventListener('change', () => {
            endDateField.min = startDateField.value; // Ensure end date is after or equal to start date
        });

        endDateField.addEventListener('change', () => {
            startDateField.max = endDateField.value; // Ensure start date is before or equal to end date
        });
    }

    // Open the Edit Modal
    function openEditModal(vacation) {
        // Populate modal fields with vacation data
        document.getElementById('vacation_id').value = vacation.id;
        document.getElementById('start_date').value = vacation.start_date;
        document.getElementById('end_date').value = vacation.end_date;
        document.getElementById('reason').value = vacation.reason;

        // Set the allowed date range dynamically for editing
        // Use today's date and 3 months ahead as min/max range for both fields
        const minDate = new Date().toISOString().split('T')[0]; // Today's date
        const maxDate = new Date(new Date().setMonth(new Date().getMonth() + 3)) // Three months ahead
            .toISOString()
            .split('T')[0];

        setModalDateRange(
            'start_date',
            'end_date',
            minDate, // Min range set to today's date
            maxDate // Max range set to 3 months ahead
        );

        // Open the modal
        document.getElementById('update-request-modal').style.display = 'block';
    }

    // Open the Create Modal
    function openCreateModal() {
        // Reset modal fields for a new vacation request
        document.getElementById('create_start_date').value = '';
        document.getElementById('create_end_date').value = '';
        document.getElementById('create_reason').value = '';

        // Set a default allowed date range for the Create Modal (today to 3 months ahead)
        const minDate = new Date().toISOString().split('T')[0]; // Today's date
        const maxDate = new Date(new Date().setMonth(new Date().getMonth() + 3)) // Three months ahead
            .toISOString()
            .split('T')[0];

        setModalDateRange('create_start_date', 'create_end_date', minDate, maxDate);

        // Open the "Create Vacation Request" modal
        document.getElementById('create-request-modal').style.display = 'block';
    }



    function closeModal() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
    }

    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };
</script>

