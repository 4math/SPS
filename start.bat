
cd /d "client"
start npm run serve 

cd /d "../backend"
start php artisan serve --host 0.0.0.0
start php artisan websocket:init > websocket.log