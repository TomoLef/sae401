<?php
/**
 * API Endpoint for managing various entities (Store, Product, Category, Stock, Employee, Brand).
 * 
 * This API supports CRUD operations (GET, POST, PUT, DELETE) for different entities.
 * It validates API keys for non-GET requests and handles requests based on the `action` parameter.
 * 
 * @package SAE401
 * @author  
 * @license MIT
 * @version 1.0
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require __DIR__ . '/../bootstrap.php';
use Entity\Store;
use Entity\Product;
use Entity\Category;
use Entity\Stock;
use Entity\Employee;
use Entity\Brand;

define('API_KEY', 'e8f1997c763');

$request_method = $_SERVER["REQUEST_METHOD"];

/**
 * Validate the API key for non-GET requests.
 * 
 * @return void
 */
function validateApiKey() {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        return;
    }
    if (!isset($headers['Api']) || $headers['Api'] != API_KEY) {
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['message' => 'Unauthorized']);
        exit();
    }
}
validateApiKey();

switch ($request_method) {
    /**
     * Handle GET requests.
     * 
     * Supported actions:
     * - `magasin`: Retrieve all stores or a specific store by ID.
     * - `produit`: Retrieve all products, a specific product by ID, or filter products by criteria.
     * - `categorie`: Retrieve all categories or a specific category by ID.
     * - `marque`: Retrieve all brands or a specific brand by ID.
     * - `stock`: Retrieve all stocks or a specific stock by ID.
     * - `employe`: Retrieve all employees, a specific employee by ID, or authenticate an employee.
     */
    case 'GET':
        if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "magasin" && !isset($_REQUEST["id"])) {
            $storeRepository = $entityManager->getRepository(Store::class);
            $stores = $storeRepository->findAll();
            foreach ($stores as $store) {
                $storeArray[] = $store->jsonSerialize();
            }
            echo json_encode($storeArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "magasin" && isset($_REQUEST["id"])) {
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["id"]);
            echo json_encode($store->jsonSerialize());
        }

        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "produit" && !isset($_REQUEST["id"]) && !isset($_REQUEST["limit"]) && !isset($_REQUEST["priceMin"]) && !isset($_REQUEST['priceMax'])) {
            $produitRepository = $entityManager->getRepository(Product::class);
            $produits = $produitRepository->findAll();
            foreach ($produits as $produit) {
                $produitArray[] = $produit->jsonSerialize();
            }
            echo json_encode($produitArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "produit" && isset($_REQUEST["id"])) {
            $produitRepository = $entityManager->getRepository(Product::class);
            $produit = $produitRepository->find($_REQUEST["id"]);   
            echo json_encode($produit->jsonSerialize());
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "produit" && isset($_REQUEST["limit"])) {
            $produitRepository = $entityManager->getRepository(Product::class);
            $produits = $produitRepository->findAll();

            for ($i=0; $i < $_REQUEST["limit"] ; $i++) { 
                $produitArray[] = $produits[$i]->jsonSerialize();
            }
            echo json_encode($produitArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "count") {
            $countRepository = $entityManager->getRepository(Product::class);
            $count = $countRepository->count([]);
            echo json_encode(['count' => $count]);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "annee") {
            $produitRepository = $entityManager->getRepository(Product::class);
            $produits = $produitRepository->findAll();
            $anneeArray = [];
            foreach ($produits as $produit) {
                $anneeArray[] = $produit->getModelYear();
            }
            echo json_encode(array_values(array_unique($anneeArray)));
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "produit" && (isset($_REQUEST["priceMin"]) || isset($_REQUEST["priceMax"]) || isset($_REQUEST["year"]) || isset($_REQUEST["category"]) || isset($_REQUEST["brand"]))) {
            $criteria = [];
            $qb = $entityManager->createQueryBuilder();
            $qb->select('p')
               ->from(Product::class, 'p');
        
            if (isset($_REQUEST["priceMin"]) && isset($_REQUEST["priceMax"])) {
                $qb->andWhere('p.list_price BETWEEN :priceMin AND :priceMax')
                   ->setParameter('priceMin', $_REQUEST["priceMin"])
                   ->setParameter('priceMax', $_REQUEST["priceMax"]);
            } elseif (isset($_REQUEST["priceMin"])) {
                $qb->andWhere('p.list_price >= :priceMin')
                   ->setParameter('priceMin', $_REQUEST["priceMin"]);
            } elseif (isset($_REQUEST["priceMax"])) {
                $qb->andWhere('p.list_price <= :priceMax')
                   ->setParameter('priceMax', $_REQUEST["priceMax"]);
            }
        
            if (isset($_REQUEST["year"])) {
                $qb->andWhere('p.model_year = :year')
                   ->setParameter('year', $_REQUEST["year"]);
            }
        
            if (isset($_REQUEST["category"])) {
                $qb->join('p.category', 'c')
                   ->andWhere('c.category_id = :category')
                   ->setParameter('category', $_REQUEST["category"]);
            }
            
            if (isset($_REQUEST["brand"])) {
                $qb->join('p.brand', 'b')
                   ->andWhere('b.brand_id = :brand')
                   ->setParameter('brand', $_REQUEST["brand"]);
            }
        
            $query = $qb->getQuery();
            $produits = $query->getResult();
        
            $produitArray = [];
            foreach ($produits as $produit) {
                $produitArray[] = $produit->jsonSerialize();
            }
            echo json_encode($produitArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "categorie" && !isset($_REQUEST["id"])) {
            $categorieRepository = $entityManager->getRepository(Category::class);
            $categories = $categorieRepository->findAll();
            foreach ($categories as $categorie) {
                $categorieArray[] = $categorie->jsonSerialize();
            }
            echo json_encode($categorieArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "categorie" && isset($_REQUEST["id"])) {
            $categorieRepository = $entityManager->getRepository(Product::class);
            $categorie = $categorieRepository->find($_REQUEST["id"]);
            echo json_encode($categorie->jsonSerialize());
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "marque" && !isset($_REQUEST["id"])) {
            $marqueRepository = $entityManager->getRepository(Brand::class);
            $marques = $marqueRepository->findAll();
            foreach ($marques as $marque) {
                $marqueArray[] = $marque->jsonSerialize();
            }
            echo json_encode($marqueArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "marque" && isset($_REQUEST["id"])) {
            $brandRepository = $entityManager->getRepository(brand::class);
            $brand = $brandRepository->find($_REQUEST["id"]);
            echo json_encode($brand->jsonSerialize());
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "stock" && !isset($_REQUEST["id"])) {
            $stockRepository = $entityManager->getRepository(Stock::class);
            $stocks = $stockRepository->findAll();
            foreach ($stocks as $stock) {
                $stockArray[] = $stock->toArray(2);
            }
            echo json_encode($stockArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "stock" && isset($_REQUEST["id"])) {
            $stockRepository = $entityManager->getRepository(Stock::class);
            $stock = $stockRepository->find($_REQUEST["id"]);
            echo json_encode($stock->toArray());
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "employe" && !isset($_REQUEST["id"]) && !isset($_REQUEST["email"]) && !isset($_REQUEST["password"])) {
            $employeRepository = $entityManager->getRepository(Employee::class);
            $employes = $employeRepository->findAll();
            foreach ($employes as $employe) {
                $employeArray[] = $employe->toArray();
            }
            echo json_encode($employeArray);
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "employe" && isset($_REQUEST["id"])) {
            $employeRepository = $entityManager->getRepository(Employee::class);
            $employe = $employeRepository->find($_REQUEST["id"]);
            echo json_encode($employe->toArray());
        }
        elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "employe" && isset($_REQUEST["email"]) && isset($_REQUEST["password"])) {
            $employeRepository = $entityManager->getRepository(Employee::class);
            $employe = $employeRepository->findOneBy(array('employee_email' => $_REQUEST["email"], 'employee_password' => $_REQUEST["password"]));
            echo json_encode($employe->toArray());
        }
        break;

    /**
     * Handle POST requests.
     * 
     * Supported actions:
     * - Add a new product.
     * - Add a new category.
     * - Add a new brand.
     * - Add a new store.
     * - Add a new stock.
     * - Add a new employee.
     */
    case 'POST':
        if (isset($_REQUEST["name"]) && isset($_REQUEST["brand"]) && isset($_REQUEST["category"]) && isset($_REQUEST["year"]) && isset($_REQUEST["price"])) {
            $brandRepository = $entityManager->getRepository(Brand::class);
            $brand = $brandRepository->find($_REQUEST["brand"]);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $category = $categoryRepository->find($_REQUEST["category"]);

            $product = new Product();
            $product->setProductName($_REQUEST["name"]);
            $product->setBrand($brand);
            $product->setCategory($category);
            $product->setModelYear($_REQUEST["year"]);
            $product->setListPrice($_REQUEST["price"]);
            $entityManager->persist($product);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["categoryName"])) {
            $category = new Category();
            $category->setCategoryName($_REQUEST["categoryName"]);
            $entityManager->persist($category);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["brandName"])) {
            $brand = new Brand();
            $brand->setBrandName($_REQUEST["brandName"]);
            $entityManager->persist($brand);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["storeName"]) && isset($_REQUEST["phone"]) && isset($_REQUEST["mail"]) && isset($_REQUEST["street"]) && isset($_REQUEST["city"]) && isset($_REQUEST["state"]) && isset($_REQUEST["zip"])) {
            $store = new Store();
            $store->setStoreName($_REQUEST["storeName"]);
            $store->setPhone($_REQUEST["phone"]);
            $store->setEmail($_REQUEST["mail"]);
            $store->setStreet($_REQUEST["street"]);
            $store->setCity($_REQUEST["city"]);
            $store->setState($_REQUEST["state"]);
            $store->setZipCode($_REQUEST["zip"]);
            $entityManager->persist($store);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["store"]) && isset($_REQUEST["product"]) && isset($_REQUEST["quantity"])) {
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["store"]);
            $productRepository = $entityManager->getRepository(Product::class);
            $product = $productRepository->find($_REQUEST["product"]);

            $stock = new Stock();
            $stock->setStore($store);
            $stock->setProduct($product);
            $stock->setQuantity($_REQUEST["quantity"]);
            $entityManager->persist($stock);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["store"]) && isset($_REQUEST["employeeName"]) && isset($_REQUEST["employeeEmail"]) && isset($_REQUEST["employeePassword"]) && isset($_REQUEST["role"])) {
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["store"]);

            $employee = new Employee();
            $employee->setStore($store);
            $employee->setEmployeeName($_REQUEST["employeeName"]);
            $employee->setEmployeeEmail($_REQUEST["employeeEmail"]);
            $employee->setEmployeePassword($_REQUEST["employeePassword"]);
            $employee->setEmployeeRole($_REQUEST["role"]);
            $entityManager->persist($employee);
            $entityManager->flush();
        }
        break;

    /**
     * Handle PUT requests.
     * 
     * Supported actions:
     * - Update a product.
     * - Update a category.
     * - Update a brand.
     * - Update a store.
     * - Update a stock.
     * - Update an employee.
     */
    case 'PUT':
        if (isset($_REQUEST["id"]) && isset($_REQUEST["name"]) && isset($_REQUEST["brand"]) && isset($_REQUEST["category"]) && isset($_REQUEST["year"]) && isset($_REQUEST["price"])) {
            $productRepository = $entityManager->getRepository(Product::class);
            $product = $productRepository->find($_REQUEST["id"]);
            $brandRepository = $entityManager->getRepository(Brand::class);
            $brand = $brandRepository->find($_REQUEST["brand"]);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $category = $categoryRepository->find($_REQUEST["category"]);

            $product->setProductName($_REQUEST["name"]);
            $product->setBrand($brand);
            $product->setCategory($category);
            $product->setModelYear($_REQUEST["year"]);
            $product->setListPrice($_REQUEST["price"]);
            $entityManager->persist($product);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["categoryName"])) {
            $categoryRepository = $entityManager->getRepository(Category::class);
            $category = $categoryRepository->find($_REQUEST["id"]);

            $category->setCategoryName($_REQUEST["categoryName"]);
            $entityManager->persist($category);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["brandName"])) {
            $brandRepository = $entityManager->getRepository(Brand::class);
            $brand = $brandRepository->find($_REQUEST["id"]);

            $brand->setBrandName($_REQUEST["brandName"]);
            $entityManager->persist($brand);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["storeName"]) && isset($_REQUEST["phone"]) && isset($_REQUEST["mail"]) && isset($_REQUEST["street"]) && isset($_REQUEST["city"]) && isset($_REQUEST["state"]) && isset($_REQUEST["zip"])) {
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["id"]);

            $store->setStoreName($_REQUEST["storeName"]);
            $store->setPhone($_REQUEST["phone"]);
            $store->setEmail($_REQUEST["mail"]);
            $store->setStreet($_REQUEST["street"]);
            $store->setCity($_REQUEST["city"]);
            $store->setState($_REQUEST["state"]);
            $store->setZipCode($_REQUEST["zip"]);
            $entityManager->persist($store);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["store"]) && isset($_REQUEST["product"]) && isset($_REQUEST["quantity"])) {
            $stockRepository = $entityManager->getRepository(Stock::class);
            $stock = $stockRepository->find($_REQUEST["id"]);
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["store"]);
            $productRepository = $entityManager->getRepository(Product::class);
            $product = $productRepository->find($_REQUEST["product"]);

            $stock->setStore($store);
            $stock->setProduct($product);
            $stock->setQuantity($_REQUEST["quantity"]);
            $entityManager->persist($stock);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["store"]) && isset($_REQUEST["employeeName"]) && isset($_REQUEST["employeeEmail"]) && isset($_REQUEST["employeePassword"]) && isset($_REQUEST["role"])) {
            $employeeRepository = $entityManager->getRepository(Employee::class);
            $employee = $employeeRepository->find($_REQUEST["id"]);
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["store"]);

            $employee->setStore($store);
            $employee->setEmployeeName($_REQUEST["employeeName"]);
            $employee->setEmployeeEmail($_REQUEST["employeeEmail"]);
            $employee->setEmployeePassword($_REQUEST["employeePassword"]);
            $employee->setEmployeeRole($_REQUEST["role"]);
            $entityManager->persist($employee);
            $entityManager->flush();
        }
        break;
    /**
     * Handle DELETE requests.
     * 
     * Supported actions:
     * - Delete a product.
     * - Delete a category.
     * - Delete a brand.
     * - Delete a store.
     * - Delete a stock.
     * - Delete an employee.
     */
    case 'DELETE':
        if (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "produit") {
            $productRepository = $entityManager->getRepository(Product::class);
            $product = $productRepository->find($_REQUEST["id"]);
            $entityManager->remove($product);
            $entityManager->flush();
        }
        elseif (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "categorie") {
            $categoryRepository = $entityManager->getRepository(Category::class);
            $category = $categoryRepository->find($_REQUEST["id"]);
            $entityManager->remove($category);
            $entityManager->flush();
        }
        if (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "marque") {
            $brandRepository = $entityManager->getRepository(Brand::class);
            $brand = $brandRepository->find($_REQUEST["id"]);
            $entityManager->remove($brand);
            $entityManager->flush();
        }
        if (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "magasin") {
            $storeRepository = $entityManager->getRepository(Store::class);
            $store = $storeRepository->find($_REQUEST["id"]);
            $entityManager->remove($store);
            $entityManager->flush();
        }
        if (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "stock") {
            $stockRepository = $entityManager->getRepository(Stock::class);
            $stock = $stockRepository->find($_REQUEST["id"]);
            $entityManager->remove($stock);
            $entityManager->flush();
        }
        if (isset($_REQUEST["id"]) && isset($_REQUEST["action"]) && $_REQUEST["action"] == "employe") {
            $employeeRepository = $entityManager->getRepository(Employee::class);
            $employee = $employeeRepository->find($_REQUEST["id"]);
            $entityManager->remove($employee);
            $entityManager->flush();
        }
        break;

    default:
        header('HTTP/1.0 405 Method Not Allowed');
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>