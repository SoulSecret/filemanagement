<nav class="sidebar sidebar-offcanvas" id="sidebar"> 
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <?php
        if ($_SESSION['role'] == 0) {
        ?>
        <li class="nav-item nav-category">Documents</li>
        <li class="nav-item">
            <a class="nav-link" href="new_document.php">
                <i class="bi bi-file-earmark-arrow-up fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title">Upload Documents</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="documents_lists.php">
                <i class="bi bi-file-earmark-post fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title">Documents List</span>
            </a>
        </li>

        <li class="nav-item nav-category">Folders</li>
        <li class="nav-item">
            <a class="nav-link" href="new_folder.php">
                <i class="bi bi-folder fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title">Folders</span>
            </a>
        </li>
        <li class="nav-item nav-category">Group</li>
        <li class="nav-item">
            <a class="nav-link" href="shared_folder.php">
                <i class="bi bi-archive fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title">Shared Folders</span>
            </a>
        </li>
        <li class="nav-item nav-category">Settings</li>
        <li class="nav-item">
            <a class="nav-link" href="user_pin_setup.php">
                <i class="bi bi bi-file-lock2 fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title">PIN Setup</span>
            </a>
        </li>
        <?php
                      $id = $_SESSION['user_id'];
                      $sql = "SELECT * from users where user_id = $id";
                      $result = $conn->query($sql);

                      if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                         ?>
        <li class="nav-item">
            <a class="nav-link" href="php/logout.php">
                <i class="bi bi-door-open fs-5"></i> &nbsp; &nbsp;
                <span class="menu-title"><h5>Logout</h5><?=$row['user_email']?></span>
            </a>
        </li>
        <?php
                        }
                      } else {
                        echo "0 results";
                      }
                      ?>
        <?php
        } else {
        ?>
        <?php
        }
        ?>
    </ul>
</nav>
