<?php include __DIR__ . '/../Header.php'; ?>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
    <h4>You have, <?= count($data['vacations_pending']) ?> pending vacation request(s).</h4>

    <!-- Tab Navigation -->
    <div class="tabs">
        <button class="tab-link active" onclick="openTab(event, 'users-tab')">Users</button>
        <button class="tab-link" onclick="openTab(event, 'vacations-tab')">Vacation Requests</button>
    </div>

    <!-- Users Tab -->
    <div id="users-tab" class="tab-content active">
        <div class="tab-header">
            <h3>Users</h3>
            <button class="create-user-btn" onclick="openModal()">Create User</button>

        </div>
        <?php if (!empty($data['users'])): ?>
            <table>
                <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Pending Vacations</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['employee_code']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['pending_vacations']) ?></td>
                        <td>
                            <form method="GET" action="/manager/showUser" style="display:inline;">
                                <input type="hidden" name="employee_code" value="<?= htmlspecialchars($user['employee_code']) ?>" />
                                <button type="submit" class="show-user-btn">Show</button>
                            </form>
                            <form method="POST" action="/manager/deleteUser" style="display:inline;">
                                <input type="hidden" name="employee_code" value="<?= htmlspecialchars($user['employee_code']) ?>" />
                                <button type="submit" class="delete-user-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>


    <!-- Vacations Tab -->
    <div id="vacations-tab" class="tab-content">
        <h3>Vacation Requests</h3>
        <?php if (!empty($data['vacations'])): ?>
            <table>
                <thead>
                <tr>
                    <th>User Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['vacations'] as $vacation): ?>
                    <tr>
                        <td><?= htmlspecialchars($vacation['user_name']) ?></td>
                        <td><?= htmlspecialchars($vacation['start_date']) ?></td>
                        <td><?= htmlspecialchars($vacation['end_date']) ?></td>
                        <td><?= htmlspecialchars($vacation['reason']) ?></td>
                        <td><?= htmlspecialchars($vacation['status']) ?></td>
                        <td>
                            <?php if ($vacation['status'] === 'pending'): ?>

                                <form method="POST" action="/manager/updateVacationStatus" style="display:inline;">
                                    <input type="hidden" name="vacation_id" value="<?= htmlspecialchars($vacation['id']) ?>" />
                                    <input type="hidden" name="status" value="approved" />
                                    <button type="submit" class="approve-btn">Approve</button>
                                </form>

                                <form method="POST" action="/manager/updateVacationStatus" style="display:inline;">
                                    <input type="hidden" name="vacation_id" value="<?= htmlspecialchars($vacation['id']) ?>" />
                                    <input type="hidden" name="status" value="rejected" />
                                    <button type="submit" class="reject-btn">Reject</button>
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
</div>


<div id="create-user-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Create New User</h2>
        <form id="create-user-form" method="POST" action="/manager/createUser">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>

            <label for="employee_code">Employee Code:</label>
            <input type="number" id="employee_code" name="employee_code" placeholder="Enter employee code"
                   min="1000000" max="9999999" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select role</option>
                <option value="manager">Manager</option>
                <option value="employee">Employee</option>
            </select>

            <button type="submit">Create</button>
        </form>
    </div>
</div>

<script>
    function openTab(event, tabId) {

        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => content.classList.remove('active'));

        const tabLinks = document.querySelectorAll('.tab-link');
        tabLinks.forEach(link => link.classList.remove('active'));

        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('.tab-link').click();
    });


    function openModal() {
        document.getElementById('create-user-modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('create-user-modal').style.display = 'none';
    }

    // Optional: Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('create-user-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
</script>
