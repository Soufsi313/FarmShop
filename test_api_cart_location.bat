@echo off
echo === Test de l'API du panier de location ===
echo.

rem Test de la route de compatibilite /api/rentals/book
echo 1. Test POST /api/rentals/book (route de compatibilite)
curl -X POST "http://127.0.0.1:8000/api/rentals/book" ^
     -H "Content-Type: application/json" ^
     -H "Accept: application/json" ^
     -H "X-CSRF-TOKEN: test" ^
     -d "{\"product_id\": 23, \"quantity\": 1, \"rental_duration_days\": 7, \"rental_start_date\": \"2025-07-02\"}" ^
     -w "\nStatus: %%{http_code}\n\n"

echo.

rem Test de la nouvelle route /api/cart-location/quick-add
echo 2. Test POST /api/cart-location/quick-add/23 (nouvelle API)
curl -X POST "http://127.0.0.1:8000/api/cart-location/quick-add/23" ^
     -H "Content-Type: application/json" ^
     -H "Accept: application/json" ^
     -H "X-CSRF-TOKEN: test" ^
     -d "{\"quantity\": 1, \"rental_duration_days\": 5, \"rental_start_date\": \"2025-07-03\"}" ^
     -w "\nStatus: %%{http_code}\n\n"

echo.

rem Test de l'API count
echo 3. Test GET /api/cart-location/count
curl -X GET "http://127.0.0.1:8000/api/cart-location/count" ^
     -H "Accept: application/json" ^
     -w "\nStatus: %%{http_code}\n\n"

echo Tests termines.
