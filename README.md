# Produktové API
Aplikace sloužící prosprávu produktů uložených v persistentní storage. 
Implementováno jako REST API.

#Instrukce k rozběhnutí
 
- v kořenovém adresáři aplikace spustit
 
docker-compose up --build
 
- Spustí aplikaci na http://localhost:8000
 
- v lumen_app spustit databázové migrace
 
 php artisan migrate
 
 #Časové zdržení 
  - instalace potřebných komponet k testování - Insomnia a Docker desktop. (neměl jsem na aktuálním počítači k dispozici)
  
 #Rozšíření aplikace 
  
  - testy: PHPUnit pro psaní a spouštění testů (využijeme třídu TestCase k nastavení testů)
  - coding standard: nainstalujeme PHP_CodeSniffer pro zajištění konzistentního kódu.
  - Statická analýza: Použijte PHPStan pro detekci problémů v kódu, které nejsou viditelné během běhu aplikace.
  - zabezpečení API:  Autentizace a autorizace - buď pomocí JSON Web Token nebo Passport (OAuth2)Šifrování a bezpečnostní hlavičky, pro ochranu proti DoS útokům omezíme počet požadavků na API pomocí rate limitingpřidání monitoringu a logování
  - Filtrace podle názvu by jsem řešil pomocí $query->where
   
           if ($request->has('name')) {
               $query->where('name', 'like', '%' . $request->input('name') . '%');
           }
   
  - Stránkování pomocí paginate ($query->paginate)