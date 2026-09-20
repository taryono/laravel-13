php artisan stub:publish
Perintah ini akan membuat folder stubs/ di root projek Anda. Di sana Anda akan melihat file seperti controller.stub, model.stub, dll.
Perintah ini akan membuat folder stubs/ di root projek Anda. Di sana Anda akan melihat file seperti controller.stub, model.stub, dll.
php artisan make:crud-yajra Document
composer require nekhbet/laravel-collective-html


 php artisan make:crud-yajra Schedule --columns=id,name,competence_id,certification_id,date,start,end,location_id

 php artisan make:crud-yajra AccessLog


 # Clear semua cache Laravel
php artisan optimize:clear

# Atau satu per satu
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear

# Regenerate autoload
composer dump-autoload

# Restart queue worker (jika ada)
php artisan queue:restart


# Buat seeder
php artisan make:seeder MenuManagementMenuSeeder

# Isi file sesuai kebutuhan

# Jalankan
php artisan db:seed --class=MenuManagementMenuSeeder

php artisan make:crud-yajra CrudGenerator

# Default menggunakan namespace Models
php artisan make:crud-yajra Product --columns=id,name,price

# Menggunakan namespace custom
php artisan make:crud-yajra Product --columns=id,name,price --namespace=App\\Models 

# Tanpa membuat menu
php artisan make:crud-yajra Product --columns=id,name,price --no-menu

#### Kalau migration lain bermasalah, jalankan spesifik file ini:

$ php artisan migrate --path=database/migrations/2026_09_19_154734_create_sliders_table.php