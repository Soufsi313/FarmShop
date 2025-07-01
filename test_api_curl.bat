@echo off
echo === Test API Cart Location avec curl ===

echo.
echo 1. Test sans authentification (doit retourner erreur 401 ou redirection)
curl -X POST http://localhost:8000/panier-location/ajouter ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"product_id\": 1, \"start_date\": \"2025-07-02\", \"end_date\": \"2025-07-06\"}" ^
  -v

echo.
echo.
echo 2. Affichage de la page de connexion pour récupérer CSRF token
curl -c cookies.txt http://localhost:8000/login > login_page.html

echo.
echo 3. Extraction du token CSRF
for /f "tokens=2 delims='" %%i in ('findstr "csrf-token" login_page.html') do set CSRF_TOKEN=%%i
echo Token CSRF trouvé: %CSRF_TOKEN%

echo.
echo 4. Test avec token CSRF (sans session - doit toujours échouer)
curl -X POST http://localhost:8000/panier-location/ajouter ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "X-CSRF-TOKEN: %CSRF_TOKEN%" ^
  -b cookies.txt ^
  -d "{\"product_id\": 1, \"start_date\": \"2025-07-02\", \"end_date\": \"2025-07-06\"}" ^
  -v

echo.
echo === Test terminé ===
pause
