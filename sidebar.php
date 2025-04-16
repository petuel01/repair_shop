<!-- filepath: c:\xampp\htdocs\repair_shop\sidebar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">CLINTECH ENTERPRISE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start custom-offcanvas" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <!-- Products Section -->
                    <li class="nav-item">
                        <a class="nav-link" href="add_product.php">
                            <i class="fas fa-plus-circle"></i> Add Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product_list.php">
                            <i class="fas fa-list"></i> View Products
                        </a>
                    </li>

                    <!-- Sales Section -->
                    <li class="nav-item">
                        <a class="nav-link" href="sell_product.php">
                            <i class="fas fa-shopping-cart"></i> Sell Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sales_list.php">
                            <i class="fas fa-file-invoice-dollar"></i> View Sales
                        </a>
                    </li>

                    <!-- Repairs Section -->
                    <li class="nav-item">
                        <a class="nav-link" href="add_repair.php">
                            <i class="fas fa-tools"></i> Add Repair
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="repair_list.php">
                            <i class="fas fa-wrench"></i> View Repairs
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom CSS -->
<style>
    .custom-offcanvas {
        background-color: #343a40; /* Light dark background */
        color: white; /* White text */
    }
    .custom-offcanvas .nav-link {
        color: white; /* Ensure links are white */
    }
    .custom-offcanvas .nav-link:hover {
        color: #adb5bd; /* Optional: Light gray on hover */
    }
</style>