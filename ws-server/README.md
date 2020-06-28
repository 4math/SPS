## Install all packages
```
npm install -g webpack
npm install
```

## Copy env
```
cp .env.example .env
```

## Run dev version
```
npm run start:dev
```

## Run production version
```
npm run start:prod
```

## Project's structure
 - `app` folder:
   - `app.ts` - entry point where websocket server is executed. Also, it controls users' connections to the server and the connection to the Redis server and Redis channel subscritpion.
- `utils` folder:
  - `colors.ts` - contains text colors for the terminal
  - `logger.ts` - simple logger. Be careful! It is not tested and might not work as expected. 