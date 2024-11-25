<?php include __DIR__ . '/../Header.php'; ?>

<div class="container">
    <a href="/manager/dashboard">Back</a>
    <!-- User Information Table -->
    <h3>User Details</h3>
    <table>
        <thead>
        <tr>
            <th>Employee Code</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= htmlspecialchars($user['employee_code']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <button class="create-user-btn" onclick="openModal()">Edit</button>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- Vacations Table -->
    <h3>Vacation Requests</h3>
    <?php if (!empty($vacations)): ?>
        <table>
            <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($vacations as $vacation): ?>
                <tr>
                    <td><?= htmlspecialchars($vacation['start_date']) ?></td>
                    <td><?= htmlspecialchars($vacation['end_date']) ?></td>
                    <td><?= htmlspecialchars($vacation['reason']) ?></td>
                    <td><?= htmlspecialchars($vacation['status']) ?></td>
                    <td>
                        <?php if ($vacation['status'] === 'pending'): ?>
                            <!-- Approve Button -->
                            <form method="POST" action="/manager/updateVacationStatus" style="display:inline;">
                                <input type="hidden" name="vacation_id" value="<?= htmlspecialchars($vacation['id']) ?>" />
                                <input type="hidden" name="status" value="approved" />
                                <button type="submit" class="approve-btn">Approve</button>
                            </form>

                            <!-- Reject Button -->
                            <form method="POST" action="/manager/updateVacationStatus" style="display:inline;">
                                <input type="hidden" name="vacation_id" value="<?= htmlspecialchars($vacation['id']) ?>" />
                                <input type="hidden" name="status" value="rejected" />
                                <button type="submit" class="reject-btn">Reject</button>
                            </form>
                        <?php else: ?>
                            <!-- No Actions for Approved/Rejected -->
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


<div id="edit-user-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit User</h2>
        <form id="edit-user-form" method="POST" action="/manager/editUser">
            <input type="hidden" name="employee_code" value="<?= $user['employee_code'] ?>" />

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave empty to keep current password">

            <label for="role">Role:</label>
            <select id="role" name="role" disabled required>
                <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('edit-user-modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('edit-user-modal').style.display = 'none';
    }

    // Optional: Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('edit-user-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
</script>