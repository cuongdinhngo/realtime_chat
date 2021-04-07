# REALTIME CHAT & NOTIFICATION: LARAVEL, MySQL, REDIS, LARAVEL ECHO, SOCKET.IO Client

You can read more about my project to understand the process of broadcasting with Redis, Laravel Echo Server, Socket.IO Client via [my LinkedIn](https://www.linkedin.com/pulse/realtime-chat-laravel-mysql-redis-echo-socketio-client-cuong-dinh-ngo)

1) Go to `echo-docker` folder to start containers
2) Run command: `docker-composer up -d` at `echo-docker`
3) Check running dockers: `docker ps`
4) Get IPAddresss of `chat_db` and `chat_redis` containers: `docker inspect chat_redis | grep IPAddress`
5) Make `.env` file: `cp .env.example .env`
6) Update `.env` file at `socket_app` folder
<pre>
DB_CONNECTION=mysql
DB_HOST=chat_db
DB_PORT=3306
DB_DATABASE=chat_app
DB_USERNAME=root
DB_PASSWORD=root@secret123

BROADCAST_DRIVER=redis
CACHE_DRIVER=file
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=chat_redis
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis
REDIS_PREFIX=""
</pre>
7) Configure project as below commands:
<pre>
docker exec -it app_server bash
composer install
npm install
npm run dev
php artisan key:generate
php artisan migrate
php artisan queue:work
</pre>
8) Open other terminal to run this commmand:
<pre>
npm install -g laravel-echo-server
laravel-echo-server start
</pre>
9) Start chat project `http://localhost:8000`
