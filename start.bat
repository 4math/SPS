
cd /d "client"
start npm run serve 

cd /d "../backend"
start php artisan serve --host 0.0.0.0

cd /d "../ws-server"
start npm run start:dev