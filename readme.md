## Stack
- Docker > ./DockerFile
- Docker-Compose /.docker-compose.yml
- PHP 7.4
- Laravel 5.8.38
- PHPUnit 7.5
- MySql 5.7.22
- .env (Config requises)
```
APP_URL=http://ie-exo.test
APP_SERVICE="ie-exo.test"
APP_PORT=3021
APP_PORT_SSL=449
FORWARD_DB_PORT=3394
```

## Set Up
- Démarrer le projet  => `docker-compose build`
- Lancer les migrations de la DB => `docker-compose exec ie_app php artisan migrate`
- Réinitialiser la DB et l'auto-increment => `docker-compose exec ie_app php artisan refresh`
- Lancer les tests PHP Unit => `docker-compose exec ie_app composer test`
- Lancer un test en particulier => `docker-compose exec ie_app composer test -- --filter=test_an_item_can_be_created`

## Model et Associations
- J'ai généré les migrations de la DB et les models avec la commande
`php artisan make:migration Item -m`
- Les méthodes RollBack ont été créées afin de bien gérer le refresh de la DB
- Le Model Item est le plus complet (App\Item). La persistance des datas est régie grâce à 
`protected $fillable =['id','category_id','sale_id','description','auction_type','pricing'];`
- Le champ updated_at créé automatiquement par Laravel est devenu l'alias "last_updated" grâce à l'attribut `const UPDATED_AT = "last_updated";
- Les champs pricing et pricing.estimates sont stockés en DB avec un type JSON. On peut manipuler ces datas et effectuer des requêtes grâce au cast array
``` PHP
protected $casts = [
        'pricing' => 'array',
        'pricing.estimates'=>'array'
    ];
```
- Les associations OneToMany (Sale et Category) sont également déclarées dans App\Item
- Des factorys pour chaque Model ont été créé dans database\factories\*Factory.php

## Validation et Tests
- J'ai créé des class Validator pour chacun des models. App\Requests\*StoreRequest
- La classe App\Requests\ItemStoreRequest est la plus complète. Des messages d'erreurs ont été déclarés pour chaque type d'erreur.
- Les tests sont de type "fonctionnel", ils sont stockés dans tests\Feature\*Test.php
- J'utilise le trait RefreshDatabase pour bien isoler chaque Test Fonctionnel
- Le test ItemTest est le plus complet. Tous les tests déclarés sont mirroirs avec la classe App\Requests\ItemStoreRequest
- Test Postam , la méthode failedValidation de App\Requests\ItemStoreRequest renvoie les messages d'erreurs demandés.
```PHP
 protected function failedValidation(Validator $validator)
    {
        
        $id_type_error = $validator->errors()->get("id") ? $validator->errors()->get("id")[0] : false;
        $auction_type_error = $validator->errors()->get("auction_type") ? $validator->errors()->get("auction_type")[0] : false ;
//      dd($this->wantsJson());
        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ]);
            throw new HttpResponseException($response, 422);
        } elseif ($auction_type_error) {
            $response = [
                'status' => true,
                'error' => $auction_type_error,
            ];
            throw new HttpResponseException(response()->json($response, 400));
        } elseif ($id_type_error ==="The id has already been taken.") {
            throw new HttpResponseException(response()->noContent(409));
        } elseif ($id_type_error) {
            $response = [
                'status' => true,
                'error' => $id_type_error,
            ];
            throw new HttpResponseException(response()->json($response, 400));
        }
    }
```
## API
- J'ai choisi de créer un Controller par Model. 
- Le model Item est formaté en tant qu'unité et en tant que collection grâce aux classes App\Http\Resources\ItemResource.php et App\Http\Resources\ItemCollection
- Afin de respecter le design data attendu par le test PostMan GET v1/items 200, 
j'ai retiré le wrap array "data" autour des collections. J'ai placé cette règle dans
  App\Providers\AppServiceProvider;
```PHP
    public function boot()
    {

    JsonResource::withoutWrapping();
    }    
```
Si souhaité on peut aussi établir le wrapp au niveau de chaque Resources\*Collection
`public static $wrap = [];`

- Afin de respecter le format des routes api avec le préfixe "v1", j'ai remplacé le route prefix "api" par "v1" dans App\Providers\RouteServiceProvider
```PHP
protected function mapApiRoutes()
  {
  Route::prefix('v1')
  ->middleware('api')
  ->namespace($this->namespace)
  ->group(base_path('routes/api.php'));
  }
```



## Refactoring

- Les tests échoués PHP Unit venaient essentiellement des erreurs de contraintes clés primaires / clés étrangères.
  J'ai donc créé des Models Factory et adapté chacun de mes tests pour y remédier.

- Les tests échoués Postman venaient de l'ordonnancement des requêtes. J'ai donc réécri la function "failedValidation" L.78 dans App\Http\Requests\ItemStoreRequest

- Je conseille d'utiliser la commande php artisan migrate:refresh entre les tests PostMan et PHPUnit afin de bien réinitialiser l'auto-incrementation des clés primaires.
  Bien sûr, cette commande est à proscrire en prod.
  Pour les tests PHPUnit, j'utuilse le test RefreshDatabase. Par conséquent, il faudrait sûrement implémenter une DB test dédiée (Sqlite par ex.) pour lancer les tests PHPUnit.





Exercice réalisé par Marie de Ubeda (Juillet 2022)


