<?php
if (isset($_POST["LoginOut"])) {
  session_destroy();
  exit(header("Location: ./signin.php"));
}
?>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
                    <nav class="navbar bg-light navbar-light">
                        <!-- <a href="index.php" class="navbar-brand mx-4 mb-3">
                            <h5 class="text-primary">parapharmaciemcs</h5>
                        </a> -->
                        <div class="navbar-nav w-100">
                            <a href="index.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Commandes</a>
                            <a href="Analytique.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Analytique</a>
                            <a href="prodects.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>les produits</a>
                            <a href="pharmacy.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>pharmacie</a>
                            <a href="categories.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Catégories</a>
                            <a href="marques.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>les marques </a>
                            <a href="subcategories.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>sous-catégories</a>
                            <a href="create.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Nouvelle produit</a>
                            <a href="newPharmacy.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Nouvelle pharmacie</a>
                            <a href="createMarques.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Nouvelle marques</a>
                            <a href="createCategory.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Nouvelle Catégorie</a>
                            <a href="createSubcategorie.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Nouvelle sous-catégorie</a>
                            <span  class="nav-item nav-link">
                                <form action="./index.php" method="POST" aria-labelledby="profileDropdown">
                                    <button name="LoginOut" class="btn btn-danger m-2" type="Submit">
                                        déconnecter
                                    </button>
                                </form>
                            </span>
                        </div>
                    </nav>
                </div>