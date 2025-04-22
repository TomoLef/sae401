document.addEventListener("DOMContentLoaded", function() {
    let loginBtn = document.getElementById("login-btn");
    if (loginBtn) {
        loginBtn.addEventListener("click", function() {
        document.getElementById("form-container").classList.replace('cacher', 'visible');
        fetch("../page/connectionForm.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("form-content").innerHTML = data;
        })
        .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let allEmployeesBtn = document.getElementById("allEmployees-btn");
    if (allEmployeesBtn) {
        allEmployeesBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/allEmployeesForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let storeEmployeeBtn = document.getElementById("storeEmployee-btn");
    if (storeEmployeeBtn) {
        storeEmployeeBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/storeEmployeeForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let newEmployeeBtn = document.getElementById("newEmployee-btn");
    if (newEmployeeBtn) {
        newEmployeeBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/newEmployeeForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let infoBtn = document.getElementById("info-btn");
    if (infoBtn) {
        infoBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/infoForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let brandBtn = document.getElementById("brand-btn");
    if (brandBtn) {
        brandBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/brandForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let categoryBtn = document.getElementById("category-btn");
    if (categoryBtn) {
        categoryBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/categoryForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let productBtn = document.getElementById("product-btn");
    if (productBtn) {
        productBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/productForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }
    let stockBtn = document.getElementById("stock-btn");
    if (stockBtn) {
        stockBtn.addEventListener("click", function() {
            document.getElementById("form-container").classList.replace('cacher', 'visible');
            fetch("../page/stockForm.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("form-content").innerHTML = data;
            })
            .catch(error => console.error("Erreur lors du chargement du formulaire :", error));
        });
    }

    let closeBtn = document.getElementById("close-btn");
    document.addEventListener("click", function(event) {
        if (event.target.id === "close-btn") {
            document.getElementById("form-container").classList.replace('visible', 'cacher');
        }
    });
});